<?php

namespace App\Repositories;

use App\Models\Point;
use Illuminate\Support\Facades\DB;

class PointRepository extends BaseRepository
{
    public function __construct(Point $model)
    {
        parent::__construct($model);
    }

    public function getTotalPointByUserId($userId)
    {
        return $this->model->select(DB::raw('SUM(points) as total_points'))
            ->where('user_id', $userId)
            ->first();
    }
}
