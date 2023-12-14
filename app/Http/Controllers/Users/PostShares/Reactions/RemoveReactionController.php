<?php

namespace App\Http\Controllers\Users\PostShares\Reactions;

use App\Http\Controllers\Controller;
use App\Repositories\PostReactionRepository;
use App\Repositories\PostShareRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RemoveReactionController extends Controller
{
    protected $postReactionRepository;
    protected $postShareRepository;

    public function __construct(
        PostReactionRepository $postReactionRepository,
        PostShareRepository $postShareRepository, 
    ) {
        $this->postReactionRepository = $postReactionRepository;
        $this->postShareRepository = $postShareRepository;
    }

    public function destroy(Request $request, $postShare, $reactionId)
    {
        $user = Auth::user(); 

        try {
            $existingReaction = $this->postReactionRepository->find($reactionId);

            if (!$existingReaction || $existingReaction->user_id !== $user->id) {
                return response()->json([
                    'message' => 'Reaction not found or you are not authorized to remove this reaction',
                ], 404);
            }

            $post = $this->postShareRepository->find($postShare);
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
