<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Offer\CreateOffer;
use App\Actions\Offer\UpdateOffer;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Offer\StoreOfferRequest;
use App\Http\Requests\Admin\Offer\UpdateOfferRequest;
use App\Models\Offer;
use App\Services\Offer\OfferService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;


class OfferController extends Controller
{
    protected  $offerService;
    protected $createOfferAction;
    protected $updateOfferAction;
    public function __construct(OfferService $offerService, CreateOffer $createOfferAction, UpdateOffer $updateOfferAction)
    {
        $this->offerService = $offerService;
        $this->createOfferAction = $createOfferAction;
        $this->updateOfferAction = $updateOfferAction;
    }
    public function index(Request $request)
    {
        $filterData = $this->offerService->getPaginatedOffers($request);
        return Inertia::render('Admin/Offer/Index', ['offers' => $filterData]);
    }
    public function store(StoreOfferRequest $request)
    {
        $data = $request->validated();
        DB::beginTransaction();
        try {
            $this->createOfferAction->handle($data);
            DB::commit();
            return redirect()->route('admin.offers.index')->with('success', 'Offer created.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    public function create()
    {
        return Inertia::render('Admin/Offer/Create');
    }
    public function show(Offer $offer) {}


    public function edit(Request $request, Offer $offer)
    {
        $offer->load('offerTargets.target');
        $data = $offer->offerTargets->map(function ($target) {
            return [
                'type' => $target->target_type,
                'name' => $target->target ? ($target->target->name ?? $target->target->name_en ?? $target->target->title_en ?? 'N/A') : 'N/A',
                'id' => $target->target_id,
            ];
        });

        return Inertia::render('Admin/Offer/Edit', [
            'offer' => $offer,
            'targets' => $data->toArray(),
        ]);
    }

    public function update(UpdateOfferRequest $request, Offer $offer)
    {
        $data = $request->validated();
        DB::beginTransaction();
        try {
            $this->updateOfferAction->handle($offer, $data);
            DB::commit();
            return redirect()->route('admin.offers.index')->with('success', 'Offer updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function delete()
    {

        //
    }
}
