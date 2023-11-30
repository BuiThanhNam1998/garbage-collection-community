<?php

namespace App\Repositories;

use App\Models\UserDetail;

class UserDetailRepository extends BaseRepository
{
    public function __construct(UserDetail $userDetail)
    {
        parent::__construct($userDetail);
    }
}
