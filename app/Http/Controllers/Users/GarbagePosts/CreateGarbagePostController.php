<?php

namespace App\Http\Controllers\Users\GarbagePosts;

use App\Enums\User\GarbagePostImage\Type;
use App\Enums\UserActivityLog\Activity;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\GarbagePostRepository;
use App\Repositories\GarbagePostImageRepository;
use App\Repositories\StreetRepository;
use Illuminate\Support\Facades\DB;

class CreateGarbagePostController extends Controller
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

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'description' => 'required',
                'street_id' => 'required|integer',
                'date' => 'required|date',
                'before_images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', 
                'after_images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', 
            ]);

            $user = $request->user(); 

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
                'user_id' => $user->id, 
            ];

            $garbagePost = $this->garbagePostRepository->create($garbagePostData);

            if ($request->hasFile('before_images') && $request->hasFile('after_images')) {
                $this->saveImagesAndCreateGarbagePostImages(
                    $request->file('before_images'),
                    Type::BEFORE,
                    $garbagePost->id
                );
    
                $this->saveImagesAndCreateGarbagePostImages(
                    $request->file('after_images'),
                    Type::AFTER,
                    $garbagePost->id
                );
            }

            $garbagePost->userActivityLogs()->create([
                'user_id' => $user->id, 
                'activity' => Activity::CREATE_POST,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Post has been created successfully',
                'garbagePost' => $garbagePost->load(['images']),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'An error occurred while creating post',
            ], 500);
        }
    }

    protected function saveImagesAndCreateGarbagePostImages($images, $type, $garbagePostId)
    {
        if ($images) {
            foreach ($images as $image) {
                $path = $image->store('public/garbage_post_images');
                $path = str_replace('public/', '', $path);

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
