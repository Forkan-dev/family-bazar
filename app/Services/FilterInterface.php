<?php


namespace App\Services;

interface FilterInterface
{
    /**
     * Apply the filter to the query
     */
    public function handle($data, \Closure $next): mixed;
}