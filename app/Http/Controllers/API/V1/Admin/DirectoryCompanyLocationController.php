<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\DirectoryCompany\Location\StoreDirectoryCompanyLocationRequest;
use App\Http\Requests\API\V1\Admin\DirectoryCompany\Location\UpdateDirectoryCompanyLocationRequest;
use App\Models\DirectoryCompany;
use App\Models\DirectoryCompanyLocation;
use App\Services\Directory\DirectoryCompanyLocationService;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\Response;

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

        $directoryCompanyLocation = QueryBuilder::for($directoryCompanyLocation)
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
     * Store a newly created resource in storage.
     *
     * @param  StoreDirectoryCompanyLocationRequest $request
     * @param  DirectoryCompany $directoryCompany
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(StoreDirectoryCompanyLocationRequest $request, DirectoryCompany $directoryCompany)
    {
        $directoryCompanyLocation = $this->directoryCompanyLocationService->store($request, $directoryCompany);

        return ResponseBuilder::asSuccess()
            ->withMessage('Directory company location created successfully.')
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

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateDirectoryCompanyLocationRequest  $request
     * @param  \App\Models\DirectoryCompanyLocation  $directoryCompanyLocation
     * @param  \App\Models\DirectoryCompany  $directoryCompany
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(
        UpdateDirectoryCompanyLocationRequest $request,
        DirectoryCompany $directoryCompany,
        DirectoryCompanyLocation $directoryCompanyLocation
    ) {
        abort_if(
            $directoryCompany->locations()->where('id', $directoryCompanyLocation->id)->doesntExist(),
            Response::HTTP_NOT_FOUND,
            'Location not found for company'
        );

        $directoryCompanyLocation = $this->directoryCompanyLocationService
            ->update($request, $directoryCompany, $directoryCompanyLocation);

        return ResponseBuilder::asSuccess()
            ->withMessage('Directory company location updated successfully.')
            ->withData(['location' => $directoryCompanyLocation])
            ->build();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DirectoryCompany  $directoryCompany
     * @param  \App\Models\DirectoryCompanyLocation  $directoryCompanyLocation
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy(
        DirectoryCompany $directoryCompany,
        DirectoryCompanyLocation $directoryCompanyLocation
    ) {
        abort_if(
            $directoryCompany->locations()->where('id', $directoryCompanyLocation->id)->doesntExist(),
            Response::HTTP_NOT_FOUND,
            'Location not found for company'
        );
        $directoryCompanyLocation = $this->directoryCompanyLocationService->destroy($directoryCompanyLocation);

        if ($directoryCompanyLocation) {
            return ResponseBuilder::asSuccess()
            ->withMessage('Directory Company Location deleted successfully')
            ->build();
        }
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Models\DirectoryCompany  $directoryCompany
     * @param  \App\Models\DirectoryCompanyLocation  $directoryCompanyLocation
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function restore(DirectoryCompany $directoryCompany, DirectoryCompanyLocation $directoryCompanyLocation)
    {
        abort_if(
            $directoryCompany->locations()->withTrashed()->where('id', $directoryCompanyLocation->id)->doesntExist(),
            Response::HTTP_NOT_FOUND,
            'Location not found for company'
        );

        $directoryCompanyLocation = $this->directoryCompanyLocationService->restore($directoryCompanyLocation);

        if ($directoryCompanyLocation) {
            return ResponseBuilder::asSuccess()
            ->withMessage('Directory Company location restored successfully')
            ->withData(['location' => $directoryCompanyLocation])
            ->build();
        }
    }
}
