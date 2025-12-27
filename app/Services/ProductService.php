<?php

namespace App\Services;

use App\Models\Product\Product;
use App\Services\Product\ProductFilterPipeline;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pipeline\Pipeline;

class ProductService
{
    public function __construct(
        private ProductFilterPipeline $filterPipeline
    ) {}

    /**
     * Get paginated products with filters applied through pipeline
     */
    public function 
    getPaginatedProducts(Request $request, ?array $customFilters = null): LengthAwarePaginator
    {
        $query = Product::with(['category', 'tags']);

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
            'search' => \App\Services\Product\Filters\SearchFilter::class,
            'category' => \App\Services\Product\Filters\CategoryFilter::class,
            'price_range' => \App\Services\Product\Filters\PriceRangeFilter::class,
            'sort' => \App\Services\Product\Filters\SortFilter::class,
        ];
    }

    /**
     * Get products with custom filters
     */
    public function getProductsWithFilters(Request $request, array $filterTypes): LengthAwarePaginator
    {
        $availableFilters = $this->getAvailableFilters();
        $filters = [];

        foreach ($filterTypes as $filterType) {
            if (isset($availableFilters[$filterType])) {
                $filters[] = $availableFilters[$filterType];
            }
        }

        return $this->getPaginatedProducts($request, $filters);
    }

    /**
     * Get searchable columns for the product
     */
    public function getSearchableColumns(): array
    {
        return ['name_en', 'name_bn', 'description'];
    }

    /**
     * Get sortable columns for the product
     */
    public function getSortableColumns(): array
    {
        return ['name_en', 'price', 'stock_quantity', 'created_at'];
    }
}
