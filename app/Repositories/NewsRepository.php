<?php

namespace App\Repositories;

use App\Models\News;

class NewsRepository extends BaseRepository
{
    public function __construct(News $model)
    {
        parent::__construct($model);
    }

    public function queryLasted()
    {
        return $this->model->published()->orderBy('publish_at', 'desc');
    }
}
