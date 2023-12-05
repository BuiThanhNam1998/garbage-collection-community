<?php

namespace App\Repositories;

use App\Models\NewsCategory;

class NewsCategoryRepository extends BaseRepository
{
    public function __construct(NewsCategory $model)
    {
        parent::__construct($model);
    }
}
