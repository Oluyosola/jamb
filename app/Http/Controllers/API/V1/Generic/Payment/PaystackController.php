<?php

namespace App\Http\Controllers\API\V1\Generic\Payment;

use App\Enums\PaymentGateway;
use App\Http\Controllers\Controller;
use App\Models\WebhookEvent;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use Yabacon\Paystack\Event;

class PaystackController extends Controller
{
    public PaystackService $paystackService;

    /**
     * Instanstiate the class.
     *
     * @param PaystackService $paystackService
     */
    public function __construct(PaystackService $paystackService)
    {
        $this->paystackService = $paystackService;
    }

    /**
     * Handle Payments for paystack.
     *
     * @param Request $request
     */
    public function webhook(Request $request)
    {
        // Retrieve the request's body and parse it as JSON.
        $event = Event::capture();
        http_response_code(200);

        // Log event in our DB.
        $webhookEvent = new WebhookEvent();
        $webhookEvent->payment_gateway = PaymentGateway::PAYSTACK;
        $webhookEvent->log = $event->raw;
        $webhookEvent->save();

        // Verify that the signature matches one of your keys.
        $my_keys = [
            'live' => config('paystack.secret_key'),
        ];

        if (!$event->discoverOwner($my_keys)) {
            exit('We do not know you');
        }

        $this->paystackService->process(json_decode($webhookEvent->log)->data->reference);

        return response('Webhook task was successful.');
    }
}
