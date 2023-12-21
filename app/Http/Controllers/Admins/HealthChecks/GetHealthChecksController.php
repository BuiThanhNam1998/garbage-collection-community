<?php

namespace App\Http\Controllers\Admins\HealthChecks;

use App\Http\Controllers\Controller;
use App\Repositories\HealthCheckRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GetHealthChecksController extends Controller
{
    protected $healthCheckRepository;

    public function __construct(HealthCheckRepository $healthCheckRepository) 
    {
        $this->healthCheckRepository = $healthCheckRepository;
    }

    public function index(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'start_date' => 'required|date',
                'end_date' => 'required|date',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }
            
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            $healthChecks = $this->healthCheckRepository->queryBetweenDate($startDate, $endDate)->get();

            return response()->json([
                'healthChecks' => $healthChecks
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching health check list',
            ], 500);
        }
    }
}
