<?php

namespace App\Services\Shop\Filters;

interface ShopFilterInterface
{
    /**
     * Apply filter to the query
     *
     * @param array $data Contains 'query' and 'request'
     * @param \Closure $next
     * @return mixed
     */
    public function handle($data, \Closure $next): mixed;
}
