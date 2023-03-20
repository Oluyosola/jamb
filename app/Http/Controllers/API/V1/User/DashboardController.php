<?php

namespace App\Http\Controllers\API\V1\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\QueryBuilder\QueryBuilder;

class DashboardController extends Controller
{    
    /**
     * Display user's wallet balance.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function walletBalance(Request $request)
    {

        return ResponseBuilder::asSuccess()
            ->withMessage('Wallet Balance fetched successfully.')
            ->withData(['Wallet Balance' => $request->user()->wallet_balance])
            ->build();
    }

    
}
