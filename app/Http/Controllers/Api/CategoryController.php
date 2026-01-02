<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Product\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CategoryResource;
use App\Http\Resources\Api\SelectTagResource;
use App\Http\Resources\Api\SidebarResource;

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
    public function sidebarCategories($categoryId=1)
    {
        $categories = Category::where('parent_id', $categoryId)->with('children')->get();
        return SidebarResource::collection($categories);
    }
}
