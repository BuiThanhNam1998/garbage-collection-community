<?php

namespace App\Repositories;

use App\Models\Street;

class StreetRepository extends BaseRepository
{
    public function __construct(Street $model)
    {
        parent::__construct($model);
    }
}
