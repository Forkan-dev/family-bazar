<?php

namespace App\Services\Payment\Strategies;

use App\Models\Order\Order;
use App\Services\Payment\PaymentStrategyInterface;

class StripeStrategy implements PaymentStrategyInterface
{
    /**
     * Process Stripe payment
     *
     * @param Order $order
     * @param array $paymentData
     * @return array
     */
    public function processPayment(Order $order, array $paymentData): array
    {
        // TODO: Implement Stripe payment gateway integration
        // This is a placeholder for future implementation

        return [
            'status' => 'pending',
            'payment_method' => 'stripe',
            'transaction_id' => null,
            'paid_amount' => 0,
            'message' => 'Stripe payment processing not implemented yet.',
        ];
    }

    /**
     * Get the payment method name
     *
     * @return string
     */
    public function getPaymentMethod(): string
    {
        return 'stripe';
    }
}
