<?php


namespace App\Facades\Api;

use Illuminate\Support\Facades\Facade;

class ResponseFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'apiResponseService';
    }
}
