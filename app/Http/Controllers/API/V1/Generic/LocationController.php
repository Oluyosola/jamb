<?php

namespace App\Http\Controllers\API\V1\Generic;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\QueryBuilder\QueryBuilder;

class LocationController extends Controller
{
    /**
     * Display a listing of the Country.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCountries()
    {
        $countries = QueryBuilder::for(Country::class)
            ->select([
                'id',
                'name',
            ])
            ->defaultSort('name')
            ->allowedSorts([
                'name',
            ])
            ->get();

        return ResponseBuilder::asSuccess()
            ->withMessage('Countries fetched successfully.')
            ->withData([
                'countries' => $countries,
            ])
            ->build();
    }

    /**
     * Display a listing of the states.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getStates(Country $country)
    {
        $states = QueryBuilder::for(State::where('country_id', $country->id))
            ->select([
                'id',
                'name',
            ])
            ->defaultSort('name')
            ->allowedSorts([
                'name',
            ])
            ->get();

        return ResponseBuilder::asSuccess()
            ->withMessage('States fetched successfully.')
            ->withData([
                'states' => $states,
            ])
            ->build();
    }

    /**
     * Display a listing of the cities.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getCities(State $state)
    {
        $cities = QueryBuilder::for(City::where('state_id', $state->id))
            ->select([
                'id',
                'name',
            ])
            ->defaultSort('name')
            ->allowedSorts([
                'name',
            ])
            ->get();

        return ResponseBuilder::asSuccess()
            ->withMessage('Cities fetched successfully.')
            ->withData([
                'cities' => $cities,
            ])
            ->build();
    }
}
