<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Enums\GalleryType;
use App\Enums\MediaCollection;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Gallery\StoreGalleryRequest;
use App\Models\Gallery;
use App\Models\Media;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class ProductGalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Product $product)
    {
        $galleries = $product->gallery;

        return ResponseBuilder::asSuccess()
            ->withMessage('Product galleries fetched successfully.')
            ->withData([
                'galleries' => $galleries,
            ])
            ->build();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreGalleryRequest $request
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(StoreGalleryRequest $request, Product $product)
    {
        $totalGalleries = count($product->gallery) + count($request->gallery);
        abort_if(
            $totalGalleries > 5,
            \Symfony\Component\HttpFoundation\Response::HTTP_I_AM_A_TEAPOT,
            'You can only add up to 5 galleries to a product.'
        );

        abort_if(
            $totalGalleries < 3,
            \Symfony\Component\HttpFoundation\Response::HTTP_I_AM_A_TEAPOT,
            'A minimum of 3 galleries is required to create a product.'
        );

        DB::beginTransaction();

        $gallery = new Gallery();
        $gallery->gallerable()->associate($product);
        $gallery->type = $request->input('type', GalleryType::MEDIA);

        if ($request->type === GalleryType::MEDIA) {
            $gallery->addMediaFromRequest('gallery')->toMediaCollection(MediaCollection::GALLERY);
        }

        $gallery->save();

        DB::commit();

        return ResponseBuilder::asSuccess()
            ->withHttpCode(\Symfony\Component\HttpFoundation\Response::HTTP_CREATED)
            ->withMessage('Gallery stored successfully.')
            ->build();
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @param Gallery $gallery
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function show(Product $product, Gallery $gallery)
    {
        abort_if(
            $product->gallery()->where('id', $gallery->id)->doesntExist(),
            \Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND,
            'Gallery not found for product'
        );

        return ResponseBuilder::asSuccess()
            ->withMessage('Gallery fetched successfully.')
            ->withData([
                'gallery' => $gallery,
            ])
            ->build();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @param Gallery $gallery
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function destroy(Product $product, Gallery $gallery)
    {
        abort_if(
            $product->gallery()->where('id', $gallery->id)->doesntExist(),
            \Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND,
            'Gallery not found for product'
        );

        $gallery->forceDelete();

        return ResponseBuilder::asSuccess()
            ->withHttpCode(\Symfony\Component\HttpFoundation\Response::HTTP_OK)
            ->withMessage('Gallery deleted successfully.')
            ->build();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @param Gallery $gallery
     * @param Media $media
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function destroyMedia(Product $product, Gallery $gallery, Media $media)
    {
        abort_if(
            $product->gallery()->where('id', $gallery->id)->doesntExist(),
            \Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND,
            'Gallery not found for product'
        );

        $media->forceDelete();

        return ResponseBuilder::asSuccess()
            ->withHttpCode(\Symfony\Component\HttpFoundation\Response::HTTP_OK)
            ->withMessage('Media deleted successfully.')
            ->build();
    }
}
