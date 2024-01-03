<?php

namespace App\Http\Controllers\Users\GarbagePosts\Reactions;

use App\Http\Controllers\Controller;
use App\Repositories\PostReactionRepository;
use App\Repositories\GarbagePostRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RemoveReactionController extends Controller
{
    protected $postReactionRepository;
    protected $garbagePostRepository;

    public function __construct(
        PostReactionRepository $postReactionRepository,
        GarbagePostRepository $garbagePostRepository
    ) {
        $this->postReactionRepository = $postReactionRepository;
        $this->garbagePostRepository = $garbagePostRepository;
    }

    public function destroy(Request $request, $garbagePostId, $reactionId)
    {
        $user = Auth::user(); 

        try {
            $existingReaction = $this->postReactionRepository->find($reactionId);

            if (!$existingReaction || $existingReaction->user_id !== $user->id) {
                return response()->json([
                    'message' => 'Reaction not found or you are not authorized to remove this reaction',
                ], 404);
            }

            $post = $this->garbagePostRepository->find($garbagePostId);
            if (!$post) {
                return response()->json([
                    'message' => 'Post does not exist',
                ], 400);
            }

            if (!$post->reactions()->where('id', $reactionId)->first())
            {
                return response()->json([
                    'message' => 'This reaction does not exist in the post',
                ], 400);
            }

            $this->postReactionRepository->delete($reactionId);

            return response()->json([
                'message' => 'Reaction removed successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to remove reaction',
            ], 500);
        }
    }
}
