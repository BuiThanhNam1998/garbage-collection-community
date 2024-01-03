<?php

namespace App\Http\Controllers\Admins\Users;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;

class GetUserDetailController extends Controller
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

            if (!$user) {
                return response()->json([
                    'message' => 'User does not exist',
                ], 400);
            }

            $user->load(['userDetail']);

            return response()->json([
                'user' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving user profile',
            ], 500);
        }
    }
}
