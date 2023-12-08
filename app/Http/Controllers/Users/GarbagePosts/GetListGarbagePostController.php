<?php

namespace App\Http\Controllers\Users\GarbagePosts;

use App\Http\Controllers\Controller;
use App\Repositories\GarbagePostRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetListGarbagePostController extends Controller
{
    protected $garbagePostRepository;

    public function __construct(GarbagePostRepository $garbagePostRepository)
    {
        $this->garbagePostRepository = $garbagePostRepository;
    }

    public function index(Request $request)
    {
        $userId = Auth::user()->id; 

        try {
            $garbagePosts = $this->garbagePostRepository->queryByUserId($userId)
                ->with('images')
                ->get();

            return response()->json([
                'garbagePosts' => $garbagePosts,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving post list',
            ], 500);
        }
    }
}
