<?php

namespace App\Actions\Shop;

use App\Models\Shop\Shop;
use App\Http\Requests\Admin\Shop\StoreShopRequest;

class CreateShop
{
    public function handle(StoreShopRequest $request): Shop
    {
        $validatedData = $request->validated();

        // Set defaults if not provided
        $validatedData['is_commission_based'] = $request->input('is_commission_based', true);
        $validatedData['status'] = $request->input('status', true);

        $shop = Shop::create($validatedData);

        return $shop;
    }
}
