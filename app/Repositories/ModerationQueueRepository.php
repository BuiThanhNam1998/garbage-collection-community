<?php

namespace App\Repositories;

use App\Models\ModerationQueue;

class ModerationQueueRepository extends BaseRepository
{
    public function __construct(ModerationQueue $model)
    {
        parent::__construct($model);
    }

    public function queryByAdmin($adminId)
    {
        return $this->model->where('admin_id', $adminId);
    }
}
