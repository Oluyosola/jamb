<?php

namespace App\Http\Controllers\API\V1\Generic;

use App\Http\Controllers\Controller;
use App\Models\DirectoryCompany;
use App\Models\DirectoryCompanyLocation;
use App\Services\Directory\DirectoryCompanyLocationService;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\QueryBuilder\QueryBuilder;

class DirectoryCompanyLocationController extends Controller
{
    /**
     * @var $directoryCompanyLocationService
     */
    public DirectoryCompanyLocationService $directoryCompanyLocationService;

    /**
     * Instantiate the class and inject classes it depends on.
     */
    public function __construct(DirectoryCompanyLocationService $directoryCompanyLocationService)
    {
        $this->directoryCompanyLocationService = $directoryCompanyLocationService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param DirectoryCompany $directoryCompany
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(DirectoryCompany $directoryCompany, Request $request)
    {
        $directoryCompanyLocation = $this->directoryCompanyLocationService->index($directoryCompany);

        $directoryCompanyLocation = QueryBuilder::for($directoryCompanyLocation->active())
            ->allowedIncludes([
                'directoryCompany',
            ])
            ->paginate($request->per_page);

        return ResponseBuilder::asSuccess()
            ->withMessage('Directory Company Location fetched successfully.')
            ->withData(['location' => $directoryCompanyLocation])
            ->build();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DirectoryCompanyLocation  $directoryCompanyLocation
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(DirectoryCompany $directoryCompany, DirectoryCompanyLocation $directoryCompanyLocation)
    {
        $directoryCompanyLocation = QueryBuilder::for(
            DirectoryCompanyLocation::where('id', $directoryCompanyLocation->id)
        )
            ->allowedIncludes([
                'directoryCompany'
            ])
            ->firstOrFail();

        return ResponseBuilder::asSuccess()
            ->withMessage('Directory company location fetched successfully.')
            ->withData(['location' => $directoryCompanyLocation])
            ->build();
    }
}
