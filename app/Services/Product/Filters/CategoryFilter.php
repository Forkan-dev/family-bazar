<?php

namespace App\Services\Product\Filters;

class CategoryFilter implements ProductFilterInterface
{
    /**
     * Apply category filter to the query
     */
    public function handle($data, \Closure $next): mixed
    {
        $query = $data['query'];
        $request = $data['request'];

        if ($request->filled('category_id')) {
            $categoryId = $request->get('category_id');
            $query->where('category_id', $categoryId);
        }else if ($request->filled('slug')) {
            $categorySlug = $request->get('slug');
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        return $next($data);
    }
}
