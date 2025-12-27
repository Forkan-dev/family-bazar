<?php

namespace App\Services\Offer;

use App\Models\Offer;
use App\Services\Offer\Filters\OfferFilterPipeline;
use Illuminate\Pagination\LengthAwarePaginator;

class OfferService
{
    protected  $filterPipeline;

    public function __construct(OfferFilterPipeline $filterPipeline)
    {
        $this->filterPipeline = $filterPipeline;
    }

     public function getPaginatedOffers($request, ?array $customFilters = null): LengthAwarePaginator
    {
        $query = Offer::with(['offerTargets.target']);

        // Get filters to apply
        $filters = $customFilters ?? $this->filterPipeline->getDefaultFilters();

        // Apply filters through pipeline
        $query = $this->filterPipeline->applyFilter($query, $request, $filters);

        // Get pagination settings
        $perPage = $request->get('per_page', 10);
        // dd($query );


        return $query->paginate($perPage);
    }
}