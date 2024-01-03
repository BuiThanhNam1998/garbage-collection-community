<?php

namespace App\Repositories;

use App\Models\Poll;

class PollRepository extends BaseRepository
{
    public function __construct(Poll $model)
    {
        parent::__construct($model);
    }

    public function queryPublished()
    {
        return $this->model->published();
    }
}
