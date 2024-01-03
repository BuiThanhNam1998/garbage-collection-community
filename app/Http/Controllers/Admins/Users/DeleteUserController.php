<?php

namespace App\Http\Controllers\Admins\Users;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;

class DeleteUserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    public function destroy($userId)
    {
        try {
            if (!$this->userRepository->find($userId)) {
                return response()->json([
                    'message' => 'User does not exist',
                ], 400);
            }

            $this->userRepository->delete($userId);

            return response()->json([
                'message' => 'User deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete user',
            ], 500);
        }
    }
}
