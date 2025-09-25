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
    public function index()
    {
        $unions = Union::with('upazila.district.division')->get();

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
        return Inertia::render('Admin/Location/Create', [
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

        return redirect()->route('locations.index')->with('success', 'Union created successfully.');
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
        return Inertia::render('Admin/Location/Edit', [
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

        return redirect()->route('locations.index')->with('success', 'Union updated successfully!');
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

        return redirect()->route('locations.index');
    }
}
