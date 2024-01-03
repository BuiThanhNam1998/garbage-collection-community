<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository extends BaseRepository
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function queryLeaderboard($startDate, $endDate)
    {
        return $this->model->select('users.*', DB::raw('SUM(points.points) as total_points'), DB::raw('COUNT(points.post_id) as post_count'))
            ->join('points', 'users.id', '=', 'points.user_id')
            ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                $q->whereDate('points.created_at', '>=', date($startDate))
                    ->whereDate('points.created_at', '<=', date($endDate));
            })
            ->groupBy('users.id')
            ->orderByDesc('total_points');
    }

    public function queryByEmail($email)
    {
        return $this->model->where('email', $email);
    }
}
