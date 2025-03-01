<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{

    /**
     * @param $result
     * @param $message
     * @return JsonResponse
     */
    public function successResponse($message, mixed $result = null): JsonResponse
    {
        $response = [
            'status' => true,
            'message' => $message,
        ];

        if (!empty($result)) {
            $response['data'] = $result;
        }

        return response()->json($response, 200);
    }

    /**
     * @param $errorMessage
     * @param $errors
     * @param int $code
     * @return JsonResponse
     */
    public function errorResponse($errorMessage, $errors = [], int $code = 500): JsonResponse
    {
        $response = [
            'status' => false,
            'message' => $errorMessage,
        ];

        if (!empty($errors)) {
            $response['error'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    public function notFoundResponse(string $message = 'Resource not found'): JsonResponse
    {
        return $this->errorResponse($message, [], 404);
    }

    /**
     * @param array $errors
     * @param string $message
     * @return JsonResponse
     */
    public function validationErrorResponse(string $message = 'Validation failed', array $errors = []): JsonResponse
    {
        return $this->errorResponse($message, $errors, 422);
    }

    /**
     * @param string $errorMessage
     * @param array $errors
     * @return JsonResponse
     */
    public function notVerified(string $errorMessage = 'Not verified', array $errors = []): JsonResponse
    {
        return $this->errorResponse($errorMessage, $errors, 302);
    }
}
