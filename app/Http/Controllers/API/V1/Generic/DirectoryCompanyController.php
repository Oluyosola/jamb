<?php

namespace App\Http\Controllers\API\V1\Generic;

use App\Http\Controllers\Controller;
use App\Models\DirectoryCompany;
use App\Services\Directory\DirectoryCompanyService;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\QueryBuilder\QueryBuilder;

class DirectoryCompanyController extends Controller
{
    /**
     * @var $directoryCompanyService
     */
    public DirectoryCompanyService $directoryCompanyService;

    /**
     * Instantiate the class and inject classes it depends on.
     */
    public function __construct(DirectoryCompanyService $directoryCompanyService)
    {
        $this->directoryCompanyService = $directoryCompanyService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $directoryCompanies = $this->directoryCompanyService->index();

        $directoryCompanies = QueryBuilder::for($directoryCompanies->published())
            ->allowedIncludes([
                'creator',
                'locations',
                'category',
                'socialAccount',
                'subCategory',
            ])
            ->paginate($request->per_page);

        return ResponseBuilder::asSuccess()
            ->withMessage('Directory Companies fetched successfully.')
            ->withData(['directory_companies' => $directoryCompanies])
            ->build();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DirectoryCompany  $directoryCompany
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(DirectoryCompany $directoryCompany)
    {
        $directoryCompany = QueryBuilder::for(DirectoryCompany::where('id', $directoryCompany->id)->published())
            ->allowedIncludes([
                'creator',
                'locations',
                'socialAccount',
                'category',
                'subCategory',
            ])
            ->firstOrFail();

        return ResponseBuilder::asSuccess()
            ->withMessage('Directory company fetched successfully.')
            ->withData(['directory_company' => $directoryCompany])
            ->build();
    }
}
