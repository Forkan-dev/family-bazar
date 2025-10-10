<?php

namespace App\Actions\Product;

use App\Models\Product\Product;
use App\Http\Requests\Admin\Product\StoreProductRequest;

class CreateProduct
{
    public function handle(StoreProductRequest $request): Product
    {
        $validatedData = $request->validated();
        $product = Product::create($validatedData);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->documents()->create([
                    'file_path' => $path,
                    'file_name' => $image->getClientOriginalName(),
                    'mime_type' => $image->getClientMimeType(),
                    'file_size' => $image->getSize(),
                ]);
            }
        }

        $product->tags()->sync($request->input('tags', []));

        return $product;
    }
}
