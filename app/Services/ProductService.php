<?php

namespace App\Services;

use App\Enums\MediaCollection;
use App\Http\Requests\API\V1\User\Product\SearchProductRequest;
use illuminate\Http\Request;
use App\Models\Product;

class ProductService
{
    /**
     * Fetches all products.
     */
    public function index()
    {
        return Product::query();
    }

    
    /**
     * Fetches a product by id.
     *
     * @param int $id
     * @return Product
     */
    public function show(int $id)
    {
        return Product::findOrFail($id);
    }

    /**
     * Creates a new product.
     *
     * @param array $data
     * @return Product
     */
    public function store($request)
    {
        $product = new Product();
        $product->artisan()->associate($request->artisan_id);
        $product->category()->associate($request->category_id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->amount = $request->amount;
        $product->is_active = $request->is_active;
        $product->is_verified = $request->is_verified;
        $product->start_date = $request->start_date;
        $product->finish_date = $request->finish_date;
        $product->save();

        if ($request->hasFile('featured_image')) {
            $product->addMediaFromRequest('featured_image')
                ->toMediaCollection(MediaCollection::FEATUREDIMAGE);
        }

        return $product;
    }

    /**
     * Updates a product.
     *
     * @param $request
     * @param Product $product
     * @return Product $product
     */
    public function update($request, Product $product)
    {
        $product->artisan()->associate($request->artisan_id);
        $product->category()->associate($request->category_id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->amount = $request->amount;
        $product->is_active = $request->is_active;
        $product->is_verified = $request->is_verified;
        $product->start_date = $request->start_date;
        $product->finish_date = $request->finish_date;
        $product->save();

        if ($request->hasFile('featured_image')) {
            $product->addMediaFromRequest('featured_image')
                ->toMediaCollection(MediaCollection::FEATUREDIMAGE);
        }

        return $product;
    }

    /**
     * Deletes a product.
     *
     * @param Product $product
     * @return bool
     */
    public function destroy(Product $product)
    {
        return $product->delete();
    }
}
