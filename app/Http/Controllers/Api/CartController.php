<?php

namespace App\Http\Controllers\Api;

use App\Actions\Order\Cart\CreateCart;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CartItem\StoreCartItemRequest;
use App\Services\Api\CartService;
use Illuminate\Http\Request;
use ApiResponse;
use App\Http\Resources\Api\CartItemResource;


class CartController extends Controller
{
    protected  $cartService;
    protected  $cartAction;

    public function __construct(CartService $cartService, CreateCart $cartAction)
    {
        $this->cartService = $cartService;
        $this->cartAction = $cartAction;
    }
    public function store(StoreCartItemRequest $request)
    {
        try {
            $data =  $this->cartAction->addToCart($request);
            return ApiResponse::success(
                [
                    'guest_id'  => $data['guest_id'] ?? null,
                    'cart_item' => new CartItemResource($data['cart_item']),
                ],
                'Product added to cart successfully'
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                'Add to cart failed: ' . $e->getMessage(),
                null,
                500
            );
        }
    }

    public function index(Request $request)
    {
        try {
            $guest_id = $request->input('guest_id');
            $cartItems =  $this->cartService->getCustomerCart($guest_id);
            return ApiResponse::success(
                CartItemResource::collection($cartItems),
                'Customer cart retrieved successfully'
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                'Retrieve cart failed: ' . $e->getMessage(),
                null,
                500
            );
        }
    }
    public function mergeCart(Request $request)
    {
        try {
            $guest_id = $request->input('guest_id');
            $customer_id = auth('customer')->id();
            $this->cartAction->mergeGuestCart($guest_id, $customer_id);
            return ApiResponse::success(
                null,
                'Guest cart merged successfully'
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                'Merge failed: Guest ID or Customer not authenticated',
                null,
                400
            );
        }
    }
}
