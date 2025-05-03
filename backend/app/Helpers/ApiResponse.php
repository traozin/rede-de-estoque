<?php

namespace App\Helpers;

use App\Helpers\ArrayUtils;
 
class ApiResponse {
    public static function success($message, $code = 200, $data = null) {
        return self::buildResponse(
            'Success',
            $message,
            $data,
            $code
        );
    }

    public static function error($message, $code = 500, $data = null) {
        return self::buildResponse(
            'Error',
            $message,
            $data,
            $code 
        );
    }

    private static function buildResponse($status, $message, $data = null, $code) {
        $array = ArrayUtils::clearArray([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], true);

        return response()->json($array, (int) $code);
    }
}
