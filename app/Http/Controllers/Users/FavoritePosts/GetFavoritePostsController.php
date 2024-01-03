<?php

namespace App\Http\Controllers\Users\FavoritePosts;

use App\Http\Controllers\Controller;
use App\Repositories\UserFavoritePostRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetFavoritePostsController extends Controller
{
    protected $userFavoritePostRepository;

    public function __construct(
        UserFavoritePostRepository $userFavoritePostRepository,
    ) {
        $this->userFavoritePostRepository = $userFavoritePostRepository;
    }

    public function index(Request $request)
    {
        $userId = Auth::user()->id; 

        try {
            $favoritePosts = $this->userFavoritePostRepository
                ->queryByUserId($userId)
                ->with(['post'])
                ->get();

            return response()->json([
                'favoritePosts' => $favoritePosts
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching the favorites list',
            ], 500);
        }
    }
}
