<?php 

namespace App\Http\Controllers\Admins\AiPostQueue;

use App\Http\Controllers\Controller;
use App\Repositories\AiPostQueueRepository;
use App\Repositories\GarbagePostRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddToAiPostQueueController extends Controller
{
    protected $aiPostQueueRepository;
    protected $garbagePostRepository;

    public function __construct(
        AiPostQueueRepository $aiPostQueueRepository,
        GarbagePostRepository $garbagePostRepository,
    ) {
        $this->aiPostQueueRepository = $aiPostQueueRepository;
        $this->garbagePostRepository = $garbagePostRepository;
    }

    public function store(Request $request)
    {
        try {
            $adminId = Auth::guard('admin')->user()->id;
            $postId = $request->input('garbage_post_id');

            $post = $this->garbagePostRepository->find($postId);

            if (!$post) {
                return response()->json([
                    'message' => 'Post does not exist',
                ], 400);
            }

            $postExistInQueue = $this->aiPostQueueRepository->queryByCondition([
                'garbage_post_id' => $postId
            ])->first();

            if ($postExistInQueue) {
                return response()->json([
                    'message' => 'Post already exists in the queue',
                ], 400);
            }

            $data = [
                'garbage_post_id' => $postId,
                'admin_id' => $adminId,
            ];

            $this->aiPostQueueRepository->create($data);

            return response()->json([
                'message' => 'Post was successfully added to the queue',
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'message' => 'An error occurred while adding post to the queue',
            ], 500);
        }
    }
}
