<?php

namespace App\Http\Controllers\Users\GarbagePosts\Comments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\PostCommentRepository;

class DeleteCommentController extends Controller
{
    protected $postCommentRepository;

    public function __construct(PostCommentRepository $postCommentRepository)
    {
        $this->postCommentRepository = $postCommentRepository;
    }

    public function destroy($garbagePostId, $commentId)
    {
        $user = Auth::user(); 

        try {
            $comment = $this->postCommentRepository->find($commentId);

            if ($comment->user_id !== $user->id) {
                return response()->json([
                    'message' => 'You are not authorized to delete this comment',
                ], 403);
            }

            if ($comment->garbage_post_id !== (int) $garbagePostId) {
                return response()->json([
                    'message' => 'This comment does not exist in the post',
                ], 400);
            }

            $this->postCommentRepository->delete($commentId);

            return response()->json([
                'message' => 'Comment deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete comment',
            ], 500);
        }
    }
}
