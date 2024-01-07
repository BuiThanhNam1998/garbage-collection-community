<?php

namespace App\Http\Controllers\Public\Streets;

use App\Http\Controllers\Controller;
use App\Repositories\CityRepository;
use App\Repositories\StreetRepository;
use Illuminate\Http\Request;

class GetStreetsController extends Controller
{
    protected $cityRepository;
    protected $streetRepository;

    public function __construct(
        CityRepository $cityRepository,
        StreetRepository $streetRepository
    ) {
        $this->cityRepository = $cityRepository;
        $this->streetRepository = $streetRepository;
    }

    public function index(Request $request)
    {
        try {
            $cityId = $request->input('city_id');
            if (!$this->cityRepository->find($cityId)) {
                return response()->json([
                    'message' => 'City does not exist',
                ], 400);
            }

            $streets = $this->streetRepository->queryByCityId($cityId)
                ->orderBy('name')
                ->get();

            return response()->json([
                'streets' => $streets,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch street list',
            ], 500);
        }
    }
}
