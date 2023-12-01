<?php 

namespace App\Http\Controllers\Users\ActivityLogs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UserActivityLogRepository;

class GetActivityLogsController extends Controller
{
    protected $userActivityLogRepository;

    public function __construct(UserActivityLogRepository $userActivityLogRepository)
    {
        $this->userActivityLogRepository = $userActivityLogRepository;
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        try {
            $activityLogs = $this->userActivityLogRepository->queryByCondition([
                'user_id' => $user->id
            ])->get();

            return response()->json([
                'activity_logs' => $activityLogs,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve activity logs',
            ], 500);
        }
    }
}
