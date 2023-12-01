<?php

namespace App\Http\Controllers\Users\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Repositories\UserRepository;

class UpdatePasswordController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function update(Request $request)
    {
        $user = Auth::user(); 

        try {
            if (!Hash::check($request->input('current_password'), $user->password)) {
                return response()->json([
                    'message' => 'Current password is incorrect',
                ], 400);
            }

            $this->userRepository->updateByCondition(
                ['id' => $user->id], 
                ['password' => Hash::make($request->input('new_password'))]
            );

            return response()->json([
                'message' => 'Password was successfully changed',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while changing the password',
            ], 500);
        }
    }
}
