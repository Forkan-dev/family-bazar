<?php

namespace App\Services\Product\Filters;

class PriceRangeFilter implements ProductFilterInterface
{
    /**
     * Apply price range filter to the query
     */
    public function handle($data, \Closure $next): mixed
    {
        $query = $data['query'];
        $request = $data['request'];

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->get('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->get('max_price'));
        }

        return $next($data);
    }
}
