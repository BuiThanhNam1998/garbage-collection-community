<?php 

namespace App\Http\Controllers\Admins\Feedback;

use App\Http\Controllers\Controller;
use App\Repositories\FeedbackRepository;
use Illuminate\Http\Request;

class DeleteFeedbackController extends Controller
{
    protected $feedbackRepository;

    public function __construct(FeedbackRepository $feedbackRepository) 
    {
        $this->feedbackRepository = $feedbackRepository;
    }

    public function destroy(Request $request, $feedbackId)
    {
        try {
            if (!$this->feedbackRepository->find($feedbackId)) {
                return response()->json([
                    'message' => 'Feedback does not exist',
                ], 400);
            }

            $this->feedbackRepository->delete($feedbackId);


            return response()->json([
                'message' => 'Feedback deleted successfully',
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'message' => 'An error occurred while deletings feedback',
            ], 500);
        }
    }
}
