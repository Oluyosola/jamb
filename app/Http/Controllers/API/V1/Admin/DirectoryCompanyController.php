<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\DirectoryCompany\StoreDirectoryCompanyRequest;
use App\Http\Requests\API\V1\Admin\DirectoryCompany\UpdateDirectoryCompanyRequest;
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

        $directoryCompanies = QueryBuilder::for($directoryCompanies)
            ->allowedIncludes([
                'creator',
                'locations',
                'category',
                'subCategories',
            ])
            ->paginate($request->per_page);

        return ResponseBuilder::asSuccess()
            ->withMessage('Directory Companies fetched successfully.')
            ->withData(['directory_companies' => $directoryCompanies])
            ->build();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreDirectoryCompanyRequest  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(StoreDirectoryCompanyRequest $request)
    {
        $creator = $request->user('admin');
        $directoryCompany = $this->directoryCompanyService->store($request, $creator);

        return ResponseBuilder::asSuccess()
            ->withMessage('Directory company created successfully.')
            ->withData(['directory_company' => $directoryCompany])
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
        $directoryCompany = QueryBuilder::for(DirectoryCompany::where('id', $directoryCompany->id))
            ->allowedIncludes([
                'creator',
                'locations',
                'socialAccount',
                'category',
                'subCategories',
            ])
            ->firstOrFail();

        return ResponseBuilder::asSuccess()
            ->withMessage('Directory company fetched successfully.')
            ->withData(['directory_company' => $directoryCompany])
            ->build();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateDirectoryCompanyRequest $request
     * @param  \App\Models\DirectoryCompany  $directoryCompany
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(UpdateDirectoryCompanyRequest $request, DirectoryCompany $directoryCompany)
    {
        $creator = $request->user('admin');
        $directoryCompany = $this->directoryCompanyService->update($request, $directoryCompany, $creator);

        return ResponseBuilder::asSuccess()
            ->withMessage('Directory company updated successfully.')
            ->withData(['directory_company' => $directoryCompany])
            ->build();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DirectoryCompany  $directoryCompany
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy(DirectoryCompany $directoryCompany)
    {
        $directoryCompany = $this->directoryCompanyService->destroy($directoryCompany);

        if ($directoryCompany) {
            return ResponseBuilder::asSuccess()
            ->withMessage('Directory Company deleted successfully')
            ->build();
        }
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Models\DirectoryCompany  $directoryCompany
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function restore(DirectoryCompany $directoryCompany)
    {
        $this->directoryCompanyService->restore($directoryCompany);

        if ($directoryCompany) {
            return ResponseBuilder::asSuccess()
            ->withMessage('Directory Company restored successfully')
            ->withData(['directory_company' => $directoryCompany])
            ->build();
        }
    }
}
