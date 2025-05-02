<?php

// app/Helpers/ApiResponse.php
namespace App\Helpers;

class ApiResponse {
    public static function success($message, $data = null, $code = 200) {
        return self::buildResponse(
            'Success',
            $message,
            $data,
            $code
        );
    }

    public static function error($message, $data = null, $code = 500) {
        return self::buildResponse(
            'Error',
            $message,
            $data,
            $code
        );
    }

    private static function buildResponse($status, $message, $data = null, $code) {
        // TODO: remover índices do array que estão nulos antes de enviar
        $data = [
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ];
        
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
