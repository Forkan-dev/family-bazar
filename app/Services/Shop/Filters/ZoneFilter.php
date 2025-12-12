<?php

namespace App\Services\Shop\Filters;

class ZoneFilter implements ShopFilterInterface
{
    /**
     * Apply zone filter to the query
     */
    public function handle($data, \Closure $next): mixed
    {
        $query = $data['query'];
        $request = $data['request'];

        if ($request->filled('zone_id')) {
            $zoneId = $request->get('zone_id');
            $query->where('zone_id', $zoneId);
        }

        return $next($data);
    }
}
