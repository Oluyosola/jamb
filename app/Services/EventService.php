<?php

namespace App\Services;

use App\Enums\MediaCollection;
use App\Models\Event;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Http\FormRequest;

class EventService
{
    /**
     * Get all events in storage.
     */
    public function index()
    {
        return Event::query();
    }

    /**
     * Store a new Event in storage.
     *
     * @param FormRequest $request
     * @param Authenticatable $creator
     * @return Event $event
     */
    public function store(FormRequest $request, Authenticatable $creator)
    {
        $event = new Event();
        $event->creator()->associate($creator);
        $event->title = $request->title;
        $event->start_date = $request->start_date;
        $event->end_date = $request->end_date;
        $event->venue_type = $request->venue_type;
        $event->venue_details = $request->venue_details;
        $event->country_id = $request->country_id;
        $event->state_id = $request->state_id;
        $event->city_id = $request->city_id;
        $event->address = $request->address;
        $event->registration_details = $request->registration_details;
        $event->is_active = $request->is_active ? true : false;
        $event->is_published = $request->is_published ? true : false;

        $event->save();

        if ($request->featured_image) {
            $event->addMediaFromRequest('featured_image')->toMediaCollection(MediaCollection::FEATUREDIMAGE);
        }

        return $event;
    }

    /**
     * Show a Event.
     *
     * @param Event $event
     * @return Event $event
     */
    public function show(Event $event)
    {
        return $event;
    }

    /**
     * Update a event.
     *
     * @param FormRequest $request
     * @param Event $event
     * @return Event $event
     */
    public function update(FormRequest $request, Event $event)
    {
        $event->title = $request->title;
        $event->start_date = $request->start_date;
        $event->end_date = $request->end_date;
        $event->venue_type = $request->venue_type;
        $event->venue_details = $request->venue_details;
        $event->country_id = $request->country_id;
        $event->state_id = $request->state_id;
        $event->city_id = $request->city_id;
        $event->address = $request->address;
        $event->registration_details = $request->registration_details;
        $event->is_active = $request->is_active ? true : false;
        $event->is_published = $request->is_published ? true : false;

        $event->save();

        if ($request->featured_image) {
            $event->addMediaFromRequest('featured_image')->toMediaCollection(MediaCollection::FEATUREDIMAGE);
        }

        return $event;
    }

     /**
     * Delete the specified event.
     *
     * @param Event $event
     * @return bool
     */
    public function destroy(Event $event): bool
    {
        return $event->delete() ? true : false;
    }

    /**
     * Restore the specified event.
     *
     * @param Event $event
     * @return bool
     */
    public function restore(Event $event)
    {
        return $event->restore();
    }
}
