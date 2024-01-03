<?php

namespace App\Http\Controllers\Users\Polls;

use App\Http\Controllers\Controller;
use App\Repositories\PollRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeletePollController extends Controller
{
    protected $pollRepository;

    public function __construct(PollRepository $pollRepository) 
    {
        $this->pollRepository = $pollRepository;
    }

    public function update(Request $request, $pollId)
    {
        try {
            $poll = $this->pollRepository->find($pollId);
            if (!$poll) {
                return response()->json([
                    'message' => 'Poll does not exist',
                ], 400);
            }

            $userId = Auth::user()->id; 
            if ($poll->creator_id !== $userId) {
                return response()->json([
                    'message' => 'You do not have permissions on this poll',
                ], 400);
            }

            $this->pollRepository->delete($pollId);

            return response()->json([
                'message' => 'Poll has been deleted successfully',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while deleting poll',
            ], 500);
        }
    }
}
