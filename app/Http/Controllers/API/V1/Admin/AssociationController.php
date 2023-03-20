<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Association\StoreAssociationRequest;
use App\Http\Requests\API\V1\Admin\Association\UpdateAssociationRequest;
use App\Models\Association;
use App\Services\AssociationService;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\QueryBuilder\QueryBuilder;

class AssociationController extends Controller
{
    private AssociationService $associationService;

    /**
     * Inject the dependencies needed for the controller.
     *
     * @return void
     */
    public function __construct(AssociationService $associationService)
    {
        $this->associationService = $associationService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $associations = QueryBuilder::for($this->associationService->index())
            ->allowedFilters([
                'name',
                'is_active'
            ])
            ->paginate($request->per_page);

        return ResponseBuilder::asSuccess()
                ->withMessage('Associations retrieved successfully.')
                ->withData(['associations' => $associations])
                ->build();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreAssociationRequest  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(StoreAssociationRequest $request)
    {
        $association = $this->associationService->store($request);

        return ResponseBuilder::asSuccess()
            ->withHttpCode(\Symfony\Component\HttpFoundation\Response::HTTP_CREATED)
            ->withMessage('Association created successfully.')
            ->withData(['association' => $association])
            ->build();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Association  $association
     * @return \Illuminate\Http\Response
     */
    public function show(Association $association)
    {
        $association = QueryBuilder::for($this->associationService->index()->where('id', $association->id))
            ->allowedIncludes([
                'artisans'
            ])
            ->firstOrFail();

        return ResponseBuilder::asSuccess()
            ->withMessage('Association retrieved successfully.')
            ->withData(['association' => $association])
            ->build();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateAssociationRequest  $request
     * @param  \App\Models\Association  $association
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(UpdateAssociationRequest $request, Association $association)
    {
        $association = $this->associationService->update($request, $association);

        return ResponseBuilder::asSuccess()
            ->withMessage('Association updated successfully.')
            ->withData(['association' => $association])
            ->build();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Association  $association
     * @return \Illuminate\Http\Response
     */
    public function destroy(Association $association)
    {
        $association->delete();

        return ResponseBuilder::asSuccess()
            ->withMessage('Association deleted successfully.')
            ->build();
    }

    /**
     * Restore the specified deleted resource from storage.
     *
     * @param  \App\Models\Association  $association
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function restore(Association $association)
    {
        $association->restore();

        return ResponseBuilder::asSuccess()
            ->withMessage('Association restored successfully.')
            ->build();
    }
}
