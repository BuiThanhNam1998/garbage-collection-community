<?php

namespace App\Http\Controllers\Public\ReactionTypes;

use App\Http\Controllers\Controller;
use App\Repositories\ReactionTypeRepository;
use Illuminate\Http\Request;

class GetReactionTypesController extends Controller
{
    protected $reactionTypeRepository;

    public function __construct(ReactionTypeRepository $reactionTypeRepository)
    {
        $this->reactionTypeRepository = $reactionTypeRepository;
    }

    public function index(Request $request)
    {
        try {
            $types = $this->reactionTypeRepository->queryNotChildren()
                ->with(['children'])
                ->get();

            return response()->json([
                'types' => $types
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching reaction types',
            ], 500);
        }
    }
}
