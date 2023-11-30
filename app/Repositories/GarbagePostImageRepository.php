<?php

namespace App\Repositories;

use App\Models\GarbagePostImage;

class GarbagePostImageRepository extends BaseRepository
{
    public function __construct(GarbagePostImage $model)
    {
        parent::__construct($model);
    }
}
