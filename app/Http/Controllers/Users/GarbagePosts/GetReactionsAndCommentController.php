<?php

namespace App\Http\Controllers\Users\GarbagePosts;

use App\Http\Controllers\Controller;
use App\Repositories\GarbagePostRepository;
use Illuminate\Support\Facades\Auth;

class GetReactionsAndCommentController extends Controller
{
    protected $garbagePostRepository;

    public function __construct(
        GarbagePostRepository $garbagePostRepository,
    ) {
        $this->garbagePostRepository = $garbagePostRepository;
    }

    public function index($garbagePostId)
    {
        $user = Auth::user();

        try {
            $garbagePost = $this->garbagePostRepository->find($garbagePostId);

            if (!$garbagePost) {
                return response()->json([
                    'message' => 'Post does not exist',
                ], 400);
            }

            $garbagePost->load([
                'reactions' => function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                        ->with('type');
                },
                'comments' => function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                }
            ]);

            return response()->json([
                'id' => $garbagePostId,
                'reactions' => $garbagePost->reactions,
                'comments' => $garbagePost->comments,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving post data',
            ], 500);
        }
    }
}
