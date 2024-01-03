<?php

namespace App\Http\Controllers\Public\News;

use App\Http\Controllers\Controller;
use App\Repositories\NewsRepository;
use Illuminate\Http\Request;

class GetLastedNewsController extends Controller
{
    protected $newRepository;

    public function __construct(NewsRepository $newRepository)
    {
        $this->newRepository = $newRepository;
    }

    public function index(Request $request)
    {
        try {
            $news = $this->newRepository->queryLasted()
                ->with('category')
                ->take(10)
                ->get();

            return response()->json([
                'news' => $news,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch news list',
            ], 500);
        }
    }
}
