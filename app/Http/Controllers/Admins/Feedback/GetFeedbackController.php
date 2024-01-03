<?php

namespace App\Http\Controllers\Admins\Feedback;

use App\Http\Controllers\Controller;
use App\Repositories\FeedbackRepository;

class GetFeedbackController extends Controller
{
    protected $feedbackRepository;

    public function __construct(FeedbackRepository $feedbackRepository) 
    {
        $this->feedbackRepository = $feedbackRepository;
    }

    public function index()
    {
        try {
            $feedbacks = $this->feedbackRepository->all();

            return response()->json([
                'feedbacks' => $feedbacks
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching feedbacks',
            ], 500);
        }
    }
}
