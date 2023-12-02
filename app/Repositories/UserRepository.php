<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function queryLeaderboard()
    {
        return User::leftJoin('points', 'users.id', '=', 'points.user_id')
            ->orderByDesc('points.points');
    }
}
