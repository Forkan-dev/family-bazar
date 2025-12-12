<?php

namespace App\Actions\Product;

use App\Http\Requests\Admin\Product\StoreProductRequest;
use App\Models\Product\Product;
use App\Models\Product\Tag;

class CreateProduct
{
    public function handle(StoreProductRequest $request): Product
    {
        $validatedData = $request->validated();
        $product = Product::create($validatedData);

        $this->storeImages($request, $product);
        $this->syncTags($request, $product);

        return $product;
    }

    private function storeImages(StoreProductRequest $request, Product $product): void
    {
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
    }

    private function syncTags(StoreProductRequest $request, Product $product): void
    {
        $tags = collect($request->input('tags', []))->map(function ($tag) {
            if (is_numeric($tag)) {
                return $tag;
            }

            return Tag::firstOrCreate(['name' => $tag], ['slug' => \Illuminate\Support\Str::slug($tag)])->id;
        });

        $product->tags()->sync($tags);
    }
}
