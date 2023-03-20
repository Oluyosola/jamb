<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\User\Event\StoreEventRequest;
use App\Http\Requests\API\V1\User\Event\UpdateEventRequest;
use App\Models\Event;
use App\Services\EventService;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\QueryBuilder\QueryBuilder;

class EventController extends Controller
{
    /**
     * @var $eventService
     */
    public EventService $eventService;

    /**
     * Instantiate the class and inject classes it depends on.
     *
     * @param EventService $eventService
     */
    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $events = QueryBuilder::for($request->user()->events())
            ->allowedIncludes([
                'country',
                'state',
                'city',
                'creator',
            ])
            ->paginate($request->per_page);

        return ResponseBuilder::asSuccess()
            ->withMessage('Events fetched successfully.')
            ->withData(['events' => $events])
            ->build();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreEventRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEventRequest $request)
    {
        $creator = $request->user();
        $event = $this->eventService->store($request, $creator);

        return ResponseBuilder::asSuccess()
            ->withMessage('Event created successfully.')
            ->withData(['event' => $event])
            ->build();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $event
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function show($event, Request $request)
    {
        $event = QueryBuilder::for($request->user()->events()->where('id', $event))
            ->allowedIncludes([
                'country',
                'state',
                'city',
                'creator',
            ])
            ->firstOrFail();

        return ResponseBuilder::asSuccess()
            ->withData(['event' => $event])
            ->withMessage('Event fetched successfully.')
            ->build();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateEventRequest  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        $event = $this->eventService->update($request, $event);

        return ResponseBuilder::asSuccess()
            ->withMessage('Event updated successfully.')
            ->withData(['event' => $event])
            ->build();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);

        $this->eventService->destroy($event);

        return ResponseBuilder::asSuccess()
            ->withMessage('Event deleted successfully.')
            ->build();
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Models\PoEventst  $post
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function restore(Event $event)
    {
        $this->authorize('restore', $event);

        $this->eventService->restore($event);

        return ResponseBuilder::asSuccess()
            ->withMessage('Even restored successfully')
            ->withData(['event' => $event])
            ->build();
    }
}
