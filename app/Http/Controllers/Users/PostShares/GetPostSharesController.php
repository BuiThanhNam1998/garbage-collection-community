<?php

namespace App\Http\Controllers\Users\PostShares;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetPostSharesController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = Auth::user(); 
            $postShares = $user->sharedPosts;

            return response()->json([
                'postShares' => $postShares
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching the post shares',
            ], 500);
        }
    }
}
