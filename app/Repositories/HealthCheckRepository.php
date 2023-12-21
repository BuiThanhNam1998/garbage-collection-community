<?php

namespace App\Repositories;

use App\Models\HealthCheck;

class HealthCheckRepository extends BaseRepository
{
    public function __construct(HealthCheck $model)
    {
        parent::__construct($model);
    }

    public function queryBetweenDate($startDate, $endDate)
    {
        return $this->model->whereBetween('checked_at', [$startDate, $endDate]);
    }
}
