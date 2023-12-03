<?php

namespace App\Http\Controllers\Admins\Reports;

use App\Http\Controllers\Controller;
use App\Repositories\ReportRepository;

class GetReportDetailController extends Controller
{
    protected $reportRepository;

    public function __construct(ReportRepository $reportRepository) 
    {
        $this->reportRepository = $reportRepository;
    }

    public function show($reportId)
    {
        try {
            $report = $this->reportRepository->find($reportId);

            if (!$report) {
                return response()->json([
                    'message' => 'Report does not exist',
                ], 400);
            }

            return response()->json([
                'report' => $report,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving report',
            ], 500);
        }
    }
}
