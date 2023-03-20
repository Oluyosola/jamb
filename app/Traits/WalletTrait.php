<?php

namespace App\Traits;

use App\Actions\GenerateUniqueIdAction;
use App\Actions\Transaction\SaveTransactionAction;
use App\Enums\PaymentGateway;
use App\Enums\PaymentPurpose;
use App\Enums\SystemConfigEnum;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Model;

trait WalletTrait
{
    /**
     * Create wallet.
     *
     * @return Wallet
     */
    public function createWallet(): Wallet
    {
        $ownerType = get_class($this);
        $ownerId = $this->id;

        $wallet = new Wallet();
        $wallet->owner_id = $ownerId;
        $wallet->owner_type = $ownerType;
        $wallet->balance = 0;
        $wallet->bonus_balance = SystemConfigEnum::WALLETBONUSAMOUNT;
        $wallet->currency = 'NGN';
        $wallet->save();

        return $wallet;
    }

    /**
     * Charge wallets.
     *
     * @param Model $transactionable
     * @param array $array['amount'=>float,'payment_purpose'=>string]
     * @throws \Exception
     * @return mixed
    */
    public function chargeWallet(Model $transactionable, $array)
    {
        $wallet = $this->wallet;
        $amount = $array['amount'];
        $paymentPurpose = $array['payment_purpose'];
        if ($wallet->bonus_balance < $amount && $wallet->balance < $amount) {
            throw new \Exception("Insufficient balance");
        }

        // Charge bonus balance else, charge main balance
        if ($wallet->bonus_balance >= $amount) {
            $wallet->bonus_balance = (int) $wallet->bonus_balance - (int) $amount;
        }
        if ($wallet->bonus_balance < $amount) {
            $wallet->balance = (int) $wallet->balance - (int) $amount;
        }
        $wallet->save();

        $wallet->charged_amount = $amount;
        $wallet->payment_purpose = $paymentPurpose;
        $wallet->currency = 'NGN';
        $wallet->transactionable_id = $transactionable->id;
        $wallet->transactionable_type = get_class($transactionable);
        $wallet->user_id = $wallet->owner->id;
        $wallet->metadata = $array['metadata'];

        // Build the payment transaction type class
        $paymentTransactionType = $this->buildPaymentTransactionType($wallet);

        // Save the payment transaction
        $paymentTransaction = (new SaveTransactionAction())->execute($paymentTransactionType);

        return $wallet;
    }

    /**
     * Build the payment transaction type class.
     *
     * @param mixed $transaction
     * @return stdClass $paymentTransactionType
     */
    public function buildPaymentTransactionType($transaction)
    {
        $paymentTransactionType = new \stdClass();
        $paymentTransactionType->status = 'success';
        $paymentTransactionType->paymentGateway = PaymentGateway::WALLET;
        $paymentTransactionType->paymentMethod = PaymentGateway::WALLET;
        $paymentTransactionType->paymentPurpose = $transaction->payment_purpose;
        $paymentTransactionType->reference = (new GenerateUniqueIdAction())->execute('transactions', 'reference');
        $paymentTransactionType->amount = (float) ($transaction->charged_amount);
        $paymentTransactionType->currency = $transaction->currency;
        $paymentTransactionType->user_id = $transaction->user_id;
        $paymentTransactionType->transactionable_id = $transaction->transactionable_id;
        $paymentTransactionType->transactionable_type = $transaction->transactionable_type;
        $paymentTransactionType->discount = $transaction->discount ?? null;
        $paymentTransactionType->metadata = $transaction->metadata;

        return $paymentTransactionType;
    }
}
