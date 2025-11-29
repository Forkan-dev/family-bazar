<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Category\CreateCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\StoreCategoryRequest;
use App\Models\Product\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryService $categoryService
    ) {}

    public function index(Request $request)
    {
        $categories = $this->categoryService->getPaginatedCategories($request);

        return Inertia::render('Admin/Category/index', [
            'categories' => $categories,
        ]);
    }

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
    public function store(StoreCategoryRequest $request, CreateCategory $createCategory)
    {

        try {
            DB::beginTransaction();
            $createCategory->handle($request);
            DB::commit();

            return redirect()
                ->route('product.categories.index')
                ->with('success', 'Category created successfully.');
        } catch (\Exception $e) {

            DB::rollBack();
            // dd($e->getMessage());

            return redirect()
                ->back()
                ->withErrors(['error' => 'An error occurred while creating the category.'])
                ->withInput();
        }
    }

    public function show(Category $category)
    {
        //
    }

    public function edit(Category $category)
    {
        // সকল categories fetch করা, except current category (optional)
        $categories = Category::where('id', '!=', $category->id)->get();

        return Inertia::render('Admin/Category/Edit', [
            'category' => $category,
            'categories' => $categories,
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
            'slug' => 'required|string|max:255|unique:categories,slug,'.$category->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // validate image
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('images/category'), $filename);

            if ($category->image && file_exists(public_path($category->image))) {
                unlink(public_path($category->image));
            }

            $data['image'] = 'images/category/'.$filename;
        }

        $category->update($data);

        return redirect()->route('product.categories.index')->with('success', 'Category updated successfully!');
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

        return redirect()->route('product.categories.index');
    }
}
