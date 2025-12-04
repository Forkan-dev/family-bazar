<?php

namespace App\Services\Category;

use App\Services\Category\Filters\SearchFilter;
use App\Services\Category\Filters\SortFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class CategoryFilterPipeline
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
     * Get default filters for categories
     */
    public function getDefaultFilters(): array
    {
        return [
            SearchFilter::class,
            SortFilter::class,
        ];
    }
}
