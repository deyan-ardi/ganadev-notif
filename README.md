# Laravel Ganadev Notification Service Package

<p>
<img alt="php" src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white"><img alt="bootstrap" src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white">
</p>

## What Is Ganadev Notification Sender API?

Ganadev Notification Sender is an API developed by GanaDev Com to simplify and support the process of sending notifications faster without disturbing the main application. The concept used in the development of this API is Microservice, your application sends a notification request via our API, and we will help send it to your users. With this concept, your application will have a much faster execution time than usual. Ganadev Notification Sender currently only supports sending notifications via Email and WhatsApp. 
- GanaDev Com API Notification v3 URL => https://sv1.wa-api.ganadev.com/home

## What Is Laravel Ganadev Notification Service Package?

Laravel Ganadev Notification Service is a Laravel package that is used to help speed up the integration process between the applications you are developing with the Ganadev Notification Sender API. This package includes ready-to-use functions that really help your work process, so you don't need to do it manually anymore

## Support Version

| Laravel Version | Support |
| --------------- | ------- |
| 4.2.x           | `No`    |
| 5.x.x           | `No`    |
| 6.x.x           | `No`    |
| 7.x.x           | `Yes`   |
| 8.x.x           | `Yes`   |
| 9.x.x           | `Yes`   |
| 10.x.x          | `Yes`   |

## System Requerements

- PHP Version "^7.3|7.4|^8.0|^8.1|^8.2"
- Guzzle HTTP (Default By Laravel, Please install if missing from your Laravel)

## Installation

- Open terminal, run this command

```php
composer require deyan-ardi/ganadev-notif
```

- After successful, run this command to publish config file name `ganadevnotif.php` in config folder Laravel

```php
php artisan vendor:publish --provider="DeyanArdi\GanadevNotif\GanadevServiceProvider" --tag="config"
```
- Add this key in project `.env` file

```php
GANADEV_REPLACE_STATUS=true
GANADEV_NOTIF_DEVICE="<YOUR-DEVICE-SELECTED>"
GANADEV_NOTIF_TOKEN="<YOUR-API-TOKEN>"
```

## How To Get API TOKEN

- Please contact GanaDev Com by this url https://ganadev.com/kontak. When your request approved, you will be get the account to login in GanaDev Com Notification API v3 (https://sv1.wa-api.ganadev.com/home)
- Select the settings menu in the upper right corner 
- In the API Key column is your API Token, Copy this to your .env as GANADEV_NOTIF_TOKEN

## How To Register Device
- Click Dashboad menu
- Choose Add Devices and fill all required form.
- Connect your Device to WhatsApp by using Linked Device Feature in WhatsApp
- By defaut WA Notif Status and Email Notif Status is Active, if disabled you cant use WhatsApp API or Email API
- Copy the Number of Devices to your .env as GANADEV_NOTIF_DEVICE configuration

## How To Use

### Integration With Laravel Mailer

To integration this package with Laravel Mailer, first you must add new mailer method in `config/mail.php`
```php
'mailers' => [
    // ....
    'ganadev' => [
            'transport' => 'smtp',
            'host' => env('GANADEV_MAIL_HOST'),
            'port' => env('GANADEV_MAIL_PORT'),
            'encryption' => env('GANADEV_MAIL_ENCRYPTION'),
            'username' => env('GANADEV_MAIL_USERNAME'),
            'password' => env('GANADEV_MAIL_PASSWORD'),
            'timeout' => null,
            'local_domain' => env('GANADEV_MAIL_EHLO_DOMAIN'),
    ],
    // ....
]
```
After that, set the default MAIL_MAILER to `ganadev` and set GANADEV_REPLACE_STATUS to `true` in .env file
```php
MAIL_MAILER=ganadev
GANADEV_REPLACE_STATUS=true
```
That way, every time you send an email using Laravel Mailer. Then the email configuration used is the configuration from the Ganadev Notification Sender API server. Later, if you make changes to your email configuration, you can simply change it on the Ganadev Notification Sender API  (https://sv1.wa-api.ganadev.com/home) server, so all your app that use the Device Account will be updated too. Another advantage, you no longer need to fear that your email configuration will be known by other people, because your email configuration is separate from your application.

Finally, if you activate this function, by default this package will automatically make a request to the Ganadev Notification Sender API server every 15 minutes. This means, if later you make changes to the Device Account on the Ganadev Notification Sender API server. The change will be felt 15 minutes later. You can change the configuration in the `idle_time` configuration in the `config/ganadevnotif.php` file. We provide default options, namely requests every 15 minutes, 30 minutes, or 60 minutes.

### Integration With Laravel Queue
By default, this package send request to API Server using Laravel Queue method used in your application QUEUE_CONNECTION setting. You can disabled it by set `must_queue` to false in `config/ganadevnotif.php`. 

If you want running spesific API Function manually using Jobs, you can publish Jobs file using this command

```php
php artisan vendor:publish --provider="DeyanArdi\GanadevNotif\GanadevServiceProvider" --tag="jobs"
```
Now all job will be publish to Jobs folder and you can call it using Laravel Queue method. Please read documentation of Laravel Queue in https://laravel.com.

### Send Mail Message Only

You can send email message (without image) using methode name `sendMailMessage`, this method required 3 paramaeters `send_to`,`subject`, and `message`. Example usage :

```php
use DeyanArdi\GanadevNotif\GanadevApi;

public function sendingMessageText(){
    $send_to = "yourtargetemail@gmail.com";
    $subject = "Test Subject";

    // Using Text Message
    $message = "Text Message"
    GanadevApi::sendMailMessage($send_to, $subject, $message)
}

public function sendingWithCustomView(){
    $send_to = "yourtargetemail@gmail.com";
    $subject = "Test Subject";

    // Using Laravel Views
    $message = view('emails.exampleNotification', compact('subject','send_to'))->render(); // Your custom view with render method
    GanadevApi::sendMailMessage($send_to, $subject, $message)
}

```

### Send Mail Media (With Image or Other Media)

You can send email message (with image or other media) using methode name `sendMailMedia`, this method required 6 paramaeters `send_to`,`subject`, `message`, `filename`, `link` and `mime_type`. For list of mime_type, please check here https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/MIME_types/Common_types 
Example usage :

```php
use DeyanArdi\GanadevNotif\GanadevApi;

public function sendingMessageText(){
    $send_to = "yourtargetemail@gmail.com";
    $subject = "Test Subject";
    $filename = "test.jpg"; // Filename must with file extension, support all document (docx, doc, xls, xlsx, video, audio, image, pdf dan zip)
    $link = "https://[yourdomain]/images/test.jpg"; //File must be upload on server, cant support localhost
    $mime_type ="image/jpeg"; 

    // Using Text Message
    $message = "Text Message"
    GanadevApi::sendMailMedia($send_to, $subject, $message, $filename, $link, $mime_type);
}

public function sendingWithCustomView(){
    $send_to = "yourtargetemail@gmail.com";
    $subject = "Test Subject";
    $filename = "test.jpg"; // Filename must with file extension, support all document (docx, doc, xls, xlsx, video, audio, image, pdf dan zip)
    $link = "https://[yourdomain]/images/test.jpg"; //File must be upload on server, cant support localhost
    $mime_type ="image/jpeg";

    // Using Laravel Views
    $message = view('emails.exampleNotification', compact('subject','send_to'))->render(); // Your custom view with render method
    GanadevApi::sendMailMedia($send_to, $subject, $message, $filename, $link, $mime_type)
}

```

### Send WhatsApp Message Only

You can send whatsapp message (without image) using methode name `sendWaMessage`, this method required 2 paramaeters `send_to` and `message`. Example usage :

```php
use DeyanArdi\GanadevNotif\GanadevApi;

public function yourExampleFunction(){
    $send_to = "6281915003004" // Must include country code
    $message = "Text Message"
    GanadevApi::sendWaMessage($send_to, $message)
}

```

### Send WhatsApp Media (With Image or Other Media)

You can send whatsapp media (with image or other media) using methode name `sendWaMedia`, this method required 4 paramaeters `send_to`, `link`, `type`, and `message`. Example usage :

```php
use DeyanArdi\GanadevNotif\GanadevApi;

public function sendingImageVideo(){
    $send_to = "6281915003004" // Must include country code
    $type = "image" // please choose one type [image,video,audio,pdf,xls,xlsx,doc,docx,zip] 
    $link = "https://[yourdomain]/images/test.jpg"; 

    // If you choose type "image" or "video", you can add caption for message
    $caption = "Text Message"
    GanadevApi::sendWaMedia($send_to, $link, $type, $caption)
}

public function sendingAudio(){
    $send_to = "6281915003004" // Must include country code
    $type = "image" // please choose one type [image,video,audio,pdf,xls,xlsx,doc,docx,zip] 
    $link = "https://[yourdomain]/images/test.jpg"; 
    $ppt = true; // If you choose type "audio, you can add ppt configuration, the value is true = voice note | false = audio

    GanadevApi::sendWaMedia($send_to, $link, $type, $ppt)
}

public function sendingOtherMedia(){
    $send_to = "6281915003004" // Must include country code
    $type = "image" // please choose one type [image,video,audio,pdf,xls,xlsx,doc,docx,zip] 
    $link = "https://[yourdomain]/images/test.jpg"; 

    // If you choose "pdf","xls","xlsx","doc","docx","zip", you can follow this
    GanadevApi::sendWaMedia($send_to, $link, $type)
}

```
### Get "Device Account" Information
The term "Device Account" means the configuration that contains your email and whatsapp data.You can get detail of device you use by using method `getDevice`, this method not required parameter. Example usage :
```php
use DeyanArdi\GanadevNotif\GanadevApi;

public function yourExampleFunction(){
    GanadevApi::getDevice() // This method return detail of your device using in app, this request using api token and device id to get
}

```
### Response Structure
By default, response of the method is `json response`, you can change it with `array` in `config/ganadevnotif.php`. This is example structure response of the method

```php
{
    "status":200,
    "message":"<MESSAGE API RESPONSE HERE>"
    "data":"<DATA GET FROM SERVER API>"
}
```

## Contributing

- [GanaDev Com](https://ganadev.com)

## Version
- v3.0.1
## License

The Laravel Ganadev Notification Service Package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).