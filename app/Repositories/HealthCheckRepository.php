<?php

namespace App\Repositories;

use App\Models\HealthCheck;

class HealthCheckRepository extends BaseRepository
{
    public function __construct(HealthCheck $model)
    {
        parent::__construct($model);
    }
}
