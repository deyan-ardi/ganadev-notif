<?php

namespace DeyanArdi\GanadevNotif;

use Illuminate\Http\Response;

class ParsedApiServices
{
    public static function parse($body)
    {
        $status = $body['status'];
        $message = $body['msg'];
        $data = $body['data'] ?? null;
        $responseType = config('ganadevnotif.response_to');

        if ($responseType === "json") {
            return self::jsonResponse($status, $message, $data);
        }

        return self::arrayResponse($status, $message, $data);
    }

    private static function jsonResponse(string $status, ?string $message = null, $data = null)
    {
        $responseData = [
            'status' => $status ? 200 : 500,
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($responseData, $responseData['status']);
    }

    private static function arrayResponse(string $status, ?string $message = null, $data = null)
    {
        $responseData = [
            'status' => $status ? 200 : 500,
            'message' => $message,
            'data' => $data
        ];

        return $responseData;
    }
}