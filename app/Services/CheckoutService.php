<?php

namespace App\Services;
use App\Models\Transaction;
use App\Models\UserProduct;


class CheckoutService
{

     /**
     * Paystack initialization.
     *
     * @param Transaction $transaction
     * @return void
     */

    public static function serve(Transaction $transaction)
    {
        $user = $transaction->user;
        $cartItem = $user->carts;

        foreach ($cartItem as $cart){

            $userProducts = new UserProduct();
            $userProducts->transaction_id = $transaction->id;
            $userProducts->product_id = $cart->product_id;
            $userProducts->user_id = $cart->user_id;
            $userProducts->quantity = $cart->quantity;
            $userProducts->save();
            $cart->delete();
        }

        
    }
}
