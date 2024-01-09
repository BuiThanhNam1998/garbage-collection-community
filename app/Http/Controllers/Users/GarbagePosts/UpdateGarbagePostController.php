<?php

namespace App\Http\Controllers\Users\GarbagePosts;

use App\Enums\User\GarbagePostImage\Type;
use App\Enums\UserActivityLog\Activity;
use App\Http\Controllers\Controller;
use App\Repositories\GarbagePostRepository;
use App\Repositories\GarbagePostImageRepository;
use App\Repositories\StreetRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UpdateGarbagePostController extends Controller
{
    protected $garbagePostRepository;
    protected $garbagePostImageRepository;
    protected $streetRepository;

    public function __construct(
        GarbagePostRepository $garbagePostRepository,
        GarbagePostImageRepository $garbagePostImageRepository,
        StreetRepository $streetRepository
    ) {
        $this->garbagePostRepository = $garbagePostRepository;
        $this->garbagePostImageRepository = $garbagePostImageRepository;
        $this->streetRepository = $streetRepository;
    }


    public function update(Request $request, $garbagePostId)
    {
        $user = Auth::user();

        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'description' => 'required',
                'street_id' => 'required|integer',
                'date' => 'required|date',
                'before_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'after_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $garbagePost = $this->garbagePostRepository->find($garbagePostId);

            if (!$garbagePost) {
                return response()->json([
                    'message' => 'Post does not exist',
                ], 400);
            }

            if ($garbagePost->user_id !== $user->id) {
                return response()->json([
                    'message' => 'You do not have permission to update this post',
                ], 403);
            }

            if (!$this->streetRepository->find($request->street_id)) {
                return response()->json([
                    'message' => 'Street does not exist',
                ], 400);
            }

            $garbagePostData = [
                'description' => $request->description,
                'street_id' => $request->street_id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'date' => $request->date,
            ];

            $garbagePost->update($garbagePostData);

            if ($request->hasFile('before_images')) {
                $this->saveImagesAndCreateGarbagePostImages(
                    $request->file('before_images'),
                    Type::BEFORE,
                    $garbagePostId
                );
                $this->garbagePostImageRepository->deleteByCondition([
                    'garbage_post_id' => $garbagePostId,
                    'type' => Type::BEFORE,
                ]);
            }


            if ($request->hasFile('before_images')) {
                $this->saveImagesAndCreateGarbagePostImages(
                    $request->file('after_images'),
                    Type::AFTER,
                    $garbagePostId
                );
                $this->garbagePostImageRepository->deleteByCondition([
                    'garbage_post_id' => $garbagePostId,
                    'type' => Type::BEFORE,
                ]);
            }

            $garbagePost->userActivityLogs()->create([
                'user_id' => $user->id,
                'activity' => Activity::UPDATE_POST,
            ]);

            $garbagePost->load(['images']);

            DB::commit();

            return response()->json([
                'message' => 'Post has been updated successfully',
                'garbagePost' => $garbagePost,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'An error occurred while updating post',
            ], 500);
        }
    }

    protected function saveImagesAndCreateGarbagePostImages($images, $type, $garbagePostId)
    {
        if ($images) {
            foreach ($images as $image) {
                $path = $image->store('public/garbage_post_images');

                $garbagePostImageData = [
                    'garbage_post_id' => $garbagePostId,
                    'image_path' => $path,
                    'type' => $type,
                ];

                $this->garbagePostImageRepository->create($garbagePostImageData);
            }
        }
    }
}
