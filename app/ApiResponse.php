<?php
namespace App;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    public function success(mixed $data, string $message = 'Success', int $status = 200): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'success' => true,
            'message' => $message,
        ], $status);
    }

    public function error(array|string|null $errors = null, string $message = 'Error', int $status = 500): JsonResponse
    {
        return response()->json([
            'data' => $errors,
            'success' => false,
            'message' => $message,
        ], $status);
    }
}
