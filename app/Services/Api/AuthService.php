<?php

namespace App\Services\Api;

use App\Models\Customer\Customer;
use Illuminate\Support\Facades\Hash;
use ApiResponse;
use App\Actions\Order\Cart\CreateCart;

class AuthService
{
    
    public function registerCustomer($request)
    {
        try {
            $validatedData = $request->validated();

            $customer = \App\Models\Customer\Customer::create([
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            $token_string  = bin2hex(random_bytes(40)) . time() . $customer->email;
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

            $customer = Customer::where('email', $request->email)->first();

            if ($customer && Hash::check($request->password, $customer->password)) {
                $token_string  = bin2hex(random_bytes(40)) . time() . $customer->email;
                $token = $customer->createToken($token_string)->plainTextToken;

               

                return ApiResponse::success(
                    ['access_token' => $token],
                    'User logged in successfully',
                );
            } else {
                return ApiResponse::error(
                    'Invalid credentials',
                    null,
                    401
                );
            }
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
