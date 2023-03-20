<?php

namespace App\Http\Controllers\API\V1\Generic;

use App\Http\Controllers\Controller;
use App\Models\DirectoryCompany;
use App\Models\DirectoryCompanySocial;
use App\Services\Directory\DirectoryCompanySocialService;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\QueryBuilder\QueryBuilder;

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
}
