<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\DirectoryCompany\Social\StoreDirectoryCompanySocialRequest;
use App\Http\Requests\API\V1\Admin\DirectoryCompany\Social\UpdateDirectoryCompanySocialRequest;
use App\Models\DirectoryCompany;
use App\Models\DirectoryCompanySocial;
use App\Services\Directory\DirectoryCompanySocialService;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\Response;

class DirectoryCompanySocialController extends Controller
{
    /**
     * @var $directoryCompanySocialService
     */
    public DirectoryCompanySocialService $directoryCompanySocialService;

    /**
     * Instantiate the class and inject classes it depends on.
     */
    public function __construct(DirectoryCompanySocialService $directoryCompanySocialService)
    {
        $this->directoryCompanySocialService = $directoryCompanySocialService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param DirectoryCompany $directoryCompany
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, DirectoryCompany $directoryCompany)
    {
        $directoryCompanySocial = $this->directoryCompanySocialService->index($directoryCompany);

        $directoryCompanySocial = QueryBuilder::for($directoryCompanySocial)
            ->allowedIncludes([
                'directoryCompany',
            ])
            ->paginate($request->per_page);

        return ResponseBuilder::asSuccess()
            ->withMessage('Directory Company Social fetched successfully.')
            ->withData(['social' => $directoryCompanySocial])
            ->build();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreDirectoryCompanySocialRequest  $request
     * @param  DirectoryCompany  $directoryCompany
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(StoreDirectoryCompanySocialRequest $request, DirectoryCompany $directoryCompany)
    {
        abort_if(
            $directoryCompany->socialAccount(),
            Response::HTTP_UNAUTHORIZED,
            'Social details has been created previously.'
        );
        $directoryCompanySocial = $this->directoryCompanySocialService->store($request, $directoryCompany);

        return ResponseBuilder::asSuccess()
            ->withMessage('Directory company social created successfully.')
            ->withData(['social' => $directoryCompanySocial])
            ->build();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DirectoryCompany  $directoryCompany
     * @param  \App\Models\DirectoryCompanySocial  $directoryCompanySocial
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(DirectoryCompany $directoryCompany, DirectoryCompanySocial $directoryCompanySocial)
    {
        $directoryCompanySocial = QueryBuilder::for(
            DirectoryCompanySocial::where('id', $directoryCompanySocial->id)
        )
            ->allowedIncludes([
                'directoryCompany'
            ])
            ->firstOrFail();

        return ResponseBuilder::asSuccess()
            ->withMessage('Directory company social fetched successfully.')
            ->withData(['social' => $directoryCompanySocial])
            ->build();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateDirectoryCompanySocialRequest  $request
     * @param  \App\Models\DirectoryCompany $directoryCompany
     * @param  \App\Models\DirectoryCompanySocial  $directoryCompanySocial
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(
        UpdateDirectoryCompanySocialRequest $request,
        DirectoryCompany $directoryCompany,
        DirectoryCompanySocial $directoryCompanySocial,
    ) {
        abort_if(
            $directoryCompany->socialAccount->id !== $directoryCompanySocial->id,
            Response::HTTP_NOT_FOUND,
            'Social details not found for company'
        );

        $directoryCompanySocial = $this->directoryCompanySocialService
            ->update($request, $directoryCompany, $directoryCompanySocial);

        return ResponseBuilder::asSuccess()
            ->withMessage('Directory company social updated successfully.')
            ->withData(['social' => $directoryCompanySocial])
            ->build();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DirectoryCompany  $directoryCompany
     * @param  \App\Models\DirectoryCompanySocial  $directoryCompanySocial
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy(DirectoryCompany $directoryCompany, DirectoryCompanySocial $directoryCompanySocial)
    {
        abort_if(
            $directoryCompany->socialAccount->id !== $directoryCompanySocial->id,
            Response::HTTP_NOT_FOUND,
            'Social details not found for company'
        );

        $directoryCompanySocial = $this->directoryCompanySocialService->destroy($directoryCompanySocial);

        if ($directoryCompanySocial) {
            return ResponseBuilder::asSuccess()
            ->withMessage('Directory Company Social deleted successfully')
            ->build();
        }
    }
}
