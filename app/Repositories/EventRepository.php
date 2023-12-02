<?php

namespace App\Repositories;

use App\Models\Event;

class EventRepository extends BaseRepository
{
    public function __construct(Event $model)
    {
        parent::__construct($model);
    }

    public function queryBetweenDate($startDate, $endDate)
    {
        return $this->model->whereBetween('date', [$startDate, $endDate]);
    }
}
