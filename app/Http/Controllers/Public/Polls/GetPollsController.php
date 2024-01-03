<?php

namespace App\Http\Controllers\Public\Polls;

use App\Http\Controllers\Controller;
use App\Repositories\PollRepository;
use Illuminate\Http\Request;

class GetPollsController extends Controller
{
    protected $pollRepository;

    public function __construct(PollRepository $pollRepository)
    {
        $this->pollRepository = $pollRepository;
    }

    public function index(Request $request)
    {
        try {
            $polls = $this->pollRepository->queryPublished()
                ->with(['options'])
                ->orderBy('published_at', 'desc')
                ->get();

            return response()->json([
                'polls' => $polls
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching poll list',
            ], 500);
        }
    }
}
