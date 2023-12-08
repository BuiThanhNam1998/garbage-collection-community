<?php

namespace App\Repositories;

use App\Models\AiPostQueue;

class AiPostQueueRepository extends BaseRepository
{
    public function __construct(AiPostQueue $model)
    {
        parent::__construct($model);
    }

    public function queryWithPost()
    {
        return $this->model->with(['post.images']);
    }

    public function queryByPostId($postId)
    {
        return $this->model->where('garbage_post_id', $postId);
    }
}
