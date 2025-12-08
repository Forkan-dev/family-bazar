<?php

namespace App\Actions\Order\Cart;

use App\Http\Requests\Order\CartItem\StoreCartItemRequest;
use App\Models\CartHistory;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CreateCart
{
    public function addToCart(StoreCartItemRequest $request)
    {
        $guestId = session()->get('guest_id');

        if (! $guestId) {
            $guestId = (string) \Illuminate\Support\Str::uuid();
            session()->put('guest_id', $guestId);
        }

        // Determine owner
        $ownerColumn = Auth::check() ? 'user_id' : 'guest_id';
        $ownerId = Auth::check() ? Auth::id() : $guestId;

        // Add or update cart item
        $this->cartItemStore($request, $ownerColumn, $ownerId);

        // Save history
        $this->cartHistoryStore($request, $ownerColumn, $ownerId);

        return response()->json(['message' => 'Added to cart']);
    }

    private function cartItemStore(StoreCartItemRequest $request, string $ownerColumn, $ownerId)
    {

        $product_price = Product::find($request->product_id)->price;

        CartItem::updateOrCreate(
            [
                $ownerColumn => $ownerId,
                'product_id' => $request->product_id,
            ],
            [
                'quantity' => $request->quantity,
                'price' => $product_price,
                'options' => $request->options,
            ]
        );
    }

    private function cartHistoryStore(StoreCartItemRequest $request, string $ownerColumn, $ownerId)
    {
        $product_price = Product::find($request->product_id)->price;
        CartHistory::create([
            $ownerColumn => $ownerId,
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

        if (! $guestId) {
            return;
        }

        $guestItems = CartItem::where('guest_id', $guestId)->get();

        foreach ($guestItems as $item) {
            // Merge into user cart
            CartItem::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'product_id' => $item->product_id,
                ],
                [
                    'quantity' => \DB::raw("quantity + {$item->quantity}"),
                    'price' => $item->price,
                    'options' => $item->options,
                ]
            );

            // Delete guest item
            $item->delete();
        }

        // Remove guest session
        session()->forget('guest_id');
    }
}
