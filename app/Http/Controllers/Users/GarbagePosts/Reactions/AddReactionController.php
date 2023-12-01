<?php

namespace App\Http\Controllers\Users\GarbagePosts\Reactions;

use App\Http\Controllers\Controller;
use App\Repositories\PostReactionRepository;
use App\Enums\User\GarbagePost\Reaction\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddReactionController extends Controller
{
    protected $postReactionRepository;

    public function __construct(PostReactionRepository $postReactionRepository)
    {
        $this->postReactionRepository = $postReactionRepository;
    }

    public function store(Request $request, $garbagePostId)
    {
        $user = Auth::user(); 

        try {
            $type = $request->input('type');

            if (!in_array($type, Type::ALL)) {
                return response()->json([
                    'message' => 'This type of reaction does not exist',
                ], 400);
            }

            $reactionData = [
                'user_id' => $user->id,
                'garbage_post_id' => $garbagePostId,
                'type' => $type,
            ];

            $existingReaction = $this->postReactionRepository->queryByCondition($reactionData)->exists();

            if ($existingReaction) {
                return response()->json([
                    'message' => 'You have already reacted to this post with this type',
                ], 400);
            }

            $createdReaction = $this->postReactionRepository->create($reactionData);

            return response()->json([
                'message' => 'Reaction added successfully',
                'reaction' => $createdReaction,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to add reaction',
            ], 500);
        }
    }
}
