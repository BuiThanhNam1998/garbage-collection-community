<?php

namespace App\Repositories;

use App\Models\Vote;

class VoteRepository extends BaseRepository
{
    public function __construct(Vote $model)
    {
        parent::__construct($model);
    }
}
