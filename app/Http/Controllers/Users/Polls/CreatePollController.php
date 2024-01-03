<?php

namespace App\Http\Controllers\Users\Polls;

use App\Http\Controllers\Controller;
use App\Repositories\PollRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CreatePollController extends Controller
{
    protected $pollRepository;

    public function __construct(PollRepository $pollRepository) 
    {
        $this->pollRepository = $pollRepository;
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'question' => 'required|string',
                'duration' => 'required|integer',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $userId = Auth::user()->id; 

            $poll = $this->pollRepository->create([
                'question' => $request->question,
                'duration' => $request->duration,
                'creator_id' => $userId,
            ]);

            return response()->json([
                'message' => 'Poll has sbeen created successfully',
                'poll' => $poll
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating poll',
            ], 500);
        }
    }
}
