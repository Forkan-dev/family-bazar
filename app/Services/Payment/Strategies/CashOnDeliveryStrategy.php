<?php

namespace App\Services\Payment\Strategies;

use App\Models\Order\Order;
use App\Services\Payment\PaymentStrategyInterface;

class CashOnDeliveryStrategy implements PaymentStrategyInterface
{
    /**
     * Process COD payment
     *
     * @param Order $order
     * @param array $paymentData
     * @return array
     */
    public function processPayment(Order $order, array $paymentData): array
    {
        return [
            'status' => 'pending',
            'payment_method' => 'cod',
            'transaction_id' => null,
            'paid_amount' => 0,
            'message' => 'Order placed successfully. Pay cash on delivery.',
        ];
    }

    /**
     * Get the payment method name
     *
     * @return string
     */
    public function getPaymentMethod(): string
    {
        return 'cod';
    }
}
