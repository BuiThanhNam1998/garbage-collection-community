<?php

namespace App\Http\Controllers\Users\GarbagePosts\Reactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\PostReactionRepository;

class RemoveReactionController extends Controller
{
    protected $postReactionRepository;

    public function __construct(PostReactionRepository $postReactionRepository)
    {
        $this->postReactionRepository = $postReactionRepository;
    }

    public function destroy(Request $request, $garbagePostId, $reactionId)
    {
        $user = Auth::user(); 

        try {
            $existingReaction = $this->postReactionRepository->find($reactionId);

            if (!$existingReaction || $existingReaction->user_id !== $user->id || $existingReaction->garbage_post_id !== (int) $garbagePostId) {
                return response()->json([
                    'message' => 'Reaction not found or you are not authorized to remove this reaction',
                ], 404);
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
