<?php

namespace App\Actions\Shop;

use App\Models\Shop\Shop;
use App\Http\Requests\Admin\Shop\UpdateShopRequest;

class UpdateShop
{
    public function handle(UpdateShopRequest $request, Shop $shop): Shop
    {
        $validatedData = $request->validated();

        $shop->update($validatedData);

        return $shop;
    }
}
