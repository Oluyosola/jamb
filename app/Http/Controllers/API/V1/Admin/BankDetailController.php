<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\BankDetail\StoreBankDetailRequest;
use App\Http\Requests\API\V1\Admin\BankDetail\UpdateBankDetailRequest;
use App\Models\BankDetail;
use App\Services\BankDetailService;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\QueryBuilder\QueryBuilder;

class BankDetailController extends Controller
{
    private BankDetailService $bankDetailService;

    /**
     * Inject the dependencies needed for the controller.
     *
     * @param  \App\Services\BankDetailService  $bankDetailService
     * @return void
     */
    public function __construct(BankDetailService $bankDetailService)
    {
        $this->bankDetailService = $bankDetailService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $bankDetails = QueryBuilder::for($this->bankDetailService->index())
            ->allowedIncludes([
                'owner',
            ])
            ->paginate($request->per_page);

        return ResponseBuilder::asSuccess()
            ->withMessage('Bank details retrieved successfully.')
            ->withData(['bankDetails' => $bankDetails])
            ->build();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBankDetailRequest $request)
    {
        $bankDetail = $this->bankDetailService->store($request);

        return ResponseBuilder::asSuccess()
            ->withHttpCode(\Illuminate\Http\Response::HTTP_CREATED)
            ->withData(['bankDetail' => $bankDetail])
            ->withMessage('Bank details created successfully.')
            ->build();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bankDetail = QueryBuilder::for($this->bankDetailService->index()->where('id', $id))
            ->allowedIncludes([
                'owner',
            ])
            ->firstOrFail();

        return ResponseBuilder::asSuccess()
            ->withData(['bankDetail' => $bankDetail])
            ->withMessage('Bank details retrieved successfully.')
            ->build();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBankDetailRequest $request, BankDetail $bankDetail)
    {
        $bankDetail = $this->bankDetailService->update($request, $bankDetail);

        return ResponseBuilder::asSuccess()
            ->withData(['bankDetail' => $bankDetail])
            ->withMessage('Bank details updated successfully.')
            ->build();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  BankDetail $bankDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(BankDetail $bankDetail)
    {
        $bankDetail = $this->bankDetailService->destroy($bankDetail);

        return ResponseBuilder::asSuccess()
            ->withMessage('Bank details deleted successfully.')
            ->build();
    }
}
