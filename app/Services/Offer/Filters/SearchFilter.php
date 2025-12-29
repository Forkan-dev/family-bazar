<?php

namespace App\Services\Offer\Filters;

use App\Services\FilterInterface;

class SearchFilter implements FilterInterface
{
    /**
     * Apply search filter to the query
     */
    public function handle($data, \Closure $next): mixed
    {
        $query = $data['query'];
        $request = $data['request'];

        if ($request->filled('search')) {
            $search = $request->get('search');

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('name_bn', 'like', "%{$search}%") 
                    ->orWhere('discount_type', 'like', "%{$search}%") 
                    ->orWhereHas('offerTargets', function ($items) use ($search) {
                        $items->where('name', 'like', "%{$search}%")
                            ->orWhere('name_bn', 'like', "%{$search}%");
                    });
            });
        }

        return $next($data); 
    }
}
