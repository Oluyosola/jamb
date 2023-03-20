<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Wallet;

class WalletService
{
    /**
     * Get all wallets.
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Wallet::query();
    }

    /**
     * Store a new Wallet.
     *
     * @param $request
     * @return Wallet
     */
    public function store($request)
    {
        switch ($request->owner_type) {
            case 'artisan':
                $ownerType = Artisan::class;
                break;

            default:
                $ownerType = User::class;
                break;
        }
        $wallet = new Wallet();
        $wallet->owner_id = $request->owner_id;
        $wallet->owner_type = $ownerType;
        $wallet->name = $request->name;
        $wallet->description = $request->description;
        $wallet->balance = $request->balance;
        $wallet->currency = $request->currency;
        $wallet->is_active = $request->is_active ? true : false;
    }

    /**
     * Paystack initialization.
     *
     * @param Transaction $transaction
     * @return void
     */
    public static function serve(Transaction $transaction)
    {
        $user = $transaction->user;

        $user->wallet->balance = $user->wallet?->balance + $transaction->amount;
        $user->wallet->save();
    }
}
