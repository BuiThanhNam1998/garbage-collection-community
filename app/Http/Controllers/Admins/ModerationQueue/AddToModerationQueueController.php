<?php 

namespace App\Http\Controllers\Admins\ModerationQueue;

use App\Enums\ModerationQueue\Status;
use App\Enums\ModerationQueue\Type;
use App\Http\Controllers\Controller;
use App\Repositories\ModerationQueueRepository;
use App\Repositories\GarbagePostRepository;
use App\Repositories\PostCommentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddToModerationQueueController extends Controller
{
    protected $moderationQueueRepository;
    protected $garbagePostRepository;
    protected $postCommentRepository;

    public function __construct(
        ModerationQueueRepository $moderationQueueRepository,
        GarbagePostRepository $garbagePostRepository,
        PostCommentRepository $postCommentRepository
    ) {
        $this->moderationQueueRepository = $moderationQueueRepository;
        $this->garbagePostRepository = $garbagePostRepository;
        $this->postCommentRepository = $postCommentRepository;
    }

    public function store(Request $request)
    {
        try {
            $adminId = Auth::guard('admin')->user()->id;
            $objectType = $request->object_type;
            $objectId = $request->object_id;

            if (!in_array($objectType, Type::ALL)) {
                return response()->json([
                    'message' => 'Type does not exist',
                ], 400);
            }

            if ($objectType === Type::POST) {
                $target = $this->garbagePostRepository->find($objectId);
                if (!$target) {
                    return response()->json([
                        'message' => 'Post does not exist',
                    ], 400);
                }
            } else if ($objectType === Type::COMMENT) {
                $target = $this->postCommentRepository->find($objectId);
                if (!$target) {
                    return response()->json([
                        'message' => 'Comment does not exist',
                    ], 400);
                }
            }

            $data = [
                'object_id' => $objectId,
                'object_type' => $objectType,
                'status' => Status::PENDING,
                'admin_id' => $adminId,
            ];

            $moderation = $this->moderationQueueRepository->create($data);

            return response()->json([
                'message' => 'Added to queue successfully',
                'moderation' => $moderation,
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'message' => 'An error occurred while adding the object to the queue',
            ], 500);
        }
    }
}
