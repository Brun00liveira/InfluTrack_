<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * Formata a resposta da API.
     *
     * @param  mixed  $data
     * @param  string  $message
     * @param  int  $status
     * @param  int|null  $code
     * @return JsonResponse
     */
    public static function format($data = null, $message = 'Success', $status = 200, $code = null): JsonResponse
    {
        $response = [
            'status' => $status,
            'message' => $message,
        ];

        // Adiciona dados extras caso existam
        if ($data !== null) {
            $response['data'] = $data;
        }

        if ($code !== null) {
            $response['code'] = $code;
        }

        return response()->json($response, $status);
    }

    /**
     * Resposta de erro da API.
     *
     * @param  string  $message
     * @param  int  $status
     * @param  int|null  $code
     * @return JsonResponse
     */
    public static function error($message = 'Error', $status = 400, $code = null): JsonResponse
    {
        return self::format(null, $message, $status, $code);
    }

    /**
     * Resposta de sucesso com dados.
     *
     * @param  mixed  $data
     * @param  string  $message
     * @param  int  $status
     * @return JsonResponse
     */
    public static function success($data, $message = 'Success', $status = 200): JsonResponse
    {
        return self::format($data, $message, $status);
    }
}
