<?php

namespace App\Http\Controllers\Users\Polls\Options;

use App\Http\Controllers\Controller;
use App\Repositories\PollRepository;
use App\Repositories\PollOptionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeleteOptionController extends Controller
{
    protected $pollRepository;
    protected $pollOptionRepository;

    public function __construct(
        PollRepository $pollRepository, 
        PollOptionRepository $pollOptionRepository
    ) {
        $this->pollRepository = $pollRepository;
        $this->pollOptionRepository = $pollOptionRepository;
    }

    public function destroy(Request $request, $pollId, $optionId)
    {
        try {
            $userId = Auth::user()->id; 

            $poll = $this->pollRepository->find($pollId);
            if (!$poll) {
                return response()->json([
                    'message' => 'Poll does not exist',
                ], 400);
            }

            if ($poll->creator_id !== $userId) {
                return response()->json([
                    'message' => 'You do not have permissions on this poll',
                ], 400);
            }

            $option = $this->pollOptionRepository->find($optionId);
            if (!$option) {
                return response()->json([
                    'message' => 'Option does not exist',
                ], 400);
            }
            
            if ($option->poll_id !== (int) $pollId) {
                return response()->json([
                    'message' => 'Option does not exist in poll',
                ], 400);
            }

            $this->pollOptionRepository->delete($optionId);

            return response()->json([
                'message' => 'Option has been deleted successfully',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while deleting option',
            ], 500);
        }
    }
}
