<?php

namespace App\Http\Controllers\Users\FavoritePosts;

use App\Http\Controllers\Controller;
use App\Repositories\GarbagePostRepository;
use App\Repositories\UserFavoritePostRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddToFavoritePostController extends Controller
{
    protected $userFavoritePostRepository;
    protected $garbagePostRepository;

    public function __construct(
        UserFavoritePostRepository $userFavoritePostRepository,
        GarbagePostRepository $garbagePostRepository,
    ) {
        $this->userFavoritePostRepository = $userFavoritePostRepository;
        $this->garbagePostRepository = $garbagePostRepository;
    }

    public function store(Request $request)
    {
        $userId = Auth::user()->id; 

        try {
            $postId = $request->input('garbage_post_id');

            if (!$this->garbagePostRepository->find($postId)) {
                return response()->json([
                    'message' => 'Post does not exist',
                ], 400);
            }

            $favoritePostExist = $this->userFavoritePostRepository->queryByCondition([
                'user_id' => $userId,
                'garbage_post_id' => $postId
            ])->first();

            if ($favoritePostExist) {
                return response()->json([
                    'message' => 'Post already exists in the favorites list',
                ], 400);
            }

            $this->userFavoritePostRepository->create([
                'user_id' => $userId,
                'garbage_post_id' => $postId
            ]);

            return response()->json([
                'message' => 'Post was successfully added to the favorites list',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while adding a post to your favorites list',
            ], 500);
        }
    }
}
