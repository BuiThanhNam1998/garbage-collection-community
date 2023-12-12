<?php

namespace App\Repositories;

use App\Models\PollOption;

class PollOptionRepository extends BaseRepository
{
    public function __construct(PollOption $model)
    {
        parent::__construct($model);
    }
}
