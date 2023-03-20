<?php

namespace App\Http\Controllers\API\V1\Generic;

use App\Http\Controllers\Controller;
use App\Models\Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use \Torann\GeoIP\Facades\GeoIP;
class SearchController extends Controller
{
    /**
     * Perform a search
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request)
    {
       
        $ip = request()->ip();
        $data= geoip()->getLocation($ip);
        $longitude = $data['lon'];
        $latitude = $data['lat'];

        $keyword = $query = $request->get('keyword');
        $cityLocation = $request->get('city_id');
        $stateLocation = $request->get('state_id');
        $artisanCategory = $request->get('category_id');
        $include = $request->get('include');
        $currentLongitude = $longitude;
        $currentLatitutde = $latitude;
        $result = Artisan::search($keyword)
        ->query(function ($query) use ($currentLatitutde, $currentLongitude) {
            $query->addSelect([
                'id',
                'state_id',
                'city_id',
                'address',
                DB::raw('6371 * acos(cos(radians(' . $currentLatitutde . ')) 
                    * cos(radians(`latitude`)) 
                    * cos(radians(`longitude`)
                    - radians(' . $currentLongitude . ')) 
                    + sin(radians(' . $currentLatitutde . ')) 
                    * sin(radians(`latitude`))) AS distance')
            ])
            ->orderBy('distance');
        })
            ->when($cityLocation, function ($query) use ($cityLocation) {
                return $query->where('city_id', $cityLocation);
            })
            ->when($stateLocation, function ($query) use ($stateLocation) {
                return $query->where('state_id', $stateLocation);
            })
            ->when($artisanCategory, function ($query) use ($artisanCategory) {
                return $query->where('category_id', $artisanCategory);
            })
            ->paginate();

        $result = tap($result, function ($result) use ($include) {
            if ((bool) $include) {
                $result = $this->loadRelations($result, $include);
            }

            return $result;
        });

        return ResponseBuilder::asSuccess()
            ->withData(['search_results' => $result])
            ->withMessage('Search results')
            ->build();
    }

    /**
     * Load relations to search.
     *
     * @param mixed $result
     * @param string $include
     * @return mixed
     */
    private function loadRelations($result, string $include)
    {
        $result = str_contains($include, 'city')
            ? $result->load('city')
            : $result;

        $result = str_contains($include, 'state')
            ? $result->load('state')
            : $result;

        return $result;
    }
}
