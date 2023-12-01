<?php

namespace App\Http\Controllers\Users\NotificationSettings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UserNotificationSettingRepository;

class UpdateNotificationSettingController extends Controller
{
    protected $userNotificationSettingRepository;

    public function __construct(UserNotificationSettingRepository $userNotificationSettingRepository)
    {
        $this->userNotificationSettingRepository = $userNotificationSettingRepository;
    }

    public function update(Request $request, $settingId)
    {
        $user = Auth::user();

        try {
            $setting = $this->userNotificationSettingRepository->find($settingId);

            if ($setting->user_id !== $user->id) {
                return response()->json([
                    'message' => 'You are not authorized to update this setting',
                ], 403);
            }

            $settingData = ['is_enabled' => $request->is_enabled];
            
            $this->userNotificationSettingRepository->updateByCondition(
                ['id' => $user->id], 
                $settingData
            );

            return response()->json([
                'message' => 'Notification setting updated successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update notification setting',
            ], 500);
        }
    }
}
