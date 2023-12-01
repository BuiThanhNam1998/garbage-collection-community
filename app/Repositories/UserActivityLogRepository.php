<?php

namespace App\Repositories;

use App\Models\UserActivityLog;

class UserActivityLogRepository extends BaseRepository
{
    public function __construct(UserActivityLog $model)
    {
        parent::__construct($model);
    }
}
