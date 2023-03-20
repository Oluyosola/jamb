<?php

namespace App\Services;

use App\Interfaces\ModelInterface;
use App\Models\Cart;

class CartService implements ModelInterface
{
    /**
     * Fetch all carts query.
     *
     * @return \Illuminate\Database\Eloquent\Builder[]
     */
    public function index()
    {
        return Cart::query();
    }

    /**
     * Store a cart
     *
     * @param $request
     * @return Cart $cart
     */
    public function store($request): Cart
    {
        // check if product exists in user's cart before creating it else, increase the product quantity
        $cart = Cart::where('user_id', $request->user()->id)
            ->where('product_id', $request->product_id)
            ->first();

        // if cart exists, Just increment the quantity
        if (!is_null($cart)) {
            (int) $cart->quantity += $request->quantity;
            $cart->save();

            return $cart;
        }

        $cart = new Cart();
        $cart->user_id = $request->user('user')->id;
        $cart->product_id = $request->product_id;
        $cart->quantity = $request->quantity;
        $cart->save();

        return $cart;
    }

    /**
     * Update a cart.
     *
     * @param $request
     * @param \Illuminate\Foundation\Http\FormRequest $request
     * @return Cart $cart
     */
    public function update($request, $cart): Cart
    {
        $cart->product_id = $request->product_id;
        $cart->quantity = $request->quantity;
        $cart->save();

        return $cart;
    }
}
