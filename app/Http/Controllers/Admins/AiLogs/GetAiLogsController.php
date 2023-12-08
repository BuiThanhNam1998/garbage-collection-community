<?php

namespace App\Http\Controllers\Admins\AiLogs;

use App\Enums\AiLog\Status;
use App\Enums\User\GarbagePost\Status as VerificationStatus;
use App\Http\Controllers\Controller;
use App\Repositories\AiLogRepository;

class GetAiLogsController extends Controller
{
    protected $aiLogRepository;

    public function __construct(AiLogRepository $aiLogRepository) 
    {
        $this->aiLogRepository = $aiLogRepository;
    }

    public function index()
    {
        try {
            $aiLogs = $this->aiLogRepository->all();
            $ailogCount = $aiLogs->count();
            $aiLogSuccesses = $aiLogs->where('status', Status::SUCCESS);
            $aiLogSuccessesCount = $aiLogSuccesses->count();
            $aiLogFailuresCount = $aiLogs->where('status', Status::FAILURE)->count();

            $aiLogSuccessApprovedsCount = $aiLogSuccesses
                ->where('verification_status', VerificationStatus::APPROVED)
                ->count();
            $aiLogSuccessRejectsCount = $aiLogSuccesses
                ->where('verification_status', VerificationStatus::REJECTED)
                ->count();

            return response()->json([
                'processedCount' => $ailogCount,
                'processedSuccessCount' => $aiLogSuccessesCount,
                'processedFailureCount' => $aiLogFailuresCount,
                'processedApprovedsCount' => $aiLogSuccessApprovedsCount,
                'processedRejectsCount' => $aiLogSuccessRejectsCount,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching ai logs',
            ], 500);
        }
    }
}
