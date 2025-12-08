<?php

namespace App\Services\Product;

use App\Services\Product\Filters\CategoryFilter;
use App\Services\Product\Filters\PriceRangeFilter;
use App\Services\Product\Filters\SearchFilter;
use App\Services\Product\Filters\SortFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class ProductFilterPipeline
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
     * Get default filters for products
     */
    public function getDefaultFilters(): array
    {
        return [
            SearchFilter::class,
            CategoryFilter::class,
            PriceRangeFilter::class,
            SortFilter::class,
        ];
    }
}
