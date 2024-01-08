<?php

namespace App\Traits;

trait ApiResponseTrait
{
    public function successResponse($data, $message = '', $status = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    public function errorResponse($message, $status)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $status);
    }
}
