<?php

namespace App\Http\Controllers\Users\GarbagePosts\Reactions;

use App\Http\Controllers\Controller;
use App\Repositories\GarbagePostRepository;
use App\Repositories\ReactionTypeRepository;
use App\Services\Reactions\ReactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AddReactionController extends Controller
{
    protected $garbagePostRepository;
    protected $reactionTypeRepository;
    protected $reactionService;

    public function __construct(
        GarbagePostRepository $garbagePostRepository,
        ReactionTypeRepository $reactionTypeRepository,
        ReactionService $reactionService
    ) {
        $this->garbagePostRepository = $garbagePostRepository;
        $this->reactionTypeRepository = $reactionTypeRepository;
        $this->reactionService = $reactionService;
    }

    public function store(Request $request, $garbagePostId)
    {

        try {
            $validator = Validator::make($request->all(), [
                'type_id' => 'required|integer',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $typeId = $request->input('type_id');
            if (!$this->reactionTypeRepository->find($typeId)) {
                return response()->json([
                    'message' => 'This type of reaction does not exist',
                ], 400);
            }

            $post = $this->garbagePostRepository->find($garbagePostId);
            if (!$post) {
                return response()->json([
                    'message' => 'Post does not exist',
                ], 400);
            }

            $user = Auth::user(); 
            $reactionData = [
                'user_id' => $user->id,
                'type_id' => $typeId,
            ];

            $existingReaction = $post->reactions()->where($reactionData)->first();
            if ($existingReaction) {
                return response()->json([
                    'message' => 'You have already reacted to this post with this type',
                ], 400);
            }

            $createdReaction = $this->reactionService->createReactionForPost($post, $reactionData);

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
