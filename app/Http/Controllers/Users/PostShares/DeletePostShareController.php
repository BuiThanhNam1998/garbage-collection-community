<?php

namespace App\Http\Controllers\Users\PostShares;

use App\Http\Controllers\Controller;
use App\Repositories\PostShareRepository;
use Illuminate\Http\Request;

class DeletePostShareController extends Controller
{
    protected $postShareRepository;

    public function __construct(
        PostShareRepository $postShareRepository
    ) {
        $this->postShareRepository = $postShareRepository;
    }

    public function destroy(Request $request, $postShareId)
    {
        try {
            if (!$this->postShareRepository->find($postShareId)) {
                return response()->json([
                    'message' => 'Post share does not exist',
                ], 400);
            }

            $this->postShareRepository->delete($postShareId);

            return response()->json([
                'message' => 'Post share has been deleted successfully',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while deleting post share',
            ], 500);
        }
    }
}
