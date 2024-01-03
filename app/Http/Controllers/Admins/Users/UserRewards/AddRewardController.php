<?php

namespace App\Http\Controllers\Admins\Users\UserRewards;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\UserRewardRepository;

class AddRewardController extends Controller
{
    protected $userRepository;
    protected $userRewardRepository;

    public function __construct(
        UserRepository $userRepository, 
        UserRewardRepository $userRewardRepository
    ) {
        $this->userRepository = $userRepository;
        $this->userRewardRepository = $userRewardRepository;
    }

    public function store(Request $request, $userId)
    {
        try {
            $user = $this->userRepository->find($userId);

            if (!$user) {
                return response()->json([
                    'message' => 'User does not exist',
                ], 400);
            }

            $rewardData = [
                'reward_name' => $request->input('reward_name'),
                'reward_description' => $request->input('reward_description'),
                'points' => $request->input('points'),
                'user_id' => $user->id,
            ];

            $createdUserReward = $this->userRewardRepository->create($rewardData);

            return response()->json([
                'message' => 'User reward created successfully',
                'userReward' => $createdUserReward,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to user reward',
            ], 500);
        }
    }
}
