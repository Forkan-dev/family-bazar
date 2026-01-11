<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ProductResource;
use App\Http\Resources\Api\SelectTagResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected  $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = $this->productService->getPaginatedProducts($request, null, true);
        return ProductResource::collection($products);
    }

    /**
     * Show the form for creating a new resource.
     */
   

    public function productDropdown(Request $request)
    {
        $param = $request->get('q', '');
        if (empty($param)) {
            $products = Product::get();
            return SelectTagResource::collection($products);
        } else {
            $products = Product::select('id', 'name_en')->where('name_en', 'LIKE',
             $param . '%')->get();
            return SelectTagResource::collection($products);
        }
    }
}
