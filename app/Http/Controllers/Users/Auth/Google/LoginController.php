<?php

namespace App\Http\Controllers\Users\Auth\Google;

use App\Enums\Social\Provider;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Repositories\SocialAccountRepository;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    protected $userRpository;
    protected $socialAccountRepository;

    public function __construct(
        UserRepository $userRpository,
        SocialAccountRepository $socialAccountRepository
    ) {
        $this->userRpository = $userRpository;
        $this->socialAccountRepository = $socialAccountRepository;
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            DB::beginTransaction();
            $socialUser = Socialite::driver('google')->stateless()->user();

            if ($socialUser) {
                $user = $this->userRpository->queryByEmail($socialUser->email)->first();

                if(!$user) {
                    $dataUser = [
                        'name' => $socialUser->name,
                        'email' => $socialUser->email,
                    ];
                    $user = $this->userRpository->create($dataUser);
                }

                $dataSocialAccount = [
                    'user_id' => $user->id,
                    'provider' => Provider::GOOGLE,
                    'provider_id' => $socialUser->id,
                    'access_token' => $socialUser->token,
                    'refresh_token' => $socialUser->refreshToken
                ];
                $this->socialAccountRepository->updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'provider' => Provider::GOOGLE,
                        'provider_id' => $socialUser->id,
                    ],
                    $dataSocialAccount
                );
                $token = JWTAuth::fromUser($user);
                DB::commit();

                return response()->json(['token' => $token]);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'An error occurred while login',
            ], 500);
        }
    }
}
