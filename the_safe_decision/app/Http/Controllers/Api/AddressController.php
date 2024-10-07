<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\City;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{

    public function getCountries()
    {
        $countries = Country::orderBy('name_en')->get();
        return response()->json([
            'data' => $countries
        ], 200, [], JSON_UNESCAPED_UNICODE);

    }

    public function getCities()
    {
        $cities = City::orderBy('name_en')->get();
        return response()->json([
            'data' => $cities
        ], 200, [], JSON_UNESCAPED_UNICODE);

    }


    public function getCitiesByCountry($countryId)
    {
       $cities = City::where('country_id', $countryId)
                          ->orderBy('name_en')
                          ->get();

        return response()->json(['data' => $cities], 200, [], JSON_UNESCAPED_UNICODE);

    }
}