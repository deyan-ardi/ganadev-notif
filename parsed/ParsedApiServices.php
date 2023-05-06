<?php

namespace DeyanArdi\GanadevNotif;

use Illuminate\Http\Response;

class ParsedApiServices
{
    protected static function parse($body)
    {
        $status = $body['status'];
        $message = $body['msg'];
        if ($status) {
            return response()->json([
                'status' => 200, 
                'info' => 'Message Send Succesfully',
                'error' => null
            ], 
            Response::HTTP_OK);
        }else{
            return response()->json([
                'status' => 500, 
                'info' => 'Internal Server Error',
                'error' => $message
            ], 
            Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
