<?php

namespace App\Services\Shop\Filters;

class StatusFilter implements ShopFilterInterface
{
    /**
     * Apply status filter to the query
     */
    public function handle($data, \Closure $next): mixed
    {
        $query = $data['query'];
        $request = $data['request'];

        if ($request->filled('status')) {
            $status = $request->get('status');

            // Convert string to boolean if needed
            if (is_string($status)) {
                $status = filter_var($status, FILTER_VALIDATE_BOOLEAN);
            }

            $query->where('status', $status);
        }

        return $next($data);
    }
}
