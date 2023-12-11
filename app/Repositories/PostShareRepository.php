<?php

namespace App\Repositories;

use App\Models\PostShare;

class PostShareRepository extends BaseRepository
{
    public function __construct(PostShare $model)
    {
        parent::__construct($model);
    }
}
