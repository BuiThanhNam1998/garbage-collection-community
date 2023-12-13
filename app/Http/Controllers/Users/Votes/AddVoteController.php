<?php

namespace App\Http\Controllers\Users\Votes;

use App\Http\Controllers\Controller;
use App\Repositories\PollRepository;
use App\Repositories\PollOptionRepository;
use App\Repositories\VoteRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AddVoteController extends Controller
{
    protected $pollRepository;
    protected $pollOptionRepository;
    protected $voteRepository;

    public function __construct(
        PollRepository $pollRepository, 
        PollOptionRepository $pollOptionRepository,
        VoteRepository $voteRepository
    ) {
        $this->pollRepository = $pollRepository;
        $this->pollOptionRepository = $pollOptionRepository;
        $this->voteRepository = $voteRepository;
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'poll_id' => 'required|integer',
                'option_id' => 'required|integer',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $pollId = $request->input('poll_id');
            $poll = $this->pollRepository->find($pollId);
            if (!$poll) {
                return response()->json([
                    'message' => 'Poll does not exist',
                ], 400);
            }

            $optionId = $request->input('option_id');
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

            $userId = Auth::user()->id; 

            if ($this->voteRepository->queryByUserIdOptionId($userId, $optionId)->first()) {
                return response()->json([
                    'message' => 'Vote already exists',
                ], 400);
            }

            $vote = $this->voteRepository->create([
                'user_id' => $userId,
                'poll_id' => $pollId,
                'option_id' => $optionId,
            ]);

            return response()->json([
                'message' => 'Vote successful',
                'vote' => $vote
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Vote failed',
            ], 500);
        }
    }
}
