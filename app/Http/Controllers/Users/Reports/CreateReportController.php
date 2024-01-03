<?php

namespace App\Http\Controllers\Users\Reports;

use App\Http\Controllers\Controller;
use App\Enums\Report\Status;
use App\Enums\Report\Type;
use App\Models\GarbagePost;
use App\Models\PostComment;
use App\Models\PostShare;
use App\Repositories\GarbagePostRepository;
use App\Repositories\PostCommentRepository;
use App\Repositories\PostShareRepository;
use App\Repositories\ReportRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreateReportController extends Controller
{
    protected $reportRepository;
    protected $garbagePostRepository;
    protected $postCommentRepository;
    protected $postShareRepository;

    public function __construct(
        ReportRepository $reportRepository,
        GarbagePostRepository $garbagePostRepository,
        PostCommentRepository $postCommentRepository,
        PostShareRepository $postShareRepository
    ) {
        $this->reportRepository = $reportRepository;
        $this->garbagePostRepository = $garbagePostRepository;
        $this->postCommentRepository = $postCommentRepository;
        $this->postShareRepository = $postShareRepository;
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

            $reportData = [
                'user_id' => $user->id,
                'reason' => $request->input('reason'),
                'status' => Status::PENDING
            ];

            $reportableId = $request->input('reportable_id');

            if ($reportableType === Type::POST) {
                $existPost = $this->garbagePostRepository->find($reportableId);

                if (!$existPost) {
                    return response()->json([
                        'message' => 'This post does not exist',
                    ], 400);
                }

                $reportData['reportable_id'] = $reportableId;
                $reportData['reportable_type'] = GarbagePost::class;
            } 
            else if ($reportableType === Type::COMMENT) {
                $exisComment = $this->postCommentRepository->find($reportableId);

                if (!$exisComment) {
                    return response()->json([
                        'message' => 'This comment does not exist',
                    ], 400);
                }

                $reportData['reportable_id'] = $reportableId;
                $reportData['reportable_type'] = PostComment::class;
            }

            else if ($reportableType === Type::POSTSHARE) {
                $exisPost = $this->postShareRepository->find($reportableId);

                if (!$exisPost) {
                    return response()->json([
                        'message' => 'This post does not exist',
                    ], 400);
                }

                $reportData['reportable_id'] = $reportableId;
                $reportData['reportable_type'] = PostShare::class;
            }

            $createdReport = $this->reportRepository->create($reportData);

            return response()->json([
                'message' => 'Report successfully',
                'report' => $createdReport,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Report failed',
            ], 500);
        }
    }
}
