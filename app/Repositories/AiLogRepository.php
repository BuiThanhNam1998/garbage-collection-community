<?php

namespace App\Repositories;

use App\Models\AiLog;

class AiLogRepository extends BaseRepository
{
    public function __construct(AiLog $model)
    {
        parent::__construct($model);
    }
}
