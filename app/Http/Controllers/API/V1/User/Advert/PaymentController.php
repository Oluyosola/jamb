<?php

namespace App\Http\Controllers\API\V1\User\Advert;

use App\Enums\PaymentPurpose;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\User\Advert\PaystackPaymentIntentRequest;
use App\Models\Advert;
use App\Models\AdvertPlan;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class PaymentController extends Controller
{
    /**
     * Generate a payment intent for Paystack.
     *
     * @param PaystackPaymentIntentRequest $request
     * @param PaystackService $paystackService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function paystack(PaystackPaymentIntentRequest $request, PaystackService $paystackService)
    {
        $user = $request->user();

        $advert = Advert::findOrFail($request->advert_id);
        $plan = AdvertPlan::findOrFail($advert->advert_plan_id);

        $transaction = $paystackService->getFactory()->transaction->initialize([
            'amount' => (int) ($plan->standard_price * 100),
            'email' => $user->email,
            'currency' => 'NGN',
            'callback_url' => $request->callbackUrl,
            'metadata' => [
                'user_id' => $user->id, // the user that initiated the payment
                'payment_purpose' => PaymentPurpose::ADVERT,
                'transactionable_id' => $request->advert_id,
                'transactionable_type' => \App\Models\Advert::class,
                // 'discount_difference' => 0.00, // if applicable
            ]
        ]);

        return ResponseBuilder::asSuccess()
            ->withMessage('Payment Intent Generated Successfully.')
            ->withData([
                'authorization_url' => $transaction->data->authorization_url,
                'access_code' => $transaction->data->access_code,
                'reference' => $transaction->data->reference,
            ])
            ->build();
    }
}
