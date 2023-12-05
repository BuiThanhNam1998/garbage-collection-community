<?php

namespace App\Repositories;

use App\Models\SocialAccount;

class SocialAccountRepository extends BaseRepository
{
    public function __construct(SocialAccount $model)
    {
        parent::__construct($model);
    }
}
