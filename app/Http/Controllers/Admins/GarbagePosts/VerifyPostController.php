<?php 

namespace App\Http\Controllers\Admins\GarbagePosts;

use App\Enums\User\GarbagePost\Status;
use App\Http\Controllers\Controller;
use App\Repositories\GarbagePostRepository;
use Illuminate\Http\Request;

class VerifyPostController extends Controller
{
    protected $garbagePostRepository;

    public function __construct(GarbagePostRepository $garbagePostRepository)
    {
        $this->garbagePostRepository = $garbagePostRepository;
    }

    public function update(Request $request, $garbagePostId)
    {
        $verificationStatus = $request->verification_status;

        try {
            $post = $this->garbagePostRepository->find($garbagePostId);

            if (!$post) {
                return response()->json([
                    'message' => 'Post does not exist',
                ], 400);
            }
            if (!in_array($verificationStatus, Status::ALL)) {
                return response()->json([
                    'message' => 'Status does not exist',
                ], 400);
            }

            $post->update([
                'verification_status' => $verificationStatus,
                'manual_verification_date' => now()
            ]);

            return response()->json([
                'message' => 'Post has been updated',
                'post' => $post,
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'message' => 'An error occurred while updating post',
            ], 500);
        }
    }
}
