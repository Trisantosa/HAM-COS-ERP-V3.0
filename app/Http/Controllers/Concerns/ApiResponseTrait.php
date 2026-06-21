<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponseTrait
{
    /**
     * Success response with data
     */
    protected function success(
        mixed $data = null,
        string $message = 'Berhasil',
        int $status = 200
    ): JsonResponse {
        $payload = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];

        if ($data instanceof LengthAwarePaginator) {
            $payload['data'] = $data->items();
            $payload['meta'] = [
                'current_page' => $data->currentPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'last_page' => $data->lastPage(),
            ];
        }

        return response()->json($payload, $status);
    }

    /**
     * Created response (201)
     */
    protected function created(
        mixed $data,
        string $message = 'Berhasil dibuat'
    ): JsonResponse {
        return $this->success($data, $message, 201);
    }

    /**
     * Error response
     */
    protected function error(
        string $message,
        ?string $errorCode = null,
        int $status = 422,
        array $errors = []
    ): JsonResponse {
        $payload = [
            'success' => false,
            'message' => $message,
        ];

        if ($errorCode) {
            $payload['error_code'] = $errorCode;
        }

        if (!empty($errors)) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $status);
    }

    /**
     * Forbidden response (403)
     */
    protected function forbidden(
        string $message = 'Anda tidak memiliki akses ke modul ini'
    ): JsonResponse {
        return $this->error($message, 'PERMISSION_DENIED', 403);
    }

    /**
     * Not found response (404)
     */
    protected function notFound(
        string $message = 'Data tidak ditemukan'
    ): JsonResponse {
        return $this->error($message, 'NOT_FOUND', 404);
    }

    /**
     * Unprocessable entity (422)
     */
    protected function unprocessable(
        string $message = 'Data tidak valid',
        array $errors = []
    ): JsonResponse {
        return $this->error($message, 'UNPROCESSABLE_ENTITY', 422, $errors);
    }

    /**
     * Server error response (500)
     */
    protected function serverError(
        string $message = 'Terjadi kesalahan pada server'
    ): JsonResponse {
        return $this->error($message, 'INTERNAL_SERVER_ERROR', 500);
    }
}
