<?php

namespace App\Http\Controllers\Users\Reports;

use App\Http\Controllers\Controller;
use App\Enums\User\Report\Status;
use App\Enums\User\Report\Type;
use App\Repositories\GarbagePostRepository;
use App\Repositories\PostCommentRepository;
use App\Repositories\ReportRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreateReportController extends Controller
{
    protected $reportRepository;
    protected $garbagePostRepository;
    protected $postCommentRepository;

    public function __construct(
        ReportRepository $reportRepository,
        GarbagePostRepository $garbagePostRepository,
        PostCommentRepository $postCommentRepository
    ) {
        $this->reportRepository = $reportRepository;
        $this->garbagePostRepository = $garbagePostRepository;
        $this->postCommentRepository = $postCommentRepository;
    }

    public function store(Request $request)
    {
        $user = Auth::user(); 

        try {
            $reportableType = $request->input('reportable_type');

            if (!in_array($reportableType, Type::ALL)) {
                return response()->json([
                    'message' => 'This type of reaction does not exist',
                ], 400);
            }

            $reportableId = $request->input('reportable_id');

            if ($reportableType === Type::POST) {
                $existPost = $this->garbagePostRepository->find($reportableId);

                if (!$existPost) {
                    return response()->json([
                        'message' => 'This post does not exist',
                    ], 400);
                }
            } 
            else if ($reportableType === Type::COMMENT) {
                $exisComment = $this->postCommentRepository->find($reportableId);

                if (!$exisComment) {
                    return response()->json([
                        'message' => 'This comment does not exist',
                    ], 400);
                }
            }

            $reportData = [
                'user_id' => $user->id,
                'reportable_id' => $reportableId,
                'reportable_type' => $reportableType,
                'reason' => $request->input('reason'),
                'status' => Status::PENDING
            ];

            $createdReport = $this->reportRepository->create($reportData);

            return response()->json([
                'message' => 'Report successfully',
                'comment' => $createdReport,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Report failed',
            ], 500);
        }
    }
}
