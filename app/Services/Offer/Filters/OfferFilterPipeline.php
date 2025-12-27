<?php

namespace App\Services\Offer\Filters;
use App\Services\Offer\Filters\SearchFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
class OfferFilterPipeline
{
    /**
     * Apply filters through pipeline
     */
    public function applyFilter(Builder $query,  $request, array $filters): Builder
    {
        $data = ['query' => $query, 'request' => $request];
        return app(Pipeline::class)
            ->send($data)
            ->through($filters)
            ->via('handle')
            ->then(function ($data) {
                return $data['query'];
            });
    }

    /**
     * Get default filters for offers
     */
    public function getDefaultFilters(): array
    {
        return [
            SearchFilter::class,
        ];
    }
}