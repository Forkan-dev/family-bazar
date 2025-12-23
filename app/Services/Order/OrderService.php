<?php

namespace App\Services\Order;

use App\Models\Cart;
use App\Models\Order\Order;
use App\Models\Order\OrderItem;
use App\Models\Product\Product;
use App\Models\Customer\CustomerAddress;
use App\Services\Payment\PaymentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Create order from checkout request
     *
     * @param array $data
     * @param int $customerId
     * @return Order
     * @throws \Exception
     */
    public function createOrder(array $data, int $customerId): Order
    {
        return DB::transaction(function () use ($data, $customerId) {
            // Step 1: Validate cart and check stock
            $cart = $this->validateCart($data['cart_id'], $customerId);

            // Step 2: Validate customer address
            $address = $this->validateAddress($data['customer_address_id'], $customerId);

            // Step 3: Calculate totals
            $totals = $this->calculateTotals($cart, $data['coupon_code'] ?? null);

            // Step 4: Create order record
            $order = $this->createOrderRecord($cart, $address, $totals, $data);

            // Step 5: Create order items
            $this->createOrderItems($order, $cart);

            // Step 6: Process payment
            // $paymentResult = $this->processPayment($order, $data['payment_method']);

            // Step 7: Update order with payment status
            $order->update([
                // 'payment_status' => $paymentResult['status'],
                'payment_status' => 'pending',
            ]);

            // Step 8: Update product inventory
            // $this->updateInventory($cart);

            // Step 9: Clear cart
            $this->clearCart($cart);

            // Step 10: Send notifications (can be queued)
            $this->sendNotifications($order);

            return $order->fresh(['orderItems']);
        });
    }

    /**
     * Validate cart and check stock availability
     *
     * @param int $cartId
     * @param int $customerId
     * @return Cart
     * @throws \Exception
     */
    protected function validateCart(int $cartId, int $customerId): Cart
    {
        $cart = Cart::with('cartItems.product')->find($cartId);

        if (!$cart) {
            throw new \Exception('Cart not found.');
        }

        if ($cart->customer_id != $customerId) {
            throw new \Exception('Unauthorized cart access.');
        }

        if ($cart->cartItems->isEmpty()) {
            throw new \Exception('Cart is empty.');
        }

        // Check stock for each item
        foreach ($cart->cartItems as $item) {
            if (!$item->product) {
                throw new \Exception("Product not found for cart item.");
            }

            // if ($item->product->stock < $item->quantity) {
            //     throw new \Exception("Insufficient stock for product: {$item->product->name}");
            // }
        }

        return $cart;
    }

    /**
     * Validate customer address
     *
     * @param int $addressId
     * @param int $customerId
     * @return CustomerAddress
     * @throws \Exception
     */
    protected function validateAddress(int $addressId, int $customerId): CustomerAddress
    {
        $address = CustomerAddress::find($addressId);

        if (!$address) {
            throw new \Exception('Address not found.');
        }

        if ($address->customer_id != $customerId) {
            throw new \Exception('Unauthorized address access.');
        }

        return $address;
    }

    /**
     * Calculate order totals
     *
     * @param Cart $cart
     * @param string|null $couponCode
     * @return array
     */
    protected function calculateTotals(Cart $cart, ?string $couponCode = null): array
    {
        $subtotal = $cart->cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $discount = 0;
        $couponId = null;

        // Apply coupon if provided
        if ($couponCode) {
            // TODO: Implement coupon logic when Coupon model exists
            // $coupon = Coupon::where('code', $couponCode)->first();
            // if ($coupon && $coupon->isValid()) {
            //     $discount = $coupon->calculateDiscount($subtotal);
            //     $couponId = $coupon->id;
            // }
        }

        // Calculate tax will be here(example: 5% tax)

        // Calculate shipping (example: flat rate)
        $shipping = 50.00;

        $total = $subtotal - $discount + $shipping;

        return [
            'subtotal' => round($subtotal, 2),
            'discount' => round($discount, 2),
            'shipping' => round($shipping, 2),
            'total' => round($total, 2),
            'coupon_id' => $couponId,
        ];
    }

    /**
     * Create order record
     *
     * @param Cart $cart
     * @param CustomerAddress $address
     * @param array $totals
     * @param array $data
     * @return Order
     */
    protected function createOrderRecord(Cart $cart, CustomerAddress $address, array $totals, array $data): Order
    {
        return Order::create([
            'customer_id' => $cart->customer_id,
            'customer_address_id' => $address->id,
            'delivery_address' => $this->formatAddressArray($address),
            'zone_id' => $address->zone_id,
            'total_amount' => $totals['total'],
            'discount_amount' => $totals['discount'],
            'payment_method' => $data['payment_method'],
            'payment_status' => 'pending',
            'status' => 'pending',
            'notes' => $data['notes'] ?? null,
            'offer_id' => $totals['coupon_id'],
        ]);
    }

    /**
     * Format address as string for legacy support
     *
     * @param CustomerAddress $address
     * @return string
     */
    protected function formatAddress(CustomerAddress $address): string
    {
        return $address->address_line . ', ' .
               $address->state . ' ' .
               $address->postal_code;
    }

    /**
     * Format address as array for JSON storage
     *
     * @param CustomerAddress $address
     * @return array
     */
    protected function formatAddressArray(CustomerAddress $address): array
    {
        return [
            'address_line' => $address->address_line,
            'state' => $address->state,
            'postal_code' => $address->postal_code,
            'phone' => $address->phone,
            'zone_id' => $address->zone_id,
        ];
    }

    /**
     * Create order items from cart
     *
     * @param Order $order
     * @param Cart $cart
     * @return void
     */
    protected function createOrderItems(Order $order, Cart $cart): void
    {
        foreach ($cart->cartItems as $cartItem) {
            $totalPrice = $this->calculateTotalPrice($cartItem);
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'unit_price' => $cartItem->price,
                'total_price' => $cartItem->price * $cartItem->quantity,
            ]);
        }
    }

    protected function calculateTotalPrice($cartItem)
    {
        return $cartItem->price * $cartItem->quantity;
    }

    /**
     * Process payment using payment service
     *
     * @param Order $order
     * @param string $paymentMethod
     * @return array
     * @throws \Exception
     */
    protected function processPayment(Order $order, string $paymentMethod): array
    {
        return $this->paymentService->processPayment($paymentMethod, $order);
    }

    /**
     * Update product inventory
     *
     * @param Cart $cart
     * @return void
     */
    protected function updateInventory(Cart $cart): void
    {
        foreach ($cart->cartItems as $item) {
            $product = $item->product;
            $product->decrement('stock', $item->quantity);
        }
    }

    /**
     * Clear cart after order is placed
     *
     * @param Cart $cart
     * @return void
     */
    protected function clearCart(Cart $cart): void
    {
        $cart->cartItems()->delete();
        // Optionally delete the cart itself
        $cart->delete();
    }

    /**
     * Send notifications to customer and admin
     *
     * @param Order $order
     * @return void
     */
    protected function sendNotifications(Order $order): void
    {
        // TODO: Implement notification logic
        // This can be dispatched as a job for better performance
        // dispatch(new SendOrderConfirmationEmail($order));
        // dispatch(new SendOrderNotificationToAdmin($order));

        Log::info("Order #{$order->id} placed successfully. Notifications should be sent.");
    }
}
