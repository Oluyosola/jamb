<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Artisan;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\QueryBuilder\QueryBuilder;

class DashboardController extends Controller
{
    /**
     * Display Dashboard Statistics.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function totalStats()
    {
        $totalAdmin = QueryBuilder::for(Admin::class)->count();
        $totalArtisan = QueryBuilder::for(Artisan::class)->count();
        $totalOrder = QueryBuilder::for(Order::class)->count();
        $totalProduct = QueryBuilder::for(Product::class)->count();

        return ResponseBuilder::asSuccess()
        ->withData([
            'total_admin' => $totalAdmin,
            'total_artisan' => $totalArtisan,
            'total_product' => $totalProduct,
            'total_order' => $totalOrder      
        ])

        ->withMessage('Dashboard Statisitics fetched successfully.')
        ->build();
    }
    
}
