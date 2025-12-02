<?php

namespace App\Services\Category\Filters;

interface CategoryFilterInterface
{
    /**
     * Apply the filter to the query
     */
    public function handle($data, \Closure $next): mixed;
}
