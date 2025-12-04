<?php

namespace App\Services\Category\Filters;

class SortFilter implements CategoryFilterInterface
{
    /**
     * Apply sorting to the query
     */
    public function handle($data, \Closure $next): mixed
    {
        $query = $data['query'];
        $request = $data['request'];

        $sortColumn = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        // Validate sort column to prevent SQL injection
        $allowedSortColumns = $this->getAllowedSortColumns();

        if (in_array($sortColumn, $allowedSortColumns)) {
            $query->orderBy($sortColumn, $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $next($data);
    }

    /**
     * Get allowed sort columns
     */
    private function getAllowedSortColumns(): array
    {
        return ['title_en', 'title_bn', 'slug', 'created_at', 'updated_at'];
    }
}
