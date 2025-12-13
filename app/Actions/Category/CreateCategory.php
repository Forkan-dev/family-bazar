<?php

namespace App\Actions\Category;

use App\Http\Requests\Admin\Category\StoreCategoryRequest;
use App\Models\Product\Category;
use Illuminate\Support\Facades\DB;

class CreateCategory
{
    public function handle(StoreCategoryRequest $request): Category
    {

        $validatedData = $request->validated();

        // DB Transaction - যাতে সবকিছু একসাথে সফল হয় অথবা rollback হয়
        return DB::transaction(function () use ($validatedData, $request) {
            $category = Category::create($validatedData);

            // fix: pass $category (not $product)
            $imagePath = $this->storeImages($request, $category);

            if ($imagePath) {
                $category->update(['image' => $imagePath]);
            }

            return $category;
        });
    }

    private function storeImages(StoreCategoryRequest $request, Category $category): void
    {
        if (! $request->hasFile('image')) {
            return;
        }

        $image = $request->file('image');

        // Store image in 'public/categories' folder inside storage/app/public
        $path = $image->store('categories', 'public'); // storage disk 'public'

        // $path is like 'categories/uniquefilename.jpg'

        // Save in documents table
        $category->documents()->create([
            'file_path' => 'storage/'.$path, // use storage link path for browser
            'file_name' => $image->getClientOriginalName(),
            'mime_type' => $image->getClientMimeType(),
            'file_size' => $image->getSize(),
        ]);
    }
}
