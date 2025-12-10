<?php

namespace App\Services\Otp;

use ApiResponse;

use App\Models\Customer\Customer;

class OtpService
{
    public function generateOtp($length = 6)
    {
        return rand(pow(10, $length - 1), pow(10, $length) - 1);
    }

    public function storeOtp(Customer $customer, $expireInMinutes = 2)
    {
        $otp = $this->generateOtp();

        $customer->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes($expireInMinutes),
            'otp_attempts' => 0,
            'last_otp_sent_at' => now(),
        ]);

        return $otp;
    }

    public function verifyOtp($request)
    {
        $validated = $request->validated();

        $customer = Customer::where('phone_number', $validated['phone_number'])
            ->select('otp_code', 'otp_expires_at', 'id')
            ->first();

        if (!$customer) {
            return ApiResponse::error(
                'Customer not found',
                null,
                404
            );
        }

        // Check OTP
        if ($customer->otp_code != $validated['otp_code']) {
            return ApiResponse::error(
                'Invalid OTP',
                null,
                401
            );
        }

        // Check Expiry
        if ($customer->otp_expires_at && $customer->otp_expires_at < now()) {
            return ApiResponse::error(
                'OTP expired. Please request a new one.',
                null,
                401
            );
        }

        // Update customer
        Customer::where('id', $customer->id)->update([
            'otp_code' => null,
            'otp_expires_at' => null,
            'otp_attempts' => 0,
            'last_otp_sent_at' => null,
            'is_phone_verified' => true,
        ]);

        return ApiResponse::success(
            null,
            'OTP verified successfully'
        );
    }
}
