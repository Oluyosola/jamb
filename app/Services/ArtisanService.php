<?php

namespace App\Services;

use App\Enums\MediaCollection;
use App\Interfaces\ModelInterface;
use App\Models\Artisan;
use App\Models\BlockedAccountMessage;
use App\Notifications\Artisan\ToggledBlockStatusNotification;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Notification;

class ArtisanService implements ModelInterface
{
    /**
     * Get all artisans.
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Artisan::query();
    }

    /**
     * Store a new artisan.
     *
     * @param $request
     * @return Artisan
     */
    public function store($request)
    {
        $address = $request->address;
        $location=  getCoordinateByAddress($address);
    
        $artisan = new Artisan();
        $artisan->first_name = $request->first_name;
        $artisan->last_name = $request->last_name;
        $artisan->email = $request->email;
        $artisan->business_name = $request->business_name;
        $artisan->profile = $request->profile;
        $artisan->phone = $request->phone;
        $artisan->address = $address;
        $artisan->latitude = $location['latitude'];
        $artisan->longitude = $location['longitude'];
        $artisan->state_id = $request->state_id;
        $artisan->city_id = $request->city_id;
        $artisan->category_id = $request->category_id;
        $artisan->association_id = $request->association_id;
        $artisan->is_active = $request->is_active ? true : false;
        $artisan->save();

        if ($request->logo) {
            $artisan->addMediaFromRequest('logo')->toMediaCollection(MediaCollection::LOGO);
        }

        return $artisan;
    }

    /**
     * Show an artisan.
     *
     * @param $artisan
     * @return Artisan
     */
    public function show($artisan)
    {
        return $artisan;
    }

    /**
     * Update an artisan.
     *
     * @param $request
     * @param $artisan
     * @return Artisan
     */
    public function update($request, $artisan)
    {
        $address = $request->address;
        $location=  getCoordinateByAddress($address);
    
        $artisan->first_name = $request->first_name;
        $artisan->last_name = $request->last_name;
        $artisan->email = $request->email;
        $artisan->business_name = $request->business_name;
        $artisan->profile = $request->profile;
        $artisan->phone = $request->phone;
        $artisan->address = $address;
        $artisan->latitude = $location['latitude'];
        $artisan->longitude = $location['longitude'];
        $artisan->state_id = $request->state_id;
        $artisan->city_id = $request->city_id;
        $artisan->category_id = $request->category_id;
        $artisan->association_id = $request->association_id;
        $artisan->is_active = $request->is_active ? true : false;
        $artisan->save();

        return $artisan;
    }

    /**
     * Delete an artisan.
     *
     * @param $artisan
     * @return bool|null
     */
    public function destroy($artisan)
    {
        return $artisan->delete();
    }

    /**
     * Toggle blocked status of artisans.
     *
     * @param Artisan $artisan
     * @return BlockedAccountMessage blockedAccountMessage
     */
    public function toggleBlockedStatus(FormRequest $request, Artisan $artisan)
    {
        $artisan->is_blocked = !$artisan->is_blocked;
        $blockedAccountMessage = $artisan->createBlockedAccountMessage($request);
        $artisan->save();

        // Notify artisan with reason.
        if (!is_null($artisan->email)) {
            Notification::route('mail', $artisan->email)
                ->notify(new ToggledBlockStatusNotification($blockedAccountMessage));
        }

        return $artisan;
    }
}
