<?php

namespace App\Http\Controllers\Users\GarbagePosts;

use App\Http\Controllers\Controller;
use App\Repositories\GarbagePostRepository;
use App\Repositories\GarbagePostImageRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeleteGarbagePostController extends Controller
{
    protected $garbagePostRepository;
    protected $garbagePostImageRepository;

    public function __construct(
        GarbagePostRepository $garbagePostRepository,
        GarbagePostImageRepository $garbagePostImageRepository
    ) {
        $this->garbagePostRepository = $garbagePostRepository;
        $this->garbagePostImageRepository = $garbagePostImageRepository;
    }

    public function delete($garbagePostId)
    {
        $user = Auth::user(); 

        try {
            DB::beginTransaction();
            $garbagePost = $this->garbagePostRepository->findOrFail($garbagePostId);

            if ($garbagePost->user_id !== $user->id) {
                return response()->json([
                    'message' => 'You do not have permission to delete this post',
                ], 403);
            }

            $this->garbagePostImageRepository->deleteByCondition([
                ['garbage_post_id', '=', $garbagePostId],
            ]);

            $this->garbagePostRepository->delete($garbagePostId);
            DB::commit();

            return response()->json([
                'message' => 'Post has been successfully deleted',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'An error occurred while deleting post',
            ], 500);
        }
    }
}
