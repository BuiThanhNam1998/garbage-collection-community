<?php

namespace App\Http\Controllers\Users\Auth;

use App\Enums\UserActivityLog\Activity;
use App\Http\Controllers\Controller;
use App\Repositories\UserActivityLogRepository;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class LogoutController extends Controller
{
    protected $userActivityLogRepository;

    public function __construct(UserActivityLogRepository $userActivityLogRepository)
    {
        $this->userActivityLogRepository = $userActivityLogRepository;
    }

    public function logout()
    {
        $user = Auth::user();
        JWTAuth::invalidate(JWTAuth::getToken());

        $this->userActivityLogRepository->create([
            'user_id' => $user->id,
            'activity' => Activity::LOGOUT
        ]);

        return response()->json(['message' => 'Successfully logged out']);
    }
}
