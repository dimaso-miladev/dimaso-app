<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

/**
 * Small helper to keep API responses consistent and easy to read.
 */
trait ApiResponse
{
    /**
     * Build a successful JSON response.
     *
     * @param mixed $data   The payload to return to the client.
     * @param int   $status HTTP status code (default 200).
     */
    protected function success($data = null, int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
        ], $status);
    }

    /**
     * Build an error JSON response.
     *
     * @param string $message Short, human-friendly error message.
     * @param int    $status  HTTP status code (default 400).
     * @param mixed  $details Optional extra info (e.g. validation errors array).
     */
    protected function error(string $message = 'An error occurred', int $status = 400, $details = null): JsonResponse
    {
        $body = [
            'success' => false,
            'error' => [ 'message' => $message ],
        ];

        if ($details !== null) {
            $body['error']['details'] = $details;
        }

        return response()->json($body, $status);
    }
}
