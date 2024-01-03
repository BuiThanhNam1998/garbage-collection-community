<?php

namespace App\Http\Controllers\Users\GarbagePosts\Comments;

use App\Http\Controllers\Controller;
use App\Services\Comments\CommentService;
use App\Repositories\PostCommentRepository;
use App\Repositories\GarbagePostRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CreateCommentController extends Controller
{
    protected $postCommentRepository;
    protected $garbagePostRepository;
    protected $commentService;

    public function __construct(
        PostCommentRepository $postCommentRepository, 
        GarbagePostRepository $garbagePostRepository,
        CommentService $commentService
    ) {
        $this->postCommentRepository = $postCommentRepository;
        $this->garbagePostRepository = $garbagePostRepository;
        $this->commentService = $commentService;
    }

    public function store(Request $request, $garbagePostId)
    {
        $user = Auth::user(); 

        try {
            $validator = Validator::make($request->all(), [
                'content' => 'required|string',
                'parent_id' => 'nullable|integer',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $post = $this->garbagePostRepository->find($garbagePostId);
            if (!$post) {
                return response()->json([
                    'message' => 'Post does not exist',
                ], 400);
            }

            $parentId = $request->input('parent_id');
            if ($parentId && !$this->postCommentRepository->find($parentId)) {
                return response()->json([
                    'message' => 'Parent comment does not exist',
                ], 400);
            } 

            if ($parentId && !$post->comments()->where('id', $parentId)->first()) {
                return response()->json([
                    'message' => 'Parent comment does not exist in the post',
                ], 400);
            } 

            $commentData = $request->only([
                'content',
                'parent_id'
            ]);
            $commentData['user_id'] = $user->id;

            $createdComment = $this->commentService->createCommentForPost($post, $commentData);

            return response()->json([
                'message' => 'Comment created successfully',
                'comment' => $createdComment,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create comment',
            ], 500);
        }
    }
}
