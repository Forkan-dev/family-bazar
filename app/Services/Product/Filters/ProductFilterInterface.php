<?php

namespace App\Services\Product\Filters;

interface ProductFilterInterface
{
    /**
     * Apply the filter to the query
     */
    public function handle($data, \Closure $next): mixed;
}
