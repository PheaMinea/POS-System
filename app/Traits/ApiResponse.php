<?php

namespace App\Traits;

trait ApiResponse
{
    protected function success(
        $data = null,
        string $message = 'Success',
        int $status = 200
    ) {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    protected function error(
        string $message = 'Error',
        int $status = 500,
        $errors = null
    ) {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ], $status);
    }

    protected function successResponse(
        $data = null,
        string $message = 'Success',
        int $status = 200
    ) {
        return $this->success($data, $message, $status);
    }

    protected function errorResponse(
        string $message = 'Error',
        int $status = 500,
        $errors = null
    ) {
        return $this->error($message, $status, $errors);
    }
}