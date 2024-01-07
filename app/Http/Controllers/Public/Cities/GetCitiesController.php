<?php

namespace App\Http\Controllers\Public\Cities;

use App\Http\Controllers\Controller;
use App\Repositories\CityRepository;
use App\Repositories\CountryRepository;
use Illuminate\Http\Request;

class GetCitiesController extends Controller
{
    protected $cityRepository;
    protected $countryRepository;

    public function __construct(
        CityRepository $cityRepository,
        CountryRepository $countryRepository
    ) {
        $this->cityRepository = $cityRepository;
        $this->countryRepository = $countryRepository;
    }

    public function index(Request $request)
    {
        try {
            $countryId = $request->input('country_id');
            if (!$this->countryRepository->find($countryId)) {
                return response()->json([
                    'message' => 'Country does not exist',
                ], 400);
            }

            $cities = $this->cityRepository->queryByCountryId($countryId)
                ->orderBy('name')
                ->get();

            return response()->json([
                'cities' => $cities,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch city list',
            ], 500);
        }
    }
}
