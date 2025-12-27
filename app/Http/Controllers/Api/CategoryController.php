<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\SelectTagResource;
use App\Models\Product\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function categoryDropdown(Request $request)
    {
        $param = $request->get('q', '');
        if (empty($param)) {
            $products = Category::get();
            return SelectTagResource::collection($products);
        } else {
            $products = Category::select('id', 'title_en')->where('title_en', 'like', "%$param%")->get();
            return SelectTagResource::collection($products);
        }
    }
}
