<?php

namespace App\Services\Shop;

use App\Models\Shop\Shop;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pipeline\Pipeline;

class ShopService
{
    public function __construct(
        private ShopFilterPipeline $filterPipeline
    ) {}

    /**
     * Get paginated shops with filters applied through pipeline
     */
    public function getPaginatedShops(Request $request, ?array $customFilters = null): LengthAwarePaginator
    {
        $query = Shop::with(['shopOwner', 'zone']);

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
            'search' => \App\Services\Shop\Filters\SearchFilter::class,
            'type' => \App\Services\Shop\Filters\TypeFilter::class,
            'status' => \App\Services\Shop\Filters\StatusFilter::class,
            'zone' => \App\Services\Shop\Filters\ZoneFilter::class,
            'sort' => \App\Services\Shop\Filters\SortFilter::class,
        ];
    }

    /**
     * Get shops with custom filters
     */
    public function getShopsWithFilters(Request $request, array $filterTypes): LengthAwarePaginator
    {
        $availableFilters = $this->getAvailableFilters();
        $filters = [];

        foreach ($filterTypes as $filterType) {
            if (isset($availableFilters[$filterType])) {
                $filters[] = $availableFilters[$filterType];
            }
        }

        return $this->getPaginatedShops($request, $filters);
    }

    /**
     * Get searchable columns for the shop
     */
    public function getSearchableColumns(): array
    {
        return ['name', 'type'];
    }

    /**
     * Get sortable columns for the shop
     */
    public function getSortableColumns(): array
    {
        return ['name', 'type', 'commission_rate', 'status', 'created_at'];
    }

    /**
     * Get shop types
     */
    public function getShopTypes(): array
    {
        return ['retail', 'wholesale', 'distributor'];
    }
}
