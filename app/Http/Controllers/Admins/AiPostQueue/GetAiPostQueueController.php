<?php

namespace App\Http\Controllers\Admins\AiPostQueue;

use App\Http\Controllers\Controller;
use App\Repositories\AiPostQueueRepository;

class GetAiPostQueueController extends Controller
{
    protected $aiPostQueueRepository;

    public function __construct(AiPostQueueRepository $aiPostQueueRepository) 
    {
        $this->aiPostQueueRepository = $aiPostQueueRepository;
    }

    public function index()
    {
        try {
            $aiPostQueue = $this->aiPostQueueRepository->all();

            return response()->json([
                'aiPostQueue' => $aiPostQueue
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching ai post queue',
            ], 500);
        }
    }
}
