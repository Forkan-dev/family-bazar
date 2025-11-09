<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Type;
use App\Actions\Banner\CreateBanner;
use App\Actions\Banner\UpdateBanner;
use App\Http\Requests\Admin\Banner\StoreBannerRequest;
use App\Http\Requests\Admin\Banner\UpdateBannerRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::with('type', 'documents')->get()->map(function ($banner) {
            $banner->image = $banner->documents->first() ? $banner->documents->first()->url : null;
            return $banner;
        });

        return Inertia::render('Admin/Banner/Index', [
            'banners' => $banners,
        ]);
    }

    public function create()
    {
        $types = Type::all();
        return Inertia::render('Admin/Banner/Create', [
            'types' => $types,
        ]);
    }

    public function store(StoreBannerRequest $request, CreateBanner $createBanner)
    {
        try {
            DB::beginTransaction();
            $createBanner->handle($request);
            DB::commit();
            return redirect()
                ->route('admin.banners.index')
                ->with('success', 'Banner created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withErrors(['error' => 'An error occurred while creating the banner.'])
                ->withInput();
        }
    }

    public function edit(Banner $banner)
    {
        $banner->load('documents');
        $banner->image = $banner->documents->first() ? $banner->documents->first()->url : null;
        $types = Type::all();
        return Inertia::render('Admin/Banner/Edit', [
            'banner' => $banner,
            'types' => $types,
        ]);
    }

    public function update(UpdateBannerRequest $request, Banner $banner, UpdateBanner $updateBanner)
    {
        try {
            DB::beginTransaction();
            $updateBanner->handle($request, $banner);
            DB::commit();
            return redirect()
                ->route('admin.banners.index')
                ->with('success', 'Banner updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withErrors(['error' => 'An error occurred while updating the banner.'])
                ->withInput();
        }
    }

    public function destroy(Banner $banner)
    {
        $banner->load('documents'); // Load the associated documents

        foreach ($banner->documents as $document) {
            // Delete the physical file from storage
            if (Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }
            // Delete the document record from the database
            $document->delete();
        }

        $banner->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.banners.index');
    }
}
