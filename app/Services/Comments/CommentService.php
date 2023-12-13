<?php

namespace App\Services\Comments;

use App\Models\GarbagePost;
use App\Models\PostShare;
use App\Repositories\PostCommentRepository;

class CommentService 
{
    protected $postCommentRepository;

    public function __construct(PostCommentRepository $postCommentRepository)
    {
        $this->postCommentRepository = $postCommentRepository;
    }

    public function createCommentForPost(GarbagePost $post, $data) {
        return $post->comments()->create($data);
    }

    public function createCommentForPostShare(PostShare $postShare, $data) {
        return $postShare->comments()->create($data);
    }
}
