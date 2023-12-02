<?php

namespace App\Http\Controllers\Public\Users;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;

class GetLeaderboardController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository) 
    {
        $this->userRepository = $userRepository;
    }

    public function getLeaderboard()
    {
        try {
            $leaderboard = $this->userRepository->queryLeaderboard()
                ->select('users.*', 'points.points')
                ->take(10)
                ->get();

            return response()->json([
                'leaderboard' => $leaderboard
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching user leaderboard',
            ], 500);
        }
    }
}
