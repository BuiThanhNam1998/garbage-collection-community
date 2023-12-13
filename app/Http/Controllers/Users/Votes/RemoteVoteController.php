<?php

namespace App\Http\Controllers\Users\Votes;

use App\Http\Controllers\Controller;
use App\Repositories\VoteRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RemoteVoteController extends Controller
{
    protected $voteRepository;

    public function __construct(VoteRepository $voteRepository) 
    {
        $this->voteRepository = $voteRepository;
    }

    public function destroy(Request $request, $voteId)
    {
        try {
            $userId = Auth::user()->id; 

            $vote = $this->voteRepository->find($voteId);
            if (!$vote) {
                return response()->json([
                    'message' => 'Vote does not exist',
                ], 400);
            }

            if ($vote->user_id !== $userId) {
                return response()->json([
                    'message' => 'You do not have permissions',
                ], 400);
            }

            $this->voteRepository->delete($voteId);

            return response()->json([
                'message' => 'Successfully reverted vote',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed attempt to revert vote',
            ], 500);
        }
    }
}
