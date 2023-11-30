<?php 

namespace App\Http\Controllers\Users\Profile;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UpdateUserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function update(Request $request)
    {
        $userId = $request->user()->id; 

        $userData = $request->only(['name', 'email']);

        try {
            DB::beginTransaction();

            $user = $this->userRepository->find($userId);

            if (!empty($userData)) {
                $user->update($userData);
            }

            $userDetailData = $request->only(['first_name', 'last_name', 'date_of_birth', 'address']);

            if (!empty($userDetailData) && $user->userDetail) {
                $user->userDetail->update($userDetailData);
            } elseif (!empty($userDetailData)) {
                $user->userDetail()->create($userDetailData);
            }
            $user->load('userDetail');

            DB::commit();

            return response()->json([
                'message' => 'User information has been updated',
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'An error occurred while updating user information',
            ], 500);
        }
    }
}
