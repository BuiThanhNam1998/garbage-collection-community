<?php

namespace App\Http\Controllers\Admins\GeolocationHeatmap;

use App\Enums\GeolocationHeatmap\Type;
use App\Http\Controllers\Controller;
use App\Repositories\GarbagePostRepository;
use App\Repositories\CountryRepository;
use Illuminate\Http\Request;

class GenerateHeatmapController extends Controller
{
    protected $garbagePostRepository;
    protected $countryRepository;

    public function __construct(
        GarbagePostRepository $garbagePostRepository,
        CountryRepository $countryRepository
    ) {
        $this->garbagePostRepository = $garbagePostRepository;
        $this->countryRepository = $countryRepository;
    }

    public function index(Request $request)
    {
        try {
            $countryId = $request->country_id;
            if (!$this->countryRepository->find($countryId)) {
                return response()->json([
                    'message' => 'Country does not exist',
                ], 400);
            }

            $type = $request->type;
            if (!in_array($type, Type::ALL)) {
                return response()->json([
                    'message' => 'Type is invalid',
                ], 400);
            }

            $garbagePosts = $this->garbagePostRepository->queryByCountryId($countryId)
                ->when($type === Type::STREET, function ($q) {
                    $q->select([
                        'garbage_posts.id',
                        'garbage_posts.street_id',
                        'streets.latitude',
                        'streets.longitude',
                    ]);
                }, function ($q) {
                    $q->select([
                        'garbage_posts.id',
                        'cities.latitude',
                        'cities.longitude',
                        'cities.id as city_id'
                    ]);
                })
                ->get();

            $garbagePostGroups = $type === Type::STREET 
                ? $garbagePosts->groupBy('street_id') 
                : $garbagePosts->groupBy('city_id');
            
            
            $headMapData = $garbagePostGroups
                ->map(function($posts) {
                    return [
                        'lat' => $posts->first()->latitude,
                        'lng' => $posts->first()->longitude,
                        'weight' => $posts->count(),
                    ];
                })->values()->toArray();

            return response()->json([
                'type' => $type,
                'heatMapData' => $headMapData,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch heatmap data',
            ], 500);
        }
    }
}
