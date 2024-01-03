<?php

namespace App\Repositories;

use App\Models\UserFavoritePost;

class UserFavoritePostRepository extends BaseRepository
{
    public function __construct(UserFavoritePost $model)
    {
        parent::__construct($model);
    }

    public function queryByUserId($userId)
    {
        return $this->model->where('user_id', $userId);
    }
}
