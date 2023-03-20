<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
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
        $transactions = QueryBuilder::for(Transaction::class)
            ->allowedFilters([
                'payment_purpose',
                'payment_method',
                'user_id'
            ])
            ->allowedIncludes([
                'user'
            ])
            ->defaultSort('-created_at')
            ->paginate($request->per_page);

        return ResponseBuilder::asSuccess()
            ->withMessage('Transactions retrieved successfully.')
            ->withData(['transactions' => $transactions])
            ->build();
    }
    
      
}
