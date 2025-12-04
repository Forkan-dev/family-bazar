<?php

namespace App\Services\Product\Filters;

class SearchFilter implements ProductFilterInterface
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
                $q->where('name_en', 'like', "%{$search}%")
                    ->orWhere('name_bn', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('title_en', 'like', "%{$search}%")
                            ->orWhere('title_bn', 'like', "%{$search}%");
                    })
                    ->orWhereHas('tags', function ($tagQuery) use ($search) {
                        $tagQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        return $next($data);
    }
}
