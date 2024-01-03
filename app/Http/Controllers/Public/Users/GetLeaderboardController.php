<?php

namespace App\Http\Controllers\Public\Users;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GetLeaderboardController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository) 
    {
        $this->userRepository = $userRepository;
    }

    public function getLeaderboard(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            $leaderboard = $this->userRepository->queryLeaderboard($startDate, $endDate)
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
