<?php

namespace App\Http\Controllers\Admins\Users;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;

class GetUsersController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository) 
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        try {
            $users = $this->userRepository->queryWithDetail()->get();

            return response()->json([
                'users' => $users
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching users',
            ], 500);
        }
    }
}
