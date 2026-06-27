<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

abstract class ApiController extends Controller
{
    protected function success(mixed $data, ?string $locale = null, int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'locale' => $locale ?? app()->getLocale(),
            'data' => $data,
        ], $status);
    }

    protected function error(string $message, int $status = 422, array $errors = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }
}
