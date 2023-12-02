<?php

namespace App\Http\Controllers\Public\Statistics;

use App\Http\Controllers\Controller;
use App\Repositories\CityRepository;
use App\Repositories\CountryRepository;
use App\Repositories\GarbagePostRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class GetStatisticsController extends Controller
{
    protected $cityRepository;
    protected $countryRepository;
    protected $garbagePostRepository;
    protected $userRepository;

    public function __construct(
        CityRepository $cityRepository,
        CountryRepository $countryRepository,
        GarbagePostRepository $garbagePostRepository,
        UserRepository $userRepository
    ) {
        $this->cityRepository = $cityRepository;
        $this->countryRepository = $countryRepository;
        $this->garbagePostRepository = $garbagePostRepository;
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        try {
            $postCount = $this->garbagePostRepository->queryApprovePost()->count();
            $userCount = $this->userRepository->count();
            $countryCount = $this->countryRepository->count();
            $cityCount = $this->cityRepository->count();

            $lastUser = $this->userRepository->last();

            return response()->json([
                'userCount' => $userCount,
                'postCount' => $postCount,
                'countryCount' => $countryCount,
                'cityCount' => $cityCount,
                'lastUser' => $lastUser,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to get chart data',
            ], 500);
        }
    }
}
