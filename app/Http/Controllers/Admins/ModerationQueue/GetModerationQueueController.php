<?php

namespace App\Http\Controllers\Admins\ModerationQueue;

use App\Http\Controllers\Controller;
use App\Repositories\ModerationQueueRepository;
use Illuminate\Support\Facades\Auth;

class GetModerationQueueController extends Controller
{
    protected $moderationQueueRepository;

    public function __construct(ModerationQueueRepository $moderationQueueRepository) 
    {
        $this->moderationQueueRepository = $moderationQueueRepository;
    }

    public function index()
    {
        try {
            $admin = Auth::guard('admin')->user();
            $moderationQueue = $this->moderationQueueRepository->queryByAdmin($admin->id)->get();

            return response()->json([
                'moderationQueue' => $moderationQueue
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching moderation queue',
            ], 500);
        }
    }
}
