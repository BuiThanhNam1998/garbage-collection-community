<?php 

namespace App\Http\Controllers\Admins\ModerationQueue;

use App\Enums\ModerationQueue\Status;
use App\Http\Controllers\Controller;
use App\Repositories\ModerationQueueRepository;
use Illuminate\Http\Request;

class UpdateModerationQueueController extends Controller
{
    protected $moderationQueueRepository;

    public function __construct(ModerationQueueRepository $moderationQueueRepository) 
    {
        $this->moderationQueueRepository = $moderationQueueRepository;
    }

    public function update(Request $request, $moderationId)
    {
        try {
            $status = $request->status;
            $moderationObject = $this->moderationQueueRepository->find($moderationId);
            if (!$moderationObject) {
                return response()->json([
                    'message' => 'Moderation object does not exist',
                ], 400);
            }

            if (!in_array($status, Status::ALL)) {
                return response()->json([
                    'message' => 'Status does not exist',
                ], 400);
            }

            $moderationObject->status = $status;
            $moderationObject->save();

            return response()->json([
                'message' => 'Update moderation object successfully',
                'moderation' => $moderationObject,
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'message' => 'An error occurred while update moderation object',
            ], 500);
        }
    }
}
