<?php 

namespace App\Http\Controllers\Admins\Users;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UpdateUserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function update(Request $request, $userId)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'email' => 'email',
                'avatar' => 'image|mimes:jpeg,png,jpg,gif|max:2048', 
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }
    
            $userData = $request->only(['name', 'email']);

            $user = $this->userRepository->find($userId);

            if (!$user) {
                return response()->json([
                    'message' => 'User does not exist',
                ], 400);
            }

            if (!empty($userData)) {
                if ($request->hasFile('avatar')) {
                    $path = $request->file('avatar')->store('public/avatar');
                    $path = str_replace('public/', '', $path);
                    $userData['avatar'] = $path; 
                }

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
