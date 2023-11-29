<?php

namespace App\Http\Controllers\Users\Auth;

use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;

class LogoutController extends Controller
{
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Successfully logged out']);
    }
}
