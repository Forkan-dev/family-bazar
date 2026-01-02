<?php

namespace App\Services\Offer;

use App\Models\Offer;
use App\Services\Offer\Filters\OfferFilterPipeline;
use Carbon\Carbon;
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

        // transform and paginate data
        $perPage = $request->get('per_page', 10);

        $offers = $query->paginate($perPage);

        // Transform the items inside the paginator
        $offers->getCollection()->transform(function ($offer) {
            $offer->start_at = datetime_parse_utc_to_local($offer->start_at, 'Y-m-d\TH:i');
            $offer->end_at   = datetime_parse_utc_to_local($offer->end_at, 'Y-m-d\TH:i');
            return $offer;
        });
        return $offers;
    }
}
