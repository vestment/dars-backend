<?php

namespace App\Http\Controllers\Backend\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Country;


class CountriesController extends Controller
{
    public function getCountries()
    {
        $countries = Country::with('eduSystems')->get();
        return view('backend.courses.create', compact('countries'));
    }

}
