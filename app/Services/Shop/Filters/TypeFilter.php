<?php

namespace App\Services\Shop\Filters;

class TypeFilter implements ShopFilterInterface
{
    /**
     * Apply type filter to the query
     */
    public function handle($data, \Closure $next): mixed
    {
        $query = $data['query'];
        $request = $data['request'];

        if ($request->filled('type')) {
            $type = $request->get('type');

            // Validate type
            $allowedTypes = ['retail', 'wholesale', 'distributor'];

            if (in_array($type, $allowedTypes)) {
                $query->where('type', $type);
            }
        }

        return $next($data);
    }
}
