<?php

namespace App\Http\Controllers\Users\Polls\Options;

use App\Http\Controllers\Controller;
use App\Repositories\PollRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CreateOptionController extends Controller
{
    protected $pollRepository;

    public function __construct(PollRepository $pollRepository) 
    {
        $this->pollRepository = $pollRepository;
    }

    public function store(Request $request, $pollId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'option_text' => 'required|string',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }

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

            $option = $poll->options()->create([
                'option_text' => $request->option_text
            ]);

            return response()->json([
                'message' => 'Option has sbeen created successfully',
                'option' => $option
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating option',
            ], 500);
        }
    }
}
