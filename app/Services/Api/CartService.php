<?php

namespace App\Services\Api;

use App\Http\Resources\Api\CartResource;
use App\Models\Cart;
use App\Services\Api\ApiResponseService;

class CartService
{
    protected ApiResponseService $apiResponseService;

    public function getCustomerCart()
    {
        $guest_id = session()->get('guest_id');

        if ($guest_id) {
            $cartItems =  Cart::where('guest_id', $guest_id)->get();
            return $this->apiResponseService->success(
                CartResource::collection($cartItems),
                'Customer cart retrieved successfully'
            );
        } else {
            $customer = auth('customer')->user();
            $cartItems =  Cart::where('customer_id', $customer->id)->get();
            return $this->apiResponseService->success(
                CartResource::collection($cartItems),
                'Customer cart retrieved successfully'
            );
        }
    }
}
