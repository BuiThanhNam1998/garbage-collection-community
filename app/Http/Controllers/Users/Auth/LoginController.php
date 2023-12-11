<?php

namespace App\Http\Controllers\Users\Auth;

use App\Enums\UserActivityLog\Activity;
use App\Http\Controllers\Controller;
use App\Repositories\UserActivityLogRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    protected $userActivityLogRepository;

    public function __construct(UserActivityLogRepository $userActivityLogRepository)
    {
        $this->userActivityLogRepository = $userActivityLogRepository;
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = JWTAuth::fromUser($user);

            $this->userActivityLogRepository->create([
                'user_id' => $user->id,
                'activity' => Activity::LOGIN
            ]);

            return response()->json(compact('token'));
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
