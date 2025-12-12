<?php

namespace App\Services\Shop;

use App\Services\Shop\Filters\SearchFilter;
use App\Services\Shop\Filters\SortFilter;
use App\Services\Shop\Filters\StatusFilter;
use App\Services\Shop\Filters\TypeFilter;
use App\Services\Shop\Filters\ZoneFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class ShopFilterPipeline
{
    /**
     * Apply filters through pipeline
     */
    public function apply(Builder $query, Request $request, array $filters): Builder
    {
        return app(Pipeline::class)
            ->send($query)
            ->through($filters)
            ->via('handle')
            ->then(function ($query) {
                return $query;
            });
    }

    /**
     * Get default filters for shops
     */
    public function getDefaultFilters(): array
    {
        return [
            SearchFilter::class,
            TypeFilter::class,
            StatusFilter::class,
            ZoneFilter::class,
            SortFilter::class,
        ];
    }
}
