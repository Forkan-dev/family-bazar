<?php

namespace App\Services\Payment;

use App\Models\Order\Order;

interface PaymentStrategyInterface
{
    /**
     * Process payment for an order
     *
     * @param Order $order
     * @param array $paymentData
     * @return array
     */
    public function processPayment(Order $order, array $paymentData): array;

    /**
     * Get the payment method name
     *
     * @return string
     */
    public function getPaymentMethod(): string;
}
