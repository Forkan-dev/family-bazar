<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Actions\Order\Cart\CreateCart;
use App\Http\Requests\Order\CartItem\StoreCartItemRequest;

class CartController extends Controller
{

    public function addToCart(StoreCartItemRequest $request, CreateCart $createCart)
    {

        return $createCart->addToCart($request);
    }
}
