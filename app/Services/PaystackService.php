<?php

namespace App\Services;

use App\Actions\Transaction\SaveTransactionAction;
use App\Enums\PaymentGateway;
use App\Enums\PaymentPurpose;
use App\Enums\PaymentStatus;
use Illuminate\Support\Facades\Log;
use stdClass;
use Yabacon\Paystack;

class PaystackService
{
    protected Paystack $paystack;

    /**
     * Prepare the paystack library.
     *
     * @return void
     */
    public function __construct()
    {
        $this->paystack = new Paystack(config('paystack.secret_key'));
    }

    /**
     * Get the Paystack library service.
     *
     * @return Paystack
     */
    public function getFactory()
    {
        return $this->paystack;
    }

    /**
     * Process the paystack transaction.
     *
     * @param string $reference
     * @return void
     * @throws \InvalidArgumentException
     * @throws \PDOException
     * @throws \Exception
     */
    public function process(string $reference)
    {
        // Verify the transaction
        $transaction = $this->getFactory()->transaction->verify([
            'reference' => $reference,
        ]);

        // Verify that the transaction was successfully registered in our Paystack account
        if ($transaction->status === false) {
            exit();
        }

        // Verify that our metadata was built into the transaction.
        if (!isset($transaction->data->metadata)) {
            exit();
        }

        try {
            // Build the payment transaction type class
            $paymentTransactionType = $this->buildPaymentTransactionType($transaction);

            // Save the payment transaction
            $paymentTransaction = (new SaveTransactionAction())->execute($paymentTransactionType);

            // Exit if the payment transaction is not successful
            if ($paymentTransaction->payment_status != PaymentStatus::SUCCESS) {
                exit();
            }

            // Proceed to main course of action
            switch ($paymentTransaction->payment_purpose) {
                case PaymentPurpose::ADVERT:
                    \App\Services\AdvertService::serve($paymentTransaction);
                    break;

                case PaymentPurpose::WALLETTOPUP:
                    \App\Services\WalletService::serve($paymentTransaction);
                    break;

                case PaymentPurpose::CHECKOUT():
                    \App\Services\CheckoutService::serve($paymentTransaction);
                    break;

                default:
                    break;
            }
            http_response_code(200);
        } catch (\InvalidArgumentException $e) {
            Log::error('An invalid argument exception occurred.', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            exit();
        } catch (\PDOException $e) {
            Log::error('A database query exception occurred.', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            exit();
        } catch (\Exception $e) {
            Log::error('An exception occurred.', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            exit();
        }
    }

    /**
     * Build the payment transaction type class.
     *
     * @param mixed $transaction
     * @return stdClass $paymentTransactionType
     */
    public function buildPaymentTransactionType($transaction)
    {
        $paymentTransactionType = new stdClass();
        $paymentTransactionType->status = $transaction->data->status;
        $paymentTransactionType->paymentGateway = PaymentGateway::PAYSTACK;
        $paymentTransactionType->paymentMethod = $transaction->data->channel;
        $paymentTransactionType->paymentPurpose = $transaction->data->metadata->payment_purpose;
        $paymentTransactionType->reference = $transaction->data->reference;
        $paymentTransactionType->amount = (float) ($transaction->data->amount / 100);
        $paymentTransactionType->currency = $transaction->data->currency;
        $paymentTransactionType->metadata = json_encode($transaction->data->metadata);
        $paymentTransactionType->user_id = $transaction->data->metadata->user_id;
        $paymentTransactionType->transactionable_id = $transaction->data->metadata->transactionable_id;
        $paymentTransactionType->transactionable_type = $transaction->data->metadata->transactionable_type;
        $paymentTransactionType->discount = $transaction->metadata->discount ?? null;

        return $paymentTransactionType;
    }
}
