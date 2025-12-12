<?php

namespace App\Http\Controllers;

use ApiResponse;
use App\Http\Requests\Api\OrderRequest;
use App\Services\Order\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Process checkout and create order
     *
     * @param OrderRequest $request
     * @return JsonResponse
     */
    public function checkout(OrderRequest $request): JsonResponse
    {
        try {
            $customerId = auth()->id();

            $order = $this->orderService->createOrder(
                $request->validated(),
                $customerId
            );

            return ApiResponse::success(
                [
                    'order' => $order,
                    'order_id' => $order->id,
                    'total_amount' => $order->total_amount,
                    'payment_status' => $order->payment_status,
                ],
                'Order placed successfully'
            );
        } catch (\Exception $e) {
            Log::error('Checkout failed: ' . $e->getMessage(), [
                'customer_id' => auth()->id(),
                'request_data' => $request->validated(),
                'exception' => $e,
            ]);

            return ApiResponse::error(
                $e->getMessage(),
                null,
                500
            );
        }
    }
}
