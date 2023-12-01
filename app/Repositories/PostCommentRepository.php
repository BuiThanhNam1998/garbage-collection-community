<?php

namespace App\Repositories;

use App\Models\PostComment;

class PostCommentRepository extends BaseRepository
{
    public function __construct(PostComment $model)
    {
        parent::__construct($model);
    }
}
