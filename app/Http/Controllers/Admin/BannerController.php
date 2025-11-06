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
        $banners = Banner::with('type')->get();

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
        if ($banner->image && file_exists(public_path($banner->image))) {
            unlink(public_path($banner->image));
        }

        $banner->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.banners.index');
    }
}
