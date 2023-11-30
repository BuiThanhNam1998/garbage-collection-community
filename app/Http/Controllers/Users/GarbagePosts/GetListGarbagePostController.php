<?php

namespace App\Http\Controllers\Users\GarbagePosts;

use App\Http\Controllers\Controller;
use App\Models\GarbagePost;
use App\Repositories\GarbagePostRepository;
use Illuminate\Http\Request;

class GetListGarbagePostController extends Controller
{
    protected $garbagePostRepository;

    public function __construct(GarbagePostRepository $garbagePostRepository)
    {
        $this->garbagePostRepository = $garbagePostRepository;
    }

    public function getList(Request $request, $userId)
    {
        $garbagePosts = $this->garbagePostRepository->queryByCondition(['user_id' => $userId])
            ->with('images')
            ->get();

        return response()->json([
            'garbagePosts' => $garbagePosts,
        ], 200);
    }
}
