<?php

// app/Helpers/ApiResponse.php
namespace App\Helpers;

class ApiResponse {
    public static function success($message, $data = null, $code) {
        return self::buildResponse(
            'Success',
            $message,
            $data,
            $code ?: 200
        );
    }

    public static function error($message, $data = null, $code) {
        return self::buildResponse(
            'Error',
            $message,
            $data,
            $code ?: 500
        );
    }

    private static function buildResponse($status, $message, $data = null, $code = 200) {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
