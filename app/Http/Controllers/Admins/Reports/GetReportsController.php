<?php

namespace App\Http\Controllers\Admins\Reports;

use App\Http\Controllers\Controller;
use App\Repositories\ReportRepository;

class GetReportsController extends Controller
{
    protected $reportRepository;

    public function __construct(ReportRepository $reportRepository) 
    {
        $this->reportRepository = $reportRepository;
    }

    public function index()
    {
        try {
            $reports = $this->reportRepository->all();

            return response()->json([
                'reports' => $reports
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching reports',
            ], 500);
        }
    }
}
