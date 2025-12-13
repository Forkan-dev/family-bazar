<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\Otp\OtpService;
use App\Services\Api\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\OtpCustomerRequest;
use App\Http\Requests\Api\Auth\LoginCustomerRequest;
use App\Http\Requests\Api\Auth\RegisterCustomerRequest;

class AuthController extends Controller
{
    protected  $authService, $otpService;
    public function __construct(AuthService $service, OtpService $otp)
    {
        $this->authService = $service;
        $this->otpService = $otp;
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

    public function otpVerify(OtpCustomerRequest $request)
    {
        return $this->otpService->verifyOtp($request);
    }



    public function logout()
    {
        return  $this->authService->logoutCustomer();
    }
}
