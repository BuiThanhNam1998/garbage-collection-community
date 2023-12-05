<?php

namespace App\Repositories;

use App\Models\Feedback;

class FeedbackRepository extends BaseRepository
{
    public function __construct(Feedback $model)
    {
        parent::__construct($model);
    }
}
