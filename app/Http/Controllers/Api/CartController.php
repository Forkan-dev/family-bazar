<?php

namespace App\Http\Controllers\Api;

use App\Actions\Order\Cart\CreateCart;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CartItem\StoreCartItemRequest;
use App\Services\Api\CartService;

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
       return $this->cartAction->addToCart($request);
    }

    public function index()
    {
        $this->cartService->getCustomerCart();
    }
}
