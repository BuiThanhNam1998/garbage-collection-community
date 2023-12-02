<?php

namespace App\Http\Controllers\Public\GarbagePosts;

use App\Http\Controllers\Controller;
use App\Repositories\GarbagePostRepository;
use App\Repositories\GarbagePostImageRepository;

class GetPostDetailController extends Controller
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
        try {
            $garbagePost = $this->garbagePostRepository->find($garbagePostId);

            if (!($garbagePost->verification_status || $garbagePost->ai_verification_status)) {
                return response()->json([
                    'message' => 'You do not have permission to view the details of this post',
                ], 403);
            }
            $garbagePost->load(['images']);

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
