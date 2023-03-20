<?php

namespace App\Services\Directory;

use App\Http\Requests\API\V1\Admin\DirectoryCompany\StoreDirectoryCompanyRequest;
use App\Http\Requests\API\V1\Admin\DirectoryCompany\UpdateDirectoryCompanyRequest;
use App\Models\DirectoryCompany;
use Illuminate\Contracts\Auth\Authenticatable;

class DirectoryCompanyService
{
    /**
     * Fetch all directories.
     */
    public function index()
    {
        return DirectoryCompany::query();
    }

    /**
     * Store a new directory company in storage.
     *
     * @param StoreDirectoryCompanyRequest $request
     * @param Authenticatable $creator
     * @return DirectoryCompany $directoryCompany
     */
    public function store(StoreDirectoryCompanyRequest $request, Authenticatable $creator)
    {
        $directoryCompany = new DirectoryCompany();
        $directoryCompany->name = $request->name;
        $directoryCompany->creator()->associate($creator);
        $directoryCompany->category()->associate($request->category_id);
        $directoryCompany->subCategory()->associate($request->sub_category_id);
        $directoryCompany->email = $request->email;
        $directoryCompany->website = $request->website;
        $directoryCompany->year_founded = $request->year_founded;
        $directoryCompany->description = $request->description;
        $directoryCompany->publish = $request->is_published ? true : false;
        $directoryCompany->save();

        return $directoryCompany;
    }

    /**
     * Update a directory company in storage.
     *
     * @param UpdateDirectoryCompanyRequest $request
     * @param Authenticatable $creator
     * @return DirectoryCompany $directoryCompany
     */
    public function update(
        UpdateDirectoryCompanyRequest $request,
        DirectoryCompany $directoryCompany,
        Authenticatable $creator
    ) {
        $directoryCompany->creator()->associate($creator);
        $directoryCompany->category()->associate($request->category_id);
        $directoryCompany->subCategory()->associate($request->sub_category_id);
        $directoryCompany->name = $request->name;
        $directoryCompany->email = $request->email;
        $directoryCompany->website = $request->website;
        $directoryCompany->year_founded = $request->year_founded;
        $directoryCompany->description = $request->description;
        $directoryCompany->publish = $request->is_published ? true : false;
        $directoryCompany->update();

        return $directoryCompany;
    }

    /**
     * Delete the specified directory company.
     *
     * @param DirectoryCompany $directoryCompany
     * @return bool
     */
    public function destroy(DirectoryCompany $directoryCompany): bool
    {
        return $directoryCompany->delete() ? true : false;
    }

    /**
     * Restore the specified directory company that has been deleted.
     *
     * @param DirectoryCompany $directoryCompany
     * @return bool
     */
    public function restore(DirectoryCompany $directoryCompany): bool
    {
        return $directoryCompany->restore();
    }
}
