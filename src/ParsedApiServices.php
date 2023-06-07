<?php

namespace DeyanArdi\GanadevNotif;

use Illuminate\Http\Response;

class ParsedApiServices
{
    public static function parse($body)
    {
        $status = $body['status'];
        $message = $body['msg'];
        $data = isset($body['data']) ? $body['data'] : null;
        $response_to = config('ganadevnotif.response_to');

        if ($response_to == "json") {
            if ($status) {
                return response()->json(
                    [
                        'status' => 200,
                        'info' => 'Success',
                        'error' => null,
                        'data' => $data,
                        'http_request_code' => Response::HTTP_OK
                    ],
                    Response::HTTP_OK
                );
            } else {
                return response()->json(
                    [
                        'status' => 500,
                        'info' => 'Internal Server Error',
                        'error' => $message,
                        'data' => [
                            'wa_notif_status' => 0,
                            'email_notif_status' => 0,
                        ],
                        'http_request_code' => Response::HTTP_INTERNAL_SERVER_ERROR
                    ],
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
            }
        } else {
            if ($status) {
                return  [
                    'status' => 200,
                    'info' => 'Success',
                    'error' => null,
                    'data' => $data,
                    'http_request_code' => Response::HTTP_OK
                ];
            } else {
                return [
                    'status' => 500,
                    'info' => 'Internal Server Error',
                    'error' => $message,
                    'data' => [
                        'wa_notif_status' => 0,
                        'email_notif_status' => 0,
                    ],
                    'http_request_code' => Response::HTTP_INTERNAL_SERVER_ERROR
                ];
            }
        }
    }
}
