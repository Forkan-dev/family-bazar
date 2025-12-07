<?php

namespace App\Traits;


trait CheckAuthTrait
{

   protected function isAuthenticated($guard): bool
   {
       return auth($guard)->check();
   }
   protected function getAuthenticatedUserId($guard)
   {
       return auth($guard)->user()->id;
   }

}
