<?php

namespace App\Repositories;

use App\Models\UserReward;

class UserRewardRepository extends BaseRepository
{
    public function __construct(UserReward $model)
    {
        parent::__construct($model);
    }
}
