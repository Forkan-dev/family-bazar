<?php

namespace App\Services;

use App\Models\Product\Category;
use App\Services\Category\CategoryFilterPipeline;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pipeline\Pipeline;

class CategoryService
{
    public function __construct(
        private CategoryFilterPipeline $filterPipeline
    ) {}

    /**
     * Get paginated categories with filters applied through pipeline
     */
    public function getPaginatedCategories(Request $request, ?array $customFilters = null): LengthAwarePaginator
    {
        $query = Category::with(['parent', 'children']);

        // Get filters to apply
        $filters = $customFilters ?? $this->filterPipeline->getDefaultFilters();

        // Apply filters through pipeline
        $query = $this->applyFilters($query, $request, $filters);

        // Get pagination settings
        $perPage = $request->get('per_page', 10);

        return $query->paginate($perPage);
    }

    /**
     * Apply filters using pipeline pattern
     */
    private function applyFilters($query, Request $request, array $filters)
    {
        $data = ['query' => $query, 'request' => $request];

        $result = app(Pipeline::class)
            ->send($data)
            ->through($filters)
            ->via('handle')
            ->then(function ($data) {
                return $data['query'];
            });

        return $result;
    }

    /**
     * Get available filter types
     */
    public function getAvailableFilters(): array
    {
        return [
            'search' => \App\Services\Category\Filters\SearchFilter::class,
            'sort' => \App\Services\Category\Filters\SortFilter::class,
        ];
    }

    /**
     * Get categories with custom filters
     */
    public function getCategoriesWithFilters(Request $request, array $filterTypes): LengthAwarePaginator
    {
        $availableFilters = $this->getAvailableFilters();
        $filters = [];

        foreach ($filterTypes as $filterType) {
            if (isset($availableFilters[$filterType])) {
                $filters[] = $availableFilters[$filterType];
            }
        }

        return $this->getPaginatedCategories($request, $filters);
    }

    /**
     * Get searchable columns for the category
     */
    public function getSearchableColumns(): array
    {
        return ['title_en', 'title_bn', 'description', 'slug'];
    }

    /**
     * Get sortable columns for the category
     */
    public function getSortableColumns(): array
    {
        return ['title_en', 'title_bn', 'slug', 'created_at', 'updated_at'];
    }
}
