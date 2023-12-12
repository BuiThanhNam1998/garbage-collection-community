<?php

namespace App\Http\Controllers\Users\Polls;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetPollsController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = Auth::user(); 
            $polls = $user->polls->load(['options']);

            return response()->json([
                'postShares' => $polls
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching poll list',
            ], 500);
        }
    }
}
