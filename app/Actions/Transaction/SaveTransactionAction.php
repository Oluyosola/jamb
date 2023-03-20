<?php

namespace App\Actions\Transaction;

use App\Models\Transaction;

class SaveTransactionAction
{
    /**
     * Create a new action instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the action.
     *
     * @param object $paymentTransaction
     * @return Transaction
     */
    public function execute($paymentTransaction)
    {
        $transaction = new Transaction();
        $transaction->user_id = $paymentTransaction->user_id;
        $transaction->transactionable_id = $paymentTransaction->transactionable_id;
        $transaction->transactionable_type = $paymentTransaction->transactionable_type;
        $transaction->reference = $paymentTransaction->reference;
        $transaction->payment_status = $paymentTransaction->status;
        $transaction->payment_gateway = $paymentTransaction->paymentGateway;
        $transaction->payment_method = $paymentTransaction->paymentMethod;
        $transaction->payment_purpose = $paymentTransaction->paymentPurpose;
        $transaction->gateway_reference = $paymentTransaction->reference;
        $transaction->amount = $paymentTransaction->amount;
        $transaction->currency = $paymentTransaction->currency;
        $transaction->discount = $paymentTransaction->discount ?? null;
        $transaction->payment_purpose = $paymentTransaction->paymentPurpose;
        $transaction->metadata = json_encode($paymentTransaction->metadata);
        $transaction->save();

        return $transaction;
    }
}
