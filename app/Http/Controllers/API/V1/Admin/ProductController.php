<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Product\StoreProductRequest;
use App\Http\Requests\API\V1\Admin\Product\UpdateProductRequest;
use App\Models\Product;
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
            ->defaultSort('-updated_at')
            ->paginate($request->per_page);

        return ResponseBuilder::asSuccess()
            ->withData(['products' => $products])
            ->withMessage('Products fetched successfully.')
            ->build();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreProductRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(StoreProductRequest $request)
    {
        $product = $this->productService->store($request);

        return ResponseBuilder::asSuccess()
            ->withHttpCode(\Illuminate\Http\Response::HTTP_CREATED)
            ->withData(['product' => $product])
            ->withMessage('Product created successfully.')
            ->build();
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
     * Update the specified resource in storage.
     *
     * @param  UpdateProductRequest $request
     * @param  Product $product
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product = $this->productService->update($request, $product);

        return ResponseBuilder::asSuccess()
            ->withHttpCode(\Illuminate\Http\Response::HTTP_OK)
            ->withData(['product' => $product])
            ->withMessage('Product updated successfully.')
            ->build();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Product $product
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return ResponseBuilder::asSuccess()
            ->withMessage('Product deleted successfully.')
            ->build();
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  Product $product
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function restore(Product $product)
    {
        $product->restore();

        return ResponseBuilder::asSuccess()
            ->withMessage('Product restored successfully.')
            ->build();
    }

    /**
     * Permanently delete the specified resource from storage.
     *
     * @param  Product $product
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function forceDelete(Product $product)
    {
        $product->forceDelete();

        return ResponseBuilder::asSuccess()
            ->withMessage('Product permanently deleted successfully.')
            ->build();
    }

    /**
     * Toggle the active status of the specified resource.
     *
     * @param  Product $product
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toggleActive(Product $product)
    {
        $product->is_active = !$product->is_active;
        $product->save();

        $message = $product->is_active ? '' : 'de';

        return ResponseBuilder::asSuccess()
            ->withMessage("Product {$message}activated successfully.")
            ->build();
    }

    /**
     * Toggle the verified status of the specified resource.
     *
     * @param  Product $product
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toggleVerified(Product $product)
    {
        $product->is_verified = !$product->is_verified;
        $product->save();

        $message = $product->is_verified ? '' : 'un';

        return ResponseBuilder::asSuccess()
            ->withMessage("Product {$message}verified successfully.")
            ->build();
    }
}
