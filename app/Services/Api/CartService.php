<?php

namespace App\Services\Api;

use App\Http\Resources\Api\CartResource;
use App\Models\Cart;
use ApiResponse;

class CartService
{

    public function getCustomerCart($guest_id)
    {

        if ($guest_id) {
            $cartItems =  Cart::where('guest_id', $guest_id)->with('cartItems')->get();
            return $cartItems;
           
        } else {
            $customer = auth('customer')->user();
            $cartItems =  Cart::where('customer_id', $customer->id)->get();
            return $cartItems;
        }
    }
}
