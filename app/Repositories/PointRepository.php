<?php

namespace App\Repositories;

use App\Models\Point;

class PointRepository extends BaseRepository
{
    public function __construct(Point $model)
    {
        parent::__construct($model);
    }
}
