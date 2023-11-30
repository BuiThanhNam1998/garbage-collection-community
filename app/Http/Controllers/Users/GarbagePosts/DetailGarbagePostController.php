<?php

namespace App\Http\Controllers\Users\GarbagePosts;

use App\Http\Controllers\Controller;
use App\Repositories\GarbagePostRepository;
use App\Repositories\GarbagePostImageRepository;
use Illuminate\Support\Facades\Auth;

class DetailGarbagePostController extends Controller
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

    public function show($garbagePostId)
    {
        $user = Auth::user(); 

        try {
            $garbagePost = $this->garbagePostRepository->find($garbagePostId);
            $garbagePost->load(['images']);

            if ($garbagePost->user_id !== $user->id) {
                return response()->json([
                    'message' => 'You do not have permission to view the details of this post',
                ], 403);
            }


            return response()->json([
                'garbagePost' => $garbagePost,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving post details',
            ], 500);
        }
    }
}
