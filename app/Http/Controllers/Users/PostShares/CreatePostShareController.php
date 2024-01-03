<?php

namespace App\Http\Controllers\Users\PostShares;

use App\Http\Controllers\Controller;
use App\Repositories\GarbagePostRepository;
use App\Repositories\PostShareRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreatePostShareController extends Controller
{
    protected $garbagePostRepository;
    protected $postShareRepository;

    public function __construct(
        GarbagePostRepository $garbagePostRepository,
        PostShareRepository $postShareRepository
    ) {
        $this->garbagePostRepository = $garbagePostRepository;
        $this->postShareRepository = $postShareRepository;
    }

    public function store(Request $request)
    {
        try {
            $userId = Auth::user()->id; 
            $postId = $request->input('garbage_post_id');

            if (!$this->garbagePostRepository->find($postId)) {
                return response()->json([
                    'message' => 'Post does not exist',
                ], 400);
            }

            $postShare = $this->postShareRepository->create([
                'user_id' => $userId,
                'garbage_post_id' => $postId
            ]);

            return response()->json([
                'message' => 'Post share has been created successfully',
                'postShare' => $postShare
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating post share',
            ], 500);
        }
    }
}
