<?php 

namespace App\Http\Controllers\Admins\GarbagePosts;

use App\Enums\User\GarbagePost\Status;
use App\Http\Controllers\Controller;
use App\Repositories\GarbagePostRepository;
use Illuminate\Http\Request;

class VerifyBulkPostController extends Controller
{
    protected $garbagePostRepository;

    public function __construct(GarbagePostRepository $garbagePostRepository)
    {
        $this->garbagePostRepository = $garbagePostRepository;
    }

    public function update(Request $request)
    {
        try {
            $verificationStatus = $request->verification_status;
            $garbagePostIds = $request->garbage_post_ids;
            $posts = $this->garbagePostRepository->queryByIds($garbagePostIds)->get();

            if ($posts->count() !== count($garbagePostIds)) {
                return response()->json([
                    'message' => 'Invalid data',
                ], 400);
            }
            if (!in_array($verificationStatus, Status::ALL)) {
                return response()->json([
                    'message' => 'Status does not exist',
                ], 400);
            }

            $this->garbagePostRepository->updateByIds($garbagePostIds, [
                'verification_status' => $verificationStatus,
                'manual_verification_date' => now()
            ]);

            return response()->json([
                'message' => 'Post list has been updated',
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'message' => 'An error occurred while updating post list',
            ], 500);
        }
    }
}
