<?php

namespace App\Observers;

use App\Actions\GenerateUniqueIdAction;
use App\Models\Wallet;
use App\Notifications\WalletUpdatedNotification;

class WalletObserver
{
    /**
     * Handle the Wallet "creating" event.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return void
     */
    public function creating(Wallet $wallet)
    {
        $wallet->unique_id = 'JW' . (new GenerateUniqueIdAction())->execute($wallet->getTable(), 'unique_id');
    }

    /**
     * Handle the Wallet "created" event.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return void
     */
    public function created(Wallet $wallet)
    {
        //
    }

    /**
     * Handle the Wallet "updating" event.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return void
     */
    public function updating(Wallet $wallet)
    {
        // Fro bonus balance
        if ($wallet->isDirty(['bonus_balance'])) {
            $action = '';
            $amount = '';
            $array['balance_type'] = 'Bonus';
            $originalBonusBalance = (float) $wallet->getOriginal()['bonus_balance'];
            if (
                (float) $wallet->getOriginal()['bonus_balance'] > (float) $wallet->bonus_balance
            ) {
                $action = 'debited';
                $amount = $originalBonusBalance - $wallet->bonus_balance;
                $array['action'] = $action;
                $array['amount'] = $amount;
            } else {
                $action = 'credited';
                $amount = $originalBonusBalance - $wallet->bonus_balance;
                $array['action'] = $action;
                $array['amount'] = $amount;
            }
            $wallet->owner->notify(new WalletUpdatedNotification($wallet, $array));
        }

        // For normal balance
        if ($wallet->isDirty(['balance'])) {
            $action = '';
            $amount = '';
            $array['balance_type'] = '';
            $originalBalance = (float) $wallet->getOriginal()['balance'];
            if (
                (float) $wallet->getOriginal()['balance'] > (float) $wallet->balance
            ) {
                $action = 'debited';
                $amount = $originalBalance - $wallet->balance;
                $array['action'] = $action;
                $array['amount'] = $amount;
            } else {
                $action = 'credited';
                $amount =  $wallet->balance - $originalBalance;
                $array['action'] = $action;
                $array['amount'] = $amount;
            }
            $wallet->owner->notify(new WalletUpdatedNotification($wallet, $array));
        }
    }

    /**
     * Handle the Wallet "updated" event.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return void
     */
    public function updated(Wallet $wallet)
    {
        //
    }

    /**
     * Handle the Wallet "deleted" event.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return void
     */
    public function deleted(Wallet $wallet)
    {
        //
    }

    /**
     * Handle the Wallet "restored" event.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return void
     */
    public function restored(Wallet $wallet)
    {
        //
    }

    /**
     * Handle the Wallet "force deleted" event.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return void
     */
    public function forceDeleted(Wallet $wallet)
    {
        //
    }
}
