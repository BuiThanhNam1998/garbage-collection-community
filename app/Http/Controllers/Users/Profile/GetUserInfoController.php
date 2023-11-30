<?php

namespace App\Http\Controllers\Users\Profile;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class GetUserInfoController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function view(Request $request)
    {
        $userId = $request->user()->id;

        $user = $this->userRepository->find($userId);
        $user->load('userDetail');

        return response()->json([
            'user' => $user,
        ]);
    }
}
