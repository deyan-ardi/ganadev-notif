<?php

return [
    /*
    |--------------------------------------------------------------------------
    |  Ganadev API URL
    |--------------------------------------------------------------------------
    |
    | By default = https://sv1.wa-api.ganadev.com, change it if API Url is not available
    |
    */
    'api_url' => "https://sv1.wa-api.ganadev.com",

    /*
    |--------------------------------------------------------------------------
    |  Ganadev API Device
    |--------------------------------------------------------------------------
    |
    | Choose your Device, if dosnt exist please create a new one Device in 
    | https://sv1.wa-api.ganadev.com, make sure device is connected
    |
    */
    'api_device' => env('GANADEV_NOTIF_DEVICE', ''),

    /*
    |--------------------------------------------------------------------------
    |  Ganadev API Token
    |--------------------------------------------------------------------------
    |
    | By default = is null, you must set it value to connect with 
    | ganadev notification server. Contact me to goet your API Token.
    |
    */
    'api_token' => env('GANADEV_NOTIF_TOKEN', ''),

    /*
    |--------------------------------------------------------------------------
    |  Idle Time Setting
    |--------------------------------------------------------------------------
    |
    | By default = 15 minute, you only can change idle time with value [15,30,60]
    | Idle time means that this package will request new configuration from server every
    | idle time setting value. If you make change configuration in server, your application
    | well get new configuration idle time setting value later.
    |
    */
    'idle_time' => 15,

    /*
    |--------------------------------------------------------------------------
    | Ganadev Notification Replace Local Email Settings
    |--------------------------------------------------------------------------
    |
    | By default = true, if false it means this package not change
    | email configuration in local project with configuration email from server. 
    | This will be useful if you want to using local email configuration.
    |
    */
    'use_mail_server_setting' => env('GANADEV_SERVER_MAIL_SETTING', true),


    /*
    |--------------------------------------------------------------------------
    | Ganadev Response API
    |--------------------------------------------------------------------------
    |
    | By default = json, you can change the value with array | json
    |
    */
    'response_to' => "json",
];
