<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginCustomerRequest;
use App\Http\Requests\Api\Auth\RegisterCustomerRequest;
use App\Services\Api\AuthService;

class AuthController extends Controller
{
  protected  $authService;
  public function __construct(AuthService $service)
  {
    $this->authService = $service;
  }

  public function register(RegisterCustomerRequest $request)
  {
    return $this->authService->registerCustomer($request);
  }

  public function login(LoginCustomerRequest $request)
  {
    try {
      $login =   $this->authService->loginCustomer($request);
      return $login;
    } catch (\Exception $e) {
      return ApiResponse::error(
        'Login failed: ' . $e->getMessage(),
        null,
        500
      );
    }
  }

  public function logout()
  {
    return  $this->authService->logoutCustomer();
  }
}
