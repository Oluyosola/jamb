<?php

namespace App\Http\Controllers\API\V1\User;

use App\Enums\PaymentPurpose;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\User\Cart\StoreCartRequest;
use App\Http\Requests\API\V1\User\Cart\UpdateCartRequest;
use App\Models\Cart;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{
    /**
     * Inject dependencies
     *
     * @param CartService $cartService
     * @return void
     */
    public function __construct(public CartService $cartService, public CheckoutService $checkoutService)
    {
        //
    }
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cart = QueryBuilder::for($request->user()->carts())
            ->allowedIncludes([
                    'product'
                ])
            ->get();

        return ResponseBuilder::asSuccess()
            ->withMessage('Events fetched successfully.')
            ->withData(['cart' => $cart])
            ->build();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreCartRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCartRequest $request)
    {
        $cart = $this->cartService->store($request);

        return ResponseBuilder::asSuccess()
            ->withHttpCode(Response::HTTP_CREATED)
            ->withData(['cart' => $cart])
            ->withMessage('Cart added successfully.')
            ->build();
    }

    /**
     * Display the specified resource.
     *
     * @param  Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        $cart = QueryBuilder::for(Cart::where('id', $cart->id))
            ->firstOrFail();

        return ResponseBuilder::asSuccess()
            ->withData(['cart' => $cart])
            ->withMessage('Cart fetched successfully.')
            ->build();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateCartRequest  $request
     * @param  Cart $cart
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCartRequest $request, Cart $cart)
    {
        $cart = $this->cartService->update($request, $cart);

        return ResponseBuilder::asSuccess()
            ->withData(['cart' => $cart])
            ->withMessage('Cart updated successfully.')
            ->build();
    }

     /**
     * Initiate a paystack transaction.
     *
     * @param  CheckoutCartRequest  $request
     * @param  PaystackService $paystackService
     * @return \Illuminate\Http\Response
     */
    public function paystackCheckoutIntent(Request $request, 
        PaystackService $paystackService)
    {
        $user = $request->user();
        $carts = $user->carts;

        foreach($carts as $cart){
            $product = $cart->product;
            $products[] = $product->amount * $cart['quantity'];    
        }

        $amount = (int) array_sum($products) * 100;
        $transaction = $paystackService->getFactory()->transaction->initialize([
            'amount' => $amount,
            'email' => $user->email,
            'currency' => 'NGN',
            'callback_url' => $request->callbackUrl,
            'metadata' => [
                'user_id' => $user->id, // the user that initiated the payment
                // 'product_id' => $product->id,
                'payment_purpose' => PaymentPurpose::CHECKOUT, // the purpose of the payment
                'transactionable_id' => $cart->id,
                'transactionable_type' => get_class($user),
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

    /**
     * Checkout via wallet.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function checkoutViaWallet(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = $request->user();
            $carts = $user->carts;
            foreach($carts as $cart){
                $product = $cart->product;
                $products[] = $product->amount * $cart['quantity'];
            }
                $amount = (int) array_sum($products);
                $array['amount'] = $amount;
                $array['payment_purpose'] = PaymentPurpose::CHECKOUT;
                $array['metadata']['user_id'] = $user->id; // the user that initiated the payment
                $array['metadata']['payment_purpose'] = PaymentPurpose::CHECKOUT; // the purpose of the payment
                $array['metadata']['transactionable_id'] = $user->id;
                $array['metadata']['transactionable_type'] = get_class($user);
                $user->chargeWallet($user, $array);
                DB::commit();
                $transaction = $user->transactions->last();
                $cart =  $this->checkoutService->serve($transaction);
                return ResponseBuilder::asSuccess()
                    ->withHttpCode(Response::HTTP_CREATED)
                    ->withMessage('Payment made successfully')
                    ->withData(['Cart' => $cart])
                    ->build();
            } 
        
        catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Cart $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        $cart->delete();

        return ResponseBuilder::asSuccess()
            ->withMessage('Cart deleted successfully.')
            ->build();
    }
}
