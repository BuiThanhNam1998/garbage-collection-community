<?php

namespace App\Http\Controllers\Users\Points;

use App\Http\Controllers\Controller;
use App\Repositories\PointRepository;
use App\Repositories\UserRewardRepository;
use Illuminate\Http\Request;

class GetPointController extends Controller
{
    protected $pointRepository;
    protected $userRewardRepository;

    public function __construct(
        PointRepository $pointRepository,
        UserRewardRepository $userRewardRepository
    ) {
        $this->pointRepository = $pointRepository;
        $this->userRewardRepository = $userRewardRepository;
    }

    public function view(Request $request)
    {
        $userId = $request->user()->id;

        try {
            $points = $this->pointRepository->getTotalPointByUserId($userId);

            $rewards = $this->userRewardRepository->queryByCondition([
                'user_id' => $userId
            ])->get();

            return response()->json([
                'point' => $points ? $points->total_points : null,
                'rewards' => $rewards,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve user points',
            ], 500);
        }
    }
}
