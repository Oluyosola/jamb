<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;

class OrderService
{
   

    /**
     * Store the specified resource in storage.
     *
     * @param FormRequest $request
     * @return Order $order
     */
    public function store(FormRequest $request)
    {
        $order = new Order();
        $orderOwner = $request->owner_id ?? $request->user()->id;
        $order->user_id = $orderOwner;
        $order->artisan_id = $request->artisan_id;
        $order->is_active = $request->is_active ? true : false;
        $order->save();
        return $order;
    }

}
