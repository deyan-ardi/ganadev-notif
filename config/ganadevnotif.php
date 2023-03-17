<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Ganadev Notification Status
    |--------------------------------------------------------------------------
    |
    | By default = true, if false it means that ganadev notification
    | cant access server. This will be useful if your internet connection 
    | is not good
    |
    */
    'api_status' => env('GANADEV_NOTIF_STATUS', true),
    /*
    |--------------------------------------------------------------------------
    |  Length of the generated OTP
    |--------------------------------------------------------------------------
    |
    | By default = is null, you must set it value to connect with 
    | ganadev notification server. Contact me to goet your API Token.
    |
    */
    'api_token' => env('GANADEV_NOTIF_TOKEN', ''),
];
