<?php

namespace DeyanArdi\GanadevNotif;

use Illuminate\Http\Response;

class ParsedApiServices
{
    public static function parse($body)
    {
        $status = $body['status'];
        $message = $body['msg'];
        $response_to = config('ganadevnotif.response_to');

        if ($response_to == "json") {
            if ($status) {
                return response()->json(
                    [
                        'status' => 200,
                        'info' => 'Message Send Succesfully',
                        'error' => null
                    ],
                    Response::HTTP_OK
                );
            } else {
                return response()->json(
                    [
                        'status' => 500,
                        'info' => 'Internal Server Error',
                        'error' => $message
                    ],
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
            }
        } else {
            if ($status) {
                return  [
                    'status' => 200,
                    'info' => 'Message Send Succesfully',
                    'error' => null
                ];
            } else {
                return [
                    'status' => 500,
                    'info' => 'Internal Server Error',
                    'error' => $message
                ];
            }
        }
    }
}
