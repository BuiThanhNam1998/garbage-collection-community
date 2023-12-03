<?php

namespace App\Http\Controllers\Users\GarbagePosts;

use App\Enums\User\GarbagePostImage\Type;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\GarbagePostRepository;
use App\Repositories\GarbagePostImageRepository;
use Illuminate\Support\Facades\DB;

class CreateGarbagePostController extends Controller
{
    protected $garbagePostRepository;
    protected $garbagePostImageRepository;

    public function __construct(
        GarbagePostRepository $garbagePostRepository,
        GarbagePostImageRepository $garbagePostImageRepository
    ) {
        $this->garbagePostRepository = $garbagePostRepository;
        $this->garbagePostImageRepository = $garbagePostImageRepository;
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $validatedData = $request->validate([
                'description' => 'required',
                'locationable_type' => 'required',
                'locationable_id' => 'required',
                'date' => 'required|date',
                'before_images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', 
                'after_images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', 
            ]);

            $user = $request->user(); 

            $garbagePostData = [
                'description' => $request->description,
                'locationable_type' => $request->locationable_type,
                'locationable_id' => $request->locationable_id,
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
