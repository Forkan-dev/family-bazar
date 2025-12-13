<?php

namespace App\Services\Payment;

use App\Models\Order\Order;
use App\Services\Payment\Strategies\CashOnDeliveryStrategy;
use App\Services\Payment\Strategies\StripeStrategy;

class PaymentService
{
    /**
     * @var array<string, PaymentStrategyInterface>
     */
    private array $strategies = [];

    public function __construct()
    {
        $this->registerStrategies();
    }

    /**
     * Register all payment strategies
     */
    private function registerStrategies(): void
    {
        $this->strategies['cod'] = new CashOnDeliveryStrategy();
        $this->strategies['stripe'] = new StripeStrategy();
        // Add more payment strategies here as needed
        // $this->strategies['card'] = new CardStrategy();
        // $this->strategies['wallet'] = new WalletStrategy();
    }

    /**
     * Process payment using the appropriate strategy
     *
     * @param string $paymentMethod
     * @param Order $order
     * @param array $paymentData
     * @return array
     * @throws \Exception
     */
    public function processPayment(string $paymentMethod, Order $order, array $paymentData = []): array
    {
        if (!isset($this->strategies[$paymentMethod])) {
            throw new \Exception("Payment method '{$paymentMethod}' is not supported.");
        }

        $strategy = $this->strategies[$paymentMethod];
        return $strategy->processPayment($order, $paymentData);
    }

    /**
     * Check if a payment method is supported
     *
     * @param string $paymentMethod
     * @return bool
     */
    public function isPaymentMethodSupported(string $paymentMethod): bool
    {
        return isset($this->strategies[$paymentMethod]);
    }

    /**
     * Get all supported payment methods
     *
     * @return array<string>
     */
    public function getSupportedPaymentMethods(): array
    {
        return array_keys($this->strategies);
    }
}
