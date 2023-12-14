<?php

namespace App\Repositories;

use App\Models\ReactionType;

class ReactionTypeRepository extends BaseRepository
{
    public function __construct(ReactionType $model)
    {
        parent::__construct($model);
    }
}
