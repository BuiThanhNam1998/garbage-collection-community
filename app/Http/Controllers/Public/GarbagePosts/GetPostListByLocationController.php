<?php

namespace App\Http\Controllers\Public\GarbagePosts;

use App\Http\Controllers\Controller;
use App\Enums\User\GarbagePost\Location\Type;
use App\Repositories\CityRepository;
use App\Repositories\CountryRepository;
use App\Repositories\GarbagePostRepository;
use Illuminate\Http\Request;

class GetPostListByLocationController extends Controller
{
    protected $cityRepository;
    protected $countryRepository;
    protected $garbagePostRepository;

    public function __construct(
        CityRepository $cityRepository,
        CountryRepository $countryRepository,
        GarbagePostRepository $garbagePostRepository,
    ) {
        $this->cityRepository = $cityRepository;
        $this->countryRepository = $countryRepository;
        $this->garbagePostRepository = $garbagePostRepository;
    }

    public function index(Request $request, $locationType, $locationId)
    {
        try {
            if (!in_array($locationType, Type::ALL)) {
                return response()->json([
                    'message' => 'The location type does not exist',
                ], 400);
            }

            $post = null; 
            if ($locationType === Type::COUNTRY) {
                $country = $this->countryRepository->find($locationId);
                if (!$country) {
                    return response()->json([
                        'message' => 'The country does not exist',
                    ], 400);
                }
                $cities = $country->cities();
                $post = $this->garbagePostRepository
                    ->queryByCountry($country->id, $cities->pluck('id'))
                    ->with('images')
                    ->get();
            }


            if ($locationType === Type::CITY) {
                $city = $this->cityRepository->find($locationId);
                if (!$city) {
                    return response()->json([
                        'message' => 'The city does not exist',
                    ], 400);
                }
                $post = $this->garbagePostRepository
                    ->queryByLocation(Type::CITY, $city->id)
                    ->with('images')
                    ->get();
            }

            return response()->json([
                'garbagePosts' => $post,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch garbage posts',
            ], 500);
        }
    }
}
