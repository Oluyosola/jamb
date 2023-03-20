<?php

namespace App\Services;

use App\Enums\MediaCollection;
use App\Enums\PaymentPurpose;
use App\Http\Requests\API\V1\User\Advert\StoreAdvertRequest;
use App\Http\Requests\API\V1\User\Advert\UpdateAdvertRequest;
use App\Models\Advert;
use App\Models\Transaction;
use App\Services\User\Transaction\TransactionService;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdvertService
{
    /**
     * Fetch a list of resources from storage.
     */
    public function index(Request $request): HasMany
    {
        $myAdverts = $request->user('user')->myAdverts();

        return $myAdverts;
    }

    /**
     * Store a new advert resource to storage.
     *
     * @param StoreAdvertRequest $request
     * @return Advert $advert
     */
    public function store(StoreAdvertRequest $request): Advert
    {
        DB::beginTransaction();
        $advert = new Advert();
        $advert->user()->associate($request->user('user'));
        $advert->advertPlan()->associate($request->advert_plan_id);
        $advert->view_count = 0;
        $advert->advert_url = $request->advert_url;
        $advert->charging_price = $advert->advertPlan->standard_price;
        $advert->save();

         // Store the banner image
        if ($request->banner) {
            $advert->addMediaFromRequest('banner')->toMediaCollection(MediaCollection::ADVERTBANNER);
        }

        // create new transaction
        $userModel = $request->user('user');
        // $transaction = new TransactionService();
        // $transaction = $transaction->store($advert, $userModel);

        DB::commit();
        return $advert;
    }

    /**
     * Show an advert resource.
     *
     * @param Advert $advert
     */
    public function show(Advert $advert): Advert
    {
        return $advert;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdvertRequest $request, Advert $advert)
    {
        DB::beginTransaction();
        $advert->advertPlan()->associate($request->advert_plan_id);
        $advert->advert_url = $request->advert_url;
        $advert->update();
        $advert->clearMediaCollection(MediaCollection::ADVERTBANNER);

        // Store the banner image
        if ($request->banner) {
            // Delete previous media image
            $advert->addMediaFromRequest('banner')->toMediaCollection(MediaCollection::ADVERTBANNER);
        }

        DB::commit();
        return $advert;
    }

    /**
     * Delete specified resource from storage.
     *
     * @param Advert $advert
     */
    public function destroy(Advert $advert): bool
    {
        return $advert->delete() ? true : false;
    }

    /**
     * Process payment for advert plans.
     *
     * @param PaymentTransaction $paymentTransaction
     * @return void
     */
    public static function serve(Transaction $transaction)
    {
        $user = $transaction->user;

        $advertSubscription = Advert::whereId($transaction->transactionable_id)
            ->first();

        $advertSubscription->user_id = $transaction->user_id;
        $advertSubscription->transaction_id = $transaction->id;
        $advertSubscription->is_recurring = $advertSubscription->is_recurring ?? true;
        $advertSubscription->charging_price = $transaction->amount;
        $advertSubscription->charging_currency = $transaction->currency;
        $advertSubscription->start_date = now();
        $advertSubscription->end_date = now()->addDays($advertSubscription->advertPlan->duration_in_days);

        $advertSubscription->save();
    }
}
