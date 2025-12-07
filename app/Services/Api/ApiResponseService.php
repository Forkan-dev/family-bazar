<?php

namespace App\Services\Api;

class ApiResponseService
{
    /**
     * Success response
     *
     * @param mixed $data
     * @param string|null $message
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($data = null,  $message = null, int $status = 200)
    {
        return response()->json([
            'status'  => 'success',
            'message' => $message ?? 'Request successful',
            'data'    => $data
        ], $status);
    }

    /**
     * Error response
     *
     * @param string|null $message
     * @param int $status
     * @param mixed|null $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function error($message = null,  $errors = null ,$status = 400, )
    {
        return response()->json([
            'status'  => 'error',
            'message' => $message ?? 'Something went wrong',
            'errors'  => $errors
        ], $status);
    }

    /**
     * Validation error response
     *
     * @param array $errors
     * @param string|null $message
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function validationError(array $errors,  $message = null)
    {
        return response()->json([
            'status'  => 'fail',
            'message' => $message ?? 'Validation failed',
            'errors'  => $errors
        ], 422);
    }
}
