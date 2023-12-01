<?php

namespace App\Repositories;

use App\Models\PostReaction;

class PostReactionRepository extends BaseRepository
{
    public function __construct(PostReaction $model)
    {
        parent::__construct($model);
    }
}
