<?php

namespace App\Actions\Order\Cart;

use App\Facades\ResponseFacade;
use App\Models\CartItem;
use App\Models\CartHistory;
use App\Http\Requests\Order\CartItem\StoreCartItemRequest;
use App\Http\Resources\Api\CartItemResource;
use App\Models\Cart;
use App\Models\Product;
use App\Services\Api\ApiResponseService;
use App\Traits\CheckAuthTrait;
use ApiResponse;

class CreateCart
{
    use CheckAuthTrait;
    protected ApiResponseService $apiResponseService;

    public function __construct(ApiResponseService $apiResponseService)
    {
        $this->apiResponseService = $apiResponseService;
    }

    public function addToCart(StoreCartItemRequest $request)
    {
        $authenticated = $this->isAuthenticated('customer');
        $guest_id = null;
        if (!$authenticated) {
            $guest_id = (string) \Illuminate\Support\Str::uuid();
        }

        // Add or update cart item
        $column = $authenticated ? 'customer_id' : 'guest_id';
        $c_id = $authenticated ? $this->getAuthenticatedUserId('customer') : $guest_id;
        $cartItem = $this->storeItemToCart($request, $column, $c_id);
        return ApiResponse::success(
            new CartItemResource($cartItem),
            'Product added to cart successfully'
        );
        // return $this->apiResponseService->success(
        //     new CartItemResource($cartItem),
        //     'Product added to cart successfully'
        // );
    }


    private function storeItemToCart(StoreCartItemRequest $request, string $user_column, $c_id)
    {

        $product_price = Product::find($request->product_id)->price;

        $cart = Cart::where($user_column, $c_id)->first();
        if (!$cart) {

            $cart = Cart::create([
                $user_column => $c_id,
            ]);
        }


        $cartItem =  CartItem::updateOrCreate(
            [
                'cart_id' =>  data_get($cart, 'id'),
                'product_id' => $request->product_id,
            ],
            [
                'quantity' => $request->quantity,
                'price' => $product_price,
            ]
        );
        return $cartItem;
    }

    private function cartHistoryStore(StoreCartItemRequest $request, string $user_column, $c_id)
    {
        $product_price = Product::find($request->product_id)->price;
        CartHistory::create([
            $user_column => $c_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $product_price,
            'options' => $request->options,
            'action' => 'added',
            // 'expires_at' => now()->addMonth(),
            'expires_at' => now()->addMinute(),

        ]);
    }


    public function mergeGuestCart()
    {
        $guestId = session()->get('guest_id');

        if (!$guestId) return;

        $guestItems = CartItem::where('guest_id', $guestId)->get();

        foreach ($guestItems as $item) {
            // Merge into user cart
            CartItem::updateOrCreate(
                [
                    'user_id' => $this->getAuthenticatedUserId('customer'),
                    'product_id' => $item->product_id
                ],
                [
                    'quantity' => \DB::raw("quantity + {$item->quantity}"),
                    'price' => $item->price,
                    'options' => $item->options
                ]
            );

            // Delete guest item
            $item->delete();
        }

        // Remove guest session
        session()->forget('guest_id');
    }

    public function getCartItems()
    {
        if ($this->isAuthenticated('customer')) {
            $c_id = $this->getAuthenticatedUserId('customer');
            return CartItem::where('customer_id', $c_id)->with('product')->get();
        } else {
            $guestId = session()->get('guest_id');
            return CartItem::where('guest_id', $guestId)->with('product')->get();
        }
    }
}
