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
            return ApiResponse::success(
                CartResource::collection($cartItems),
                'Customer cart retrieved successfully'
            );
        } else {
            $customer = auth('customer')->user();
            $cartItems =  Cart::where('customer_id', $customer->id)->get();
            return ApiResponse::success(
                CartResource::collection($cartItems),
                'Customer cart retrieved successfully'
            );
        }
    }
}
