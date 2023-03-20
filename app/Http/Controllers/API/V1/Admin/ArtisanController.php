<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Artisan\StoreArtisanRequest;
use App\Http\Requests\API\V1\Admin\Artisan\StoreBlockedAccountMessageRequest;
use App\Http\Requests\API\V1\Admin\Artisan\UpdateArtisanRequest;
use App\Models\Artisan;
use App\Services\ArtisanService;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\QueryBuilder\QueryBuilder;

class ArtisanController extends Controller
{
    private ArtisanService $artisanService;

    /**
     * Inject the dependencies into the controller class.
     *
     * @param ArtisanService $artisanService
     * @return void
     */
    public function __construct(ArtisanService $artisanService)
    {
        $this->artisanService = $artisanService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $artisans = QueryBuilder::for($this->artisanService->index())
            ->allowedIncludes([
                'association',
                'bankDetail',
                'city',
                'state',
                'wallet',
            ])
            ->allowedSorts([
                'balance'
            ])
            ->allowedFilters([
                'is_active',
                'category_id',
                'association_id'
            ])
            ->defaultSort('-created_at')
            ->paginate($request->per_page);

        return ResponseBuilder::asSuccess()
            ->withMessage('Artisans retrieved successfully.')
            ->withData(['artisans' => $artisans])
            ->build();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreArtisanRequest  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(StoreArtisanRequest $request)
    {
        $artisan = $this->artisanService->store($request);

        return ResponseBuilder::asSuccess()
            ->withHttpCode(\Symfony\Component\HttpFoundation\Response::HTTP_CREATED)
            ->withMessage('Artisan created successfully.')
            ->withData(['artisan' => $artisan])
            ->build();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Artisan  $artisan
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show($artisan)
    {
        $artisan = QueryBuilder::for($this->artisanService->index()->where('id', $artisan))
            ->allowedIncludes([
                'association',
                'bankDetail',
                'city',
                'state',
                'wallet',
            ])
            ->firstOrFail();

        return ResponseBuilder::asSuccess()
            ->withData(['artisan' => $artisan])
            ->withMessage('Artisan retrieved successful.')
            ->build();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateArtisanRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(UpdateArtisanRequest $request, Artisan $artisan)
    {
        $artisan = $this->artisanService->update($request, $artisan);

        return ResponseBuilder::asSuccess()
            ->withMessage('Artisan updated successfully.')
            ->withData(['artisan' => $artisan])
            ->build();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Artisan  $artisan
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy(Artisan $artisan)
    {
        $artisan->delete();

        return ResponseBuilder::asSuccess()
            ->withMessage('Artisan deleted successfully.')
            ->build();
    }

    /**
     * Restore the specified deleted resource from storage.
     *
     * @param  \App\Models\Artisan  $artisan
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function restore(Artisan $artisan)
    {
        $artisan->restore();

        return ResponseBuilder::asSuccess()
            ->withMessage('Artisan restored successfully.')
            ->build();
    }

    /**
     * Toggle blocked status.
     *
     * @param StoreBlockedAccountMessageRequest $request
     * @param Artisan $artisan
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toggleBlockedStatus(StoreBlockedAccountMessageRequest $request, Artisan $artisan)
    {
        $artisan = $this->artisanService->toggleBlockedStatus($request, $artisan);

        $status = $artisan->is_blocked ? '' : 'un';

        return ResponseBuilder::asSuccess()
            ->withData(['artisan' => $artisan])
            ->withMessage("Artisan {$status}blocked successfully.'")
            ->build();
    }
}
