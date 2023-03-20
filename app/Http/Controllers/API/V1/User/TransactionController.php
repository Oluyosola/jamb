<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\QueryBuilder\QueryBuilder;


class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $transactions = QueryBuilder::for($request->user()->transactions())
            ->allowedFilters([
                'payment_purpose',
                'payment_method'
            ])
            
            ->defaultSort('-created_at')
            ->paginate($request->per_page);

        return ResponseBuilder::asSuccess()
            ->withMessage('Transactions retrieved successfully.')
            ->withData(['transactions' => $transactions])
            ->build();
    }

}
