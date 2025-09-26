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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // ✅ validate file
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/category'), $imageName);

            // Save relative path to DB
            $data['image'] = 'images/category/' . $imageName;
        }

        Category::create($data);

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
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
    // public function update(Request $request, Category $category)
    // {
    //     $request->validate([
    //         'title_en' => 'required|string|max:255',
    //         'title_bn' => 'required|string|max:255',
    //         'slug' => 'required|string|max:255|unique:categories,slug,' . $category->id,
    //         'description' => 'nullable|string',
    //         'icon' => 'nullable|string',
    //         'image' => 'nullable|string',
    //         'parent_id' => 'nullable|exists:categories,id',
    //     ]);

    //     $category->update($request->all());

    //     return redirect()->route('categories.index');
    // }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'title_en' => 'required|string|max:255',
            'title_bn' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // validate image
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/category'), $filename);

            if ($category->image && file_exists(public_path($category->image))) {
                unlink(public_path($category->image));
            }

            $data['image'] = 'images/category/' . $filename;
        }

        $category->update($data);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
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
