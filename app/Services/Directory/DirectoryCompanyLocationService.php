<?php

namespace App\Services\Directory;

use App\Http\Requests\API\V1\Admin\DirectoryCompany\Location\StoreDirectoryCompanyLocationRequest;
use App\Http\Requests\API\V1\Admin\DirectoryCompany\Location\UpdateDirectoryCompanyLocationRequest;
use App\Models\DirectoryCompany;
use App\Models\DirectoryCompanyLocation;

class DirectoryCompanyLocationService
{
    /**
     * Fetch all directory company locations.
     *
     * @param DirectoryCompany $directoryCompany
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function index(DirectoryCompany $directoryCompany)
    {
        return $directoryCompany->locations();
    }

    /**
     * Store a new directory company location in storage.
     *
     * @param StoreDirectoryCompanyRequest $request
     * @param DirectoryCompany $directoryCompany
     * @return DirectoryCompanyLocation $location
     */
    public function store(StoreDirectoryCompanyLocationRequest $request, DirectoryCompany $directoryCompany)
    {
        $location = new DirectoryCompanyLocation();
        $location->directoryCompany()->associate($directoryCompany);
        $location->street_address = $request->street_address;
        $location->address_landmark = $request->address_landmark;
        $location->city_id = $request->city_id;
        $location->local_government_id = $request->local_government_id;
        $location->phone_1 = $request->phone_1;
        $location->phone_2 = $request->phone_2;
        $location->website_url = $request->website_url;
        $location->is_active = $request->is_active ? true : false;
        $location->is_primary = $request->is_primary ? true : false;
        $location->save();

        return $location;
    }

    /**
     * Update a directory company location in storage.
     *
     * @param UpdateDirectoryCompanyLocationRequest $request
     * @param DirectoryCompany $directoryCompany
     * @param DirectoryCompanyLocation $directoryCompanyLocation
     * @return DirectoryCompanyLocation $directoryCompanyLocation
     */
    public function update(
        UpdateDirectoryCompanyLocationRequest $request,
        DirectoryCompany $directoryCompany,
        DirectoryCompanyLocation $directoryCompanyLocation
    ) {
        $directoryCompanyLocation->directoryCompany()->associate($directoryCompany);
        $directoryCompanyLocation->street_address = $request->street_address;
        $directoryCompanyLocation->address_landmark = $request->address_landmark;
        $directoryCompanyLocation->city_id = $request->city_id;
        $directoryCompanyLocation->local_government_id = $request->local_government_id;
        $directoryCompanyLocation->phone_1 = $request->phone_1;
        $directoryCompanyLocation->phone_2 = $request->phone_2;
        $directoryCompanyLocation->website_url = $request->website_url;
        $directoryCompanyLocation->is_active = $request->is_active ? true : false;
        $directoryCompanyLocation->is_primary = $request->is_primary ? true : false;
        $directoryCompanyLocation->update();

        return $directoryCompanyLocation;
    }

    /**
     * Delete the specified directory company location.
     *
     * @param DirectoryCompanyLocation $directoryCompanyLocation
     * @return bool
     */
    public function destroy(DirectoryCompanyLocation $directoryCompanyLocation): bool
    {
        return $directoryCompanyLocation->delete() ? true : false;
    }

    /**
     * Restore the specified directory company location that has been deleted.
     *
     * @param DirectoryCompanyLocation $directoryCompanyLocation
     * @return bool
     */
    public function restore(DirectoryCompanyLocation $directoryCompanyLocation): bool
    {
        return $directoryCompanyLocation->restore();
    }
}
