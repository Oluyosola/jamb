<?php

namespace App\Services;

use App\Interfaces\ModelInterface;
use App\Models\Admin;
use App\Models\Artisan;
use App\Models\BankDetail;
use App\Models\User;

class BankDetailService implements ModelInterface
{
    /**
     * Get all BankDetails.
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return BankDetail::query();
    }

    /**
     * Store a new BankDetail.
     *
     * @param $request
     * @return BankDetail
     */
    public function store($request)
    {
        switch ($request->owner_type) {
            case 'artisan':
                $ownerType = Artisan::class;
                break;

            case 'admin':
                $ownerType = Admin::class;
                break;

            default:
                $ownerType = User::class;
                break;
        }
        $bankDetail = new BankDetail();
        $bankDetail->owner_id = $request->owner_id;
        $bankDetail->owner_type = $ownerType;
        $bankDetail->bank_name = $request->bank_name;
        $bankDetail->account_number = $request->account_number;
        $bankDetail->account_type = $request->account_type;
        $bankDetail->account_holder_name = $request->account_holder_name;
        $bankDetail->save();

        return $bankDetail;
    }

    /**
     * Show an BankDetail.
     *
     * @param $bankDetail
     * @return BankDetail
     */
    public function show($bankDetail)
    {
        return $bankDetail;
    }

    /**
     * Update an BankDetail.
     *
     * @param $request, $bankDetail
     * @return BankDetail
     */
    public function update($request, $bankDetail)
    {
        switch ($request->owner_type) {
            case 'artisan':
                $ownerType = Artisan::class;
                break;

            case 'admin':
                $ownerType = Admin::class;
                break;

            default:
                $ownerType = User::class;
                break;
        }
        $bankDetail->owner_id = $request->owner_id;
        $bankDetail->owner_type = $ownerType;
        $bankDetail->bank_name = $request->bank_name;
        $bankDetail->account_number = $request->account_number;
        $bankDetail->account_type = $request->account_type;
        $bankDetail->account_holder_name = $request->account_holder_name;
        $bankDetail->save();

        return $bankDetail;
    }

    /**
     * Delete an BankDetail.
     *
     * @param $bankDetail
     * @return bool|null
     * @throws \Exception
     */
    public function destroy($bankDetail)
    {
        $bankDetail->delete();
    }
}
