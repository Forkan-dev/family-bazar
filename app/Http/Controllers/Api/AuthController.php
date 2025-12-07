<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginCustomerRequest;
use App\Http\Requests\Api\Auth\RegisterCustomerRequest;
use App\Services\Api\AuthService;
use Illuminate\Support\Facades\Request;

class AuthController extends Controller
{
    protected  $authService;
    public function __construct( AuthService $service)
    {
        $this->authService = $service;
    }

    public function register(RegisterCustomerRequest $request)
    {
       return $this->authService->registerCustomer($request);
    }

    public function login(LoginCustomerRequest $request)
    {
      return  $this->authService->loginCustomer($request);
    }

    public function logout()
    {
      return  $this->authService->logoutCustomer();
    }
   
}
