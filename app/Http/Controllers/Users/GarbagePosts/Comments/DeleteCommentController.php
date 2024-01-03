<?php

namespace App\Http\Controllers\Users\GarbagePosts\Comments;

use App\Http\Controllers\Controller;
use App\Repositories\PostCommentRepository;
use App\Repositories\GarbagePostRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeleteCommentController extends Controller
{
    protected $postCommentRepository;
    protected $garbagePostRepository;

    public function __construct(
        PostCommentRepository $postCommentRepository,
        GarbagePostRepository $garbagePostRepository,
    ) {
        $this->postCommentRepository = $postCommentRepository;
        $this->garbagePostRepository = $garbagePostRepository;
    }

    public function destroy($garbagePostId, $commentId)
    {
        $user = Auth::user(); 

        try {
            DB::beginTransaction();

            $comment = $this->postCommentRepository->find($commentId);

            if (!$comment) {
                return response()->json([
                    'message' => 'Comment does not exist',
                ], 400);
            }

            if ($comment->user_id !== $user->id) {
                return response()->json([
                    'message' => 'You are not authorized to delete this comment',
                ], 403);
            }

            $post = $this->garbagePostRepository->find($garbagePostId);
            if (!$post) {
                return response()->json([
                    'message' => 'Post does not exist',
                ], 400);
            }

            if (!$post->comments()->where('id', $commentId)->first()) {
                return response()->json([
                    'message' => 'This comment does not exist in the post',
                ], 400);
            }

            $comment->children()->delete();
            $this->postCommentRepository->delete($commentId);

            DB::commit();

            return response()->json([
                'message' => 'Comment deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'message' => 'Failed to delete comment',
            ], 500);
        }
    }
}
