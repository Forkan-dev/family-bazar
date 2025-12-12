<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Shop\CreateShop;
use App\Actions\Shop\UpdateShop;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Shop\StoreShopRequest;
use App\Http\Requests\Admin\Shop\UpdateShopRequest;
use App\Models\Shop\Shop;
use App\Services\Shop\ShopOwnerService;
use App\Services\Shop\ShopService;
use App\Services\Zone\ZoneService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ShopController extends Controller
{
    public function __construct(
        private ShopService $shopService,
        private ShopOwnerService $shopOwnerService,
        private ZoneService $zoneService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Inertia\Response
    {
        $shops = $this->shopService->getPaginatedShops($request);

        return Inertia::render('Admin/Shops/Index', [
            'shops' => $shops,
            'filters' => $request->only(['search', 'sort', 'direction', 'type', 'status', 'area_id']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $initialZones = $this->zoneService->getInitialZones();
        $initialShopOwners = $this->shopOwnerService->getInitialShopOwners();

        return Inertia::render('Admin/Shops/Form', [
            'initialZones' => $initialZones,
            'initialShopOwners' => $initialShopOwners,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShopRequest $request, CreateShop $createShop)
    {
        try {
            DB::beginTransaction();
            $createShop->handle($request);
            DB::commit();

            return redirect()->route('admin.shops.index')->with('success', 'Shop created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['error' => 'An error occurred while creating the shop.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Shop $shop)
    {
        $shop->load(['shopOwner.user', 'zone']);

        return Inertia::render('Admin/Shops/Show', [
            'shop' => $shop,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shop $shop)
    {
        $shop->load(['shopOwner.user', 'zone']);

        // Get initial data for SearchableSelects
        $initialZone = $this->zoneService->getInitialZone($shop->zone);
        $initialShopOwner = $this->shopOwnerService->getInitialShopOwner($shop->shopOwner);

        return Inertia::render('Admin/Shops/Form', [
            'shop' => $shop,
            'initialZone' => $initialZone,
            'initialShopOwner' => $initialShopOwner,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShopRequest $request, Shop $shop, UpdateShop $updateShop)
    {
        try {
            DB::beginTransaction();
            $updateShop->handle($request, $shop);
            DB::commit();

            return redirect()->route('admin.shops.index')->with('success', 'Shop updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['error' => 'An error occurred while updating the shop.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shop $shop)
    {
        try {
            $shop->delete();

            return redirect()->route('admin.shops.index')->with('success', 'Shop deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred while deleting the shop.']);
        }
    }

    /**
     * Search shop owners for select dropdown.
     */
    public function searchOwners(Request $request)
    {
        $query = $request->input('q', '');
        $shopOwners = $this->shopOwnerService->getFormattedShopOwners($query);

        return response()->json($shopOwners);
    }

    /**
     * Search zones for select dropdown.
     */
    public function searchZones(Request $request)
    {
        $query = $request->input('q', '');
        $zones = $this->zoneService->getFormattedZones($query);

        return response()->json($zones);
    }
}
