<?php

namespace App\Services;

use App\Interfaces\ModelInterface;
use App\Models\Association;

class AssociationService implements ModelInterface
{
    /**
     * Get all associations.
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Association::query();
    }

    /**
     * Store a new Association.
     *
     * @param $request
     * @return Association
     */
    public function store($request)
    {
        $association = new Association();
        $association->name = $request->name;
        $association->description = $request->description;
        $association->is_active = $request->is_active ? true : false;

        $association->save();

        return $association;
    }

    /**
     * Show an Association.
     *
     * @param $association
     * @return Association
     */
    public function show($association)
    {
        return $association;
    }

    /**
     * Update an Association.
     *
     * @param $request, $association
     * @return Association
     */
    public function update($request, $model)
    {
        $model->name = $request->name;
        $model->description = $request->description;
        $model->is_active = $request->is_active ? true : false;
        $model->save();

        return $model;
    }

    /**
     * Destroy an Association.
     *
     * @param $association
     * @return bool
     */
    public function destroy($association)
    {
        $association->delete();

        return true;
    }

    /**
     * Restore an Association.
     *
     * @param $association
     */
    public function restore($association)
    {
        $association->restore();

        return true;
    }
}
