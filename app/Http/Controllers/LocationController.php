<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\District;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getRegions()
    {
        return Region::all();
    }

    public function getDistricts($regionId)
    {
        return District::where('region_id', $regionId)->get();
    }
}