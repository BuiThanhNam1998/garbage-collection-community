<?php

namespace App\Http\Controllers\Users\GarbagePosts;

use App\Enums\User\GarbagePostImage\Type;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\GarbagePostRepository;
use App\Repositories\GarbagePostImageRepository;
use App\Repositories\StreetRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            $request->validate([
                'description' => 'required',
                'street_id' => 'required|integer',
                'date' => 'required|date',
                'before_images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', 
                'after_images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', 
            ]);

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

            $this->garbagePostImageRepository->deleteByCondition([
                ['garbage_post_id', '=', $garbagePostId],
            ]);

            $garbagePostData = [
                'description' => $request->description,
                'street_id' => $request->street_id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'date' => $request->date,
            ];

            $garbagePost->update($garbagePostData);

            $this->saveImagesAndCreateGarbagePostImages(
                $request->file('before_images'),
                Type::BEFORE,
                $garbagePostId
            );

            $this->saveImagesAndCreateGarbagePostImages(
                $request->file('after_images'),
                Type::AFTER,
                $garbagePostId
            );
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
                $path = $image->store('garbage_post_images');

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
