<?php

namespace App\Actions\Order\Cart;

use App\Models\CartItem;
use App\Models\CartHistory;
use App\Http\Requests\Order\CartItem\StoreCartItemRequest;
use App\Http\Resources\Api\CartItemResource;
use App\Models\Cart;
use App\Models\Product;
use App\Traits\CheckAuthTrait;
use ApiResponse;
use stdClass;
use App\Http\Requests\Order\CartItem\StoreCartItemRequest;
use App\Models\CartHistory;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CreateCart
{
    use CheckAuthTrait;

    public function addToCart(StoreCartItemRequest $request)
    {
        $authenticated = $this->isAuthenticated('customer');
        $guest_id = $request->guest_id;
        if (!$authenticated && !$guest_id) {
            $guest_id = (string) \Illuminate\Support\Str::uuid();
        }

        // Add or update cart item
        $column = $authenticated ? 'customer_id' : 'guest_id';
        $c_id = $authenticated ? $this->getAuthenticatedUserId('customer') : $guest_id;
        $cartItem = $this->storeItemToCart($request, $column, $c_id);
        return [
            'guest_id' => $guest_id,
            'cart_item' => $cartItem,
        ];
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

        $cartItem = CartItem::where('cart_id', data_get($cart, 'id'))
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            // Increment quantity
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // Create new cart item
            $cartItem = CartItem::create([
                'cart_id' => data_get($cart, 'id'),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'price' => $product_price,
            ]);
        }
        return $cartItem;
    }

    private function cartHistoryStore(StoreCartItemRequest $request, string $user_column, $c_id)
    {
        // will create later
    }


    public function mergeGuestCart($guest_id, $customer_id)
    {

        if (!$guest_id) return;

        $cart = Cart::where('guest_id', $guest_id)->get();

        if ($cart->isEmpty()) return;
        // Get or create the authenticated user's cart
        $userCart = Cart::firstOrCreate(
            ['customer_id' => $customer_id]
        );

        $guestItems = CartItem::whereIn('cart_id', $cart->pluck('id'))->get();

        foreach ($guestItems as $item) {

            $userItem = CartItem::firstOrCreate(
                [
                    'cart_id' => $userCart->id,
                    'product_id' => $item->product_id,
                ],
                [

                    'price'    => $item->price,
                    'options'  => $item->options,
                ]
            );

            // atomic increment
            $userItem->increment('quantity', $item->quantity);

        }
        Cart::where('guest_id', $guest_id)->delete();


    }

}
