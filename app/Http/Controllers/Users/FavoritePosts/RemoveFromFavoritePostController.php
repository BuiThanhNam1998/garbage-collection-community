<?php

namespace App\Http\Controllers\Users\FavoritePosts;

use App\Http\Controllers\Controller;
use App\Repositories\GarbagePostRepository;
use App\Repositories\UserFavoritePostRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RemoveFromFavoritePostController extends Controller
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

    public function destroy(Request $request)
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

            if (!$favoritePostExist) {
                return response()->json([
                    'message' => 'Post does not exist in the favorites list',
                ], 400);
            }

            $this->userFavoritePostRepository->queryByCondition([
                'user_id' => $userId,
                'garbage_post_id' => $postId
            ])->delete();

            return response()->json([
                'message' => 'Post has been removed from favorites list',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while removing the post from the favorites list',
            ], 500);
        }
    }
}
