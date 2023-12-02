<?php

namespace App\Http\Controllers\Users\GarbagePosts;

use App\Http\Controllers\Controller;
use App\Models\GarbagePost;
use App\Models\GarbagePostImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreateGarbagePostController extends Controller
{
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

            $garbagePost = new GarbagePost($validatedData);
            $garbagePost->user()->associate($user);
            $garbagePost->save();   

            if ($request->hasFile('before_images') && $request->hasFile('after_images')) {
                foreach ($request->file('before_images') as $image) {
                    $path = $image->store('garbage_post_images'); 

                    $garbagePostImage = new GarbagePostImage([
                        'image_path' => $path,
                        'type' => 'bebore', 
                    ]);
                    $garbagePost->images()->save($garbagePostImage);
                }

                foreach ($request->file('after_images') as $image) {
                    $path = $image->store('garbage_post_images'); 

                    $garbagePostImage = new GarbagePostImage([
                        'image_path' => $path,
                        'type' => 'after', 
                    ]);
                    $garbagePost->images()->save($garbagePostImage);
                }
            }
            DB::commit();

            return response()->json([
                'message' => 'Create successful',
                'garbagePost' => $garbagePost,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong',
            ], 500);
        }
    }
}
