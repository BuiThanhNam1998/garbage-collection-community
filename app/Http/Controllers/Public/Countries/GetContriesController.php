<?php

namespace App\Http\Controllers\Public\Countries;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class GetContriesController extends Controller
{
    public function index(Request $request)
    {
        try {
            $countries = Country::orderBy('name')
                ->get();

            return response()->json([
                'countries' => $countries,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch country list',
            ], 500);
        }
    }
}
