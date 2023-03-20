<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\QueryBuilder\QueryBuilder;


class ProductController extends Controller
{

     /**
     * @var ProductService
     */
    private $productService;

    /**
     * Inject controller dependencies.
     *
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function index(Request $request)
    {
        $products = QueryBuilder::for($this->productService->index())
            ->allowedFilters([
                'artisan_id',
                'category_id',
                'name',
                'amount',
                'is_active',
                'is_verified'
            ])
            ->allowedIncludes([
                'artisan',
                'category',
            ])
            ->defaultSort('-updated_at')->paginate($request->per_page);

        return ResponseBuilder::asSuccess()
            ->withData(['products' => $products])
            ->withMessage('Products fetched successfully.')
            ->build();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $productId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show($productId)
    {
        $product = QueryBuilder::for($this->productService->index()->where('id', $productId))
            ->allowedIncludes([
                'artisan',
                'category',
            ])
            ->firstOrFail();
        return ResponseBuilder::asSuccess()
            ->withData(['product' => $product])
            ->withMessage('Product fetched successfully.')
            ->build();
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}