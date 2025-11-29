<?php

namespace App\Services\Product;

use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Database\Eloquent\Builder;
use App\Services\Product\Filters\SortFilter;
use App\Services\Product\Filters\SearchFilter;
use App\Services\Product\Filters\CategoryFilter;
use App\Services\Product\Filters\PriceRangeFilter;

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
