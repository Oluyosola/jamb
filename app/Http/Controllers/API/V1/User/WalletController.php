<?php

namespace App\Http\Controllers\API\V1\User;

use App\Enums\PaymentPurpose;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\User\Wallet\StoreWalletRequest;
use App\Models\Wallet;
use App\Services\PaystackService;
use App\Services\WalletService;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\QueryBuilder\QueryBuilder;

class WalletController extends Controller
{
    private WalletService $walletService;

    /**
     * Inject dependencies to the controller.
     *
     * @param WalletService $walletService
     * @return void
     */
    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $wallet = QueryBuilder::for($request->user()->wallet())
            ->allowedIncludes([
                'owner'
            ])
            ->first();

        return ResponseBuilder::asSuccess()
            ->withMessage('Wallet fetched successfully.')
            ->withData(['wallet' => $wallet])
            ->build();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();

        abort_if(
            !is_null($user->wallet),
            403,
            'You already have a wallet.'
        );

        $wallet = $user->createWallet();

        return ResponseBuilder::asSuccess()
            ->withMessage('Wallet created successfully.')
            ->withData(['wallet' => $wallet])
            ->build();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function show(Wallet $wallet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wallet $wallet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wallet $wallet)
    {
        //
    }

    /**
     * Initiate a paystack transaction.
     *
     * @param  StoreWalletRequest  $request
     * @param  PaystackService $paystackService
     * @return \Illuminate\Http\Response
     */
    public function paystackTopupIntent(StoreWalletRequest $request, PaystackService $paystackService)
    {
        $user = $request->user();
        $wallet = $request->user()->wallet;

        $transaction = $paystackService->getFactory()->transaction->initialize([
            'amount' => (int) $request->amount * 100,
            'email' => $user->email,
            'currency' => 'NGN',
            'callback_url' => $request->callbackUrl,
            'metadata' => [
                'user_id' => $user->id, // the user that initiated the payment
                'payment_purpose' => PaymentPurpose::WALLETTOPUP, // the purpose of the payment
                'transactionable_id' => $wallet->id,
                'transactionable_type' => get_class($wallet),
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
