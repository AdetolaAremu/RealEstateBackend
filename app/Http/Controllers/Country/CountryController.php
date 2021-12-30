<?php

namespace App\Http\Controllers\Country;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CountryController extends Controller
{
    public function city()
    {
        $countries = City::get(['id', 'name']);
    
        return response($countries, Response::HTTP_ACCEPTED);
    }
}
