<?php

namespace App\Http\Controllers\Admins\Feedback;

use App\Http\Controllers\Controller;
use App\Repositories\FeedbackRepository;

class GetFeedbackDetailController extends Controller
{
    protected $feedbackRepository;

    public function __construct(FeedbackRepository $feedbackRepository) 
    {
        $this->feedbackRepository = $feedbackRepository;
    }

    public function show($feedbackIds)
    {
        try {
            $feeback = $this->feedbackRepository->find($feedbackIds);

            if (!$feeback) {
                return response()->json([
                    'message' => 'Feedback does not exist',
                ], 400);
            }

            return response()->json([
                'feeback' => $feeback,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving feeback',
            ], 500);
        }
    }
}
