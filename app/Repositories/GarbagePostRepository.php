<?php 

namespace App\Repositories;

use App\Models\GarbagePost;

class GarbagePostRepository extends BaseRepository
{
    public function __construct(GarbagePost $model)
    {
        parent::__construct($model);
    }
}
