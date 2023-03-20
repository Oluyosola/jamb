<?php

namespace App\Http\Controllers\API\V1\User\Advert;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\User\Advert\StoreAdvertRequest;
use App\Http\Requests\API\V1\User\Advert\UpdateAdvertRequest;
use App\Models\Advert;
use App\Services\AdvertService;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\Response;

class AdvertController extends Controller
{
    public $advertService;

    /**
     * Instanstiate the class.
     *
     * @param AdvertService $advertService
     */
    public function __construct(AdvertService $advertService)
    {
        $this->advertService = $advertService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $myAdverts = $this->advertService->index($request);

        $myAdverts = QueryBuilder::for($myAdverts)
            ->allowedIncludes([
                'advertPlan'
            ])
            ->paginate($request->per_page);

        return ResponseBuilder::asSuccess()
            ->withData(['adverts' => $myAdverts])
            ->withMessage('Adverts fetched successfully')
            ->build();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreAdvertRequest  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(StoreAdvertRequest $request)
    {
        $advertService = new $this->advertService();
        $advert = $advertService->store($request);

        return ResponseBuilder::asSuccess()
            ->withData(['advert' => $advert])
            ->withMessage('Advert created successfully')
            ->build();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Advert  $advert
     * @return \Illuminate\Http\Response
     */
    public function show(Advert $advert)
    {
        $advert = $this->advertService->show($advert);
        $advert = QueryBuilder::for($advert)
            ->allowedIncludes([
                'advertPlan'
            ])
            ->first();

        return ResponseBuilder::asSuccess()
            ->withData(['advert' => $advert])
            ->withMessage('Advert fetched successfully')
            ->build();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateAdvertRequest  $request
     * @param  \App\Models\Advert  $advert
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAdvertRequest $request, Advert $advert)
    {
        abort_if(
            $advert->transaction->payment_status,
            Response::HTTP_UNPROCESSABLE_ENTITY,
            'Paid adverts can not be edited.'
        );

        $advert = $this->advertService->update($request, $advert);

        return ResponseBuilder::asSuccess()
            ->withData(['data' => $advert])
            ->withMessage('Advert updated successfully.')
            ->build();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Advert  $advert
     * @return \Illuminate\Http\Response
     */
    public function destroy(Advert $advert)
    {
        abort_if(
            $advert->transaction->payment_status,
            Response::HTTP_UNPROCESSABLE_ENTITY,
            'Paid adverts can not be deleted.'
        );

        $this->advertService->destroy($advert);

        return ResponseBuilder::asSuccess()
            ->withMessage('Advert deleted successfully.')
            ->build();
    }
}
