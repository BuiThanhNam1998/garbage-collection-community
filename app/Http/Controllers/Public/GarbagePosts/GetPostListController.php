<?php

namespace App\Http\Controllers\Public\GarbagePosts;

use App\Http\Controllers\Controller;
use App\Repositories\GarbagePostRepository;
use Illuminate\Http\Request;

class GetPostListController extends Controller
{
    protected $garbagePostRepository;

    public function __construct(GarbagePostRepository $garbagePostRepository)
    {
        $this->garbagePostRepository = $garbagePostRepository;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $keyword = $request->input('keyword');

            $garbagePosts = $this->garbagePostRepository->queryApprovePost()
                ->when($keyword, function($q) use ($keyword) {
                    $q->where('description', 'like', '%'.$keyword.'%');
                })
                ->with(['images', 'user', 'comments'])
                ->withCount(['comments', 'positiveReactions', 'negativeReactions'])
                ->paginate($perPage);

            return response()->json([
                'garbagePosts' => $garbagePosts,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch garbage posts',
            ], 500);
        }
    }
}
