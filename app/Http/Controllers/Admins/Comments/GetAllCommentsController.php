<?php

namespace App\Http\Controllers\Admins\Comments;

use App\Http\Controllers\Controller;
use App\Repositories\PostCommentRepository;

class GetAllCommentsController extends Controller
{
    protected $postCommentRepository;

    public function __construct(PostCommentRepository $postCommentRepository) 
    {
        $this->postCommentRepository = $postCommentRepository;
    }

    public function index()
    {
        try {
            $comments = $this->postCommentRepository->all()->groupBy('garbage_post_id');

            return response()->json([
                'comments' => $comments
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving comments',
            ], 500);
        }
    }
}
