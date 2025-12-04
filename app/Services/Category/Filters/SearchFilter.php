<?php

namespace App\Services\Category\Filters;

class SearchFilter implements CategoryFilterInterface
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
                $q->where('title_en', 'like', "%{$search}%")
                    ->orWhere('title_bn', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhereHas('parent', function ($parentQuery) use ($search) {
                        $parentQuery->where('title_en', 'like', "%{$search}%")
                            ->orWhere('title_bn', 'like', "%{$search}%");
                    });
            });
        }

        return $next($data);
    }
}
