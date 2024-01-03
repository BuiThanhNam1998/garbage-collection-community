<?php

namespace App\Repositories;

use App\Models\EducationResource;

class EducationResourceRepository extends BaseRepository
{
    public function __construct(EducationResource $model)
    {
        parent::__construct($model);
    }

    public function queryActive()
    {
        return $this->model->active();
    }
}
