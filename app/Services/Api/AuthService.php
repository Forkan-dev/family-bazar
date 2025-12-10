<?php

namespace App\Services\Api;

use ApiResponse;
use App\Services\Otp\OtpService;
use App\Models\Customer\Customer;
use Illuminate\Support\Facades\Hash;
use App\Actions\Order\Cart\CreateCart;

class AuthService
{

    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }


    public function registerCustomer($request)
    {

        try {
            $validatedData = $request->validated();

            $customer = Customer::create([
                'phone_number' => $validatedData['phone_number'],

            ]);
            $otp = $this->generateOtp();

            $token_string  = bin2hex(random_bytes(40)) . time() . $customer->phone_number;
            $token = $customer->createToken($token_string)->plainTextToken;
            return ApiResponse::success(
                ['access_token' => $token],
                'User registered successfully',
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                'Registration failed: ' . $e->getMessage(),
                null,
                500
            );
        }
    }
    public function loginCustomer($request)
    {
        try {
            $validatedData = $request->validated();
            // Find or create customer
            $customer = Customer::firstOrCreate(
                ['phone_number' => $validatedData['phone_number']]
            );

            // Use injected OtpService
            $otp = $this->otpService->storeOtp($customer);

            // Create API token
            $token_string = bin2hex(random_bytes(40)) . time() . $customer->phone_number;
            $token = $customer->createToken($token_string)->plainTextToken;

            return ApiResponse::success(
                [
                    'access_token' => $token,
                    'otp' => $otp, // optionally return OTP for SMS
                ],
                'Enter Your Otp',
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                'Login failed: ' . $e->getMessage(),
                null,
                500
            );
        }
    }


    public function logoutCustomer()
    {
        try {
            $customer = auth('customer')->user();

            // Check if customer is authenticated
            if (!$customer) {
                return ApiResponse::error(
                    'User already logged out or invalid token',
                    null,
                    401
                );
            }

            // Delete current token only
            $customer->currentAccessToken()->delete();
            return ApiResponse::success(
                null,
                'User logged out successfully'
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                'Logout failed: ' . $e->getMessage(),
                null,
                500
            );
        }
    }
}
