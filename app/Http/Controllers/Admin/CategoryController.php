<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index()
    {
        $categories = Category::with('parent')->get();

        return Inertia::render('Admin/Category/index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return Inertia::render('Admin/Category/Create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title_en' => 'required|string|max:255',
            'title_bn' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'image' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        Category::create($request->all());

        return redirect()->route('categories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */


    public function edit(Category $category)
    {
        // সকল categories fetch করা, except current category (optional)
        $categories = Category::where('id', '!=', $category->id)->get();

        return Inertia::render('Admin/Category/Edit', [
            'category' => $category,
            'categories' => $categories, // ✅ এখানে পাঠানো হচ্ছে
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'title_en' => 'required|string|max:255',
            'title_bn' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'image' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $category->update($request->all());

        return redirect()->route('categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(Category $category)
    // {
    //     $category->delete();

    //     return redirect()->route('categories.index');
    // }

    public function destroy(Category $category)
    {
        $category->delete();

        // Ajax / Inertia request হলে JSON return করুন
        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('categories.index');
    }
}
