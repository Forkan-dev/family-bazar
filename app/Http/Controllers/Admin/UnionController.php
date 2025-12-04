<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Union;
use App\Models\Upazila;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UnionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Union::with('upazila.district.division');

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_en', 'like', "%{$search}%")
                  ->orWhere('name_bn', 'like', "%{$search}%")
                  ->orWhereHas('upazila', function ($q) use ($search) {
                      $q->where('name_en', 'like', "%{$search}%");
                  });
            });
        }

        // Sorting functionality
        if ($request->has('sort')) {
            $direction = $request->get('direction', 'asc');
            $query->orderBy($request->sort, $direction);
        } else {
            $query->latest();
        }

        $unions = $query->paginate(10);

        return Inertia::render('Admin/Location/index', [
            'unions' => $unions,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $upazilas = Upazila::all();
        return Inertia::render('Admin/Location/Form', [
            'upazilas' => $upazilas,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'upazila_id' => 'required|exists:upazilas,id',
            'name_en' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
        ]);

        Union::create($request->all());

        return redirect()->route('product.locations.index')->with('success', 'Union created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Union $union)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id) // Change to accept ID instead of Union model
    {
        $union = Union::findOrFail($id); // Explicitly find the Union by ID
        $union->loadMissing(['upazila']); // Load upazila if not already loaded

        $upazilas = Upazila::all();
        return Inertia::render('Admin/Location/Form', [
            'union' => $union->only(['id', 'upazila_id', 'name_en', 'name_bn']),
            'upazilas' => $upazilas,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) // Change to accept ID instead of Union model
    {
        $union = Union::findOrFail($id); // Explicitly find the Union by ID

        $request->validate([
            'upazila_id' => 'required|exists:upazilas,id',
            'name_en' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
        ]);

        $union->update($request->all());

        return redirect()->route('product.locations.index')->with('success', 'Union updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Union $union)
    {
        $union->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('product.locations.index');
    }
}
