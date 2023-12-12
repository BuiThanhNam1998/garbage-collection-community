<?php

namespace App\Http\Controllers\Users\Polls;

use App\Enums\Poll\Status;
use App\Http\Controllers\Controller;
use App\Repositories\PollRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UpdatePollController extends Controller
{
    protected $pollRepository;

    public function __construct(PollRepository $pollRepository) 
    {
        $this->pollRepository = $pollRepository;
    }

    public function update(Request $request, $pollId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'question' => 'string',
                'duration' => 'integer',
                'status' => 'in:'.Status::PUBLISHED
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }

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

            if ($poll->status === Status::PUBLISHED) {
                return response()->json([
                    'message' => 'Poll cannot be edited once published',
                ], 400);
            }

            $updateData = $request->only([
                'question',
                'duration', 
                'status'
            ]); 
            if ($request->status) {
                $updateData['published_at'] = now();
            }

            $poll->update($updateData);

            return response()->json([
                'message' => 'Poll has been updated successfully',
                'poll' => $poll
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating poll',
            ], 500);
        }
    }
}
