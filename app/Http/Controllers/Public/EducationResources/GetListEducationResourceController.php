<?php

namespace App\Http\Controllers\Public\EducationResources;

use App\Http\Controllers\Controller;
use App\Repositories\EducationResourceRepository;
use Illuminate\Http\Request;

class GetListEducationResourceController extends Controller
{
    protected $educationResourceRepository;

    public function __construct(EducationResourceRepository $educationResourceRepository)
    {
        $this->educationResourceRepository = $educationResourceRepository;
    }

    public function index(Request $request)
    {
        try {
            $resource = $this->educationResourceRepository->queryActive()->get();

            return response()->json([
                'resource' => $resource,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch resource list',
            ], 500);
        }
    }
}
