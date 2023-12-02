<?php

namespace App\Http\Controllers\Public\Users;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;

class GetUserProfileController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository) 
    {
        $this->userRepository = $userRepository;
    }

    public function show($userId)
    {
        try {
            $user = $this->userRepository->find($userId);

            $user->load([
                'userDetail',
                'garbagePosts' => function ($q) {
                    $q->approved()->with('images')->paginate(10);
                }
            ]);

            return response()->json([
                'garbagePost' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving user profile',
            ], 500);
        }
    }
}
