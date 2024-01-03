<?php

namespace App\Http\Controllers\Admins\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::guard('admin')->attempt($credentials)) {
            $user = Auth::guard('admin')->user();
            $token = JWTAuth::fromUser($user);
            return response()->json(compact('token'));
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
