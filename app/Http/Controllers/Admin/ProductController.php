<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Product\Tag;
use App\Models\Product\Unit;
use Illuminate\Http\Request;
use App\Models\Product\Brand;
use App\Models\Product\Product;
use App\Models\Product\Category;
use App\Http\Controllers\Controller;
use App\Actions\Product\CreateProduct;
use App\Actions\Product\UpdateProduct;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\Product\StoreProductRequest;
use App\Http\Requests\Admin\Product\UpdateProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['category', 'tags'])->get();
        return Inertia::render('Admin/Products/Index', [
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::whereNull('parent_id')->get();
        $tags = Tag::all();
        $units = Unit::all();
        $brands = Brand::all();
        return Inertia::render('Admin/Products/Form', [
            'categories' => $categories,
            'tags' => $tags,
            'units' => $units,
            'brands' => $brands,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request, CreateProduct $createProduct)
    {
        $createProduct->handle($request);

        return redirect()->route('product.products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // Not typically used for a resource controller in an admin panel
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::whereNull('parent_id')->get();
        $tags = Tag::all();
        $units = Unit::all();
        $brands = Brand::all();
        $product->load('category', 'tags', 'documents');

        return Inertia::render('Admin/Products/Form', [
            'product' => $product,
            'categories' => $categories,
            'tags' => $tags,
            'units' => $units,
            'brands' => $brands,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product, UpdateProduct $updateProduct)
    {
        $updateProduct->handle($request, $product);

        return redirect()->route('product.products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    public function getSubCategories(Request $request, $id)
    {
        $subcategories = Category::where('parent_id', $id)->get();
        return response()->json($subcategories);
    }
}
