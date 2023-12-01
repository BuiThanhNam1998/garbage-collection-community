<?php

namespace App\Http\Controllers\Users\GarbagePosts\Comments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\PostCommentRepository;

class CreateCommentController extends Controller
{
    protected $postCommentRepository;

    public function __construct(PostCommentRepository $postCommentRepository)
    {
        $this->postCommentRepository = $postCommentRepository;
    }

    public function store(Request $request, $garbagePostId)
    {
        $user = Auth::user(); 

        try {
            $commentData = [
                'content' => $request->input('content'),
                'user_id' => $user->id,
                'garbage_post_id' => $garbagePostId,
            ];

            $createdComment = $this->postCommentRepository->create($commentData);

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
