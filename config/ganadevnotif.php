<?php

return [
    /*
    |--------------------------------------------------------------------------
    |  Ganadev API URL
    |--------------------------------------------------------------------------
    |
    | By default = https://sv1.notif.ganadev.com, change it if API Url is not available
    |
    */
    'api_url' => "https://sv1.notif.ganadev.com",

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
    | Ganadev Notification WhatsApp Status
    |--------------------------------------------------------------------------
    |
    | By default = true, if false it means you cant use WhatsApp Notification Sender API. 
    | This will be useful if you want to using Email Notification Sender only.
    |
    */
    'api_wa_status' => env('GANADEV_WA_API_STATUS', true),

    /*
    |--------------------------------------------------------------------------
    | Ganadev Notification Email Status
    |--------------------------------------------------------------------------
    |
    | By default = true, if false it means you cant use Email Notification Sender API. 
    | This will be useful if you want to using WhatsApp Notification Sender only.
    |
    */
    'api_email_status' => env('GANADEV_MAIL_API_STATUS', true),

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
];
