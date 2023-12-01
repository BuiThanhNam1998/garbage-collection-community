<?php 

namespace App\Http\Controllers\Users\NotificationSettings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UserNotificationSettingRepository;

class GetNotificationSettingsController extends Controller
{
    protected $userNotificationSettingRepository;

    public function __construct(UserNotificationSettingRepository $userNotificationSettingRepository)
    {
        $this->userNotificationSettingRepository = $userNotificationSettingRepository;
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        try {
            $notificationSettings = $this->userNotificationSettingRepository->queryByCondition([
                'user_id' => $user->id
            ])->get();

            return response()->json([
                'notification_settings' => $notificationSettings,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve notification settings',
            ], 500);
        }
    }
}
