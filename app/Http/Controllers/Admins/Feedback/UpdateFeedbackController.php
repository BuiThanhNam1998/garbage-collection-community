<?php 

namespace App\Http\Controllers\Admins\Feedback;

use App\Enums\Feedback\Status;
use App\Http\Controllers\Controller;
use App\Repositories\FeedbackRepository;
use Illuminate\Http\Request;

class UpdateFeedbackController extends Controller
{
    protected $feedbackRepository;

    public function __construct(FeedbackRepository $feedbackRepository) 
    {
        $this->feedbackRepository = $feedbackRepository;
    }

    public function update(Request $request, $feedbackId)
    {
        try {
            $feedback = $this->feedbackRepository->find($feedbackId);

            if (!$feedback) {
                return response()->json([
                    'message' => 'Feedback does not exist',
                ], 400);
            }

            if (!in_array($request->status, Status::ALL)) {
                return response()->json([
                    'message' => 'Feedback status does not exist',
                ], 400);
            }

            $feedbackData = $request->only([
                'status',
                'response'
            ]);

            $feedback->update($feedbackData);

            return response()->json([
                'message' => 'Feedback has been updated',
                'feedback' => $feedback,
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'message' => 'An error occurred while updating feedback',
            ], 500);
        }
    }
}
