<?php 

namespace App\Http\Controllers\Admins\ModerationQueue;

use App\Http\Controllers\Controller;
use App\Repositories\ModerationQueueRepository;
use Illuminate\Http\Request;

class RemoveFromModerationQueueController extends Controller
{
    protected $moderationQueueRepository;

    public function __construct(ModerationQueueRepository $moderationQueueRepository) 
    {
        $this->moderationQueueRepository = $moderationQueueRepository;
    }

    public function destroy(Request $request, $moderationId)
    {
        try {
            if (!$this->moderationQueueRepository->find($moderationId)) {
                return response()->json([
                    'message' => 'The object does not exist in the queue',
                ], 400);
            }

            $this->moderationQueueRepository->delete($moderationId);

            return response()->json([
                'message' => 'Delete moderation object successfully',
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'message' => 'An error occurred while delete moderation object',
            ], 500);
        }
    }
}
