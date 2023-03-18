
# Laravel Ganadev Notification Service Package
<p>
<img alt="php" src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white"><img alt="bootstrap" src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white">
</p>

## What Is Ganadev Notification Sender API?

Ganadev Notification Sender is an API developed by GanaDev Com to simplify and support the process of sending notifications faster without disturbing the main application. The concept used in the development of this API is Microservice, your application sends a notification request via our API, and we will help send it to your users. With this concept, your application will have a much faster execution time than usual. Ganadev Notification Sender currently only supports sending notifications via Email and WhatsApp. You can view full documentation via the url https://sv1.notif.ganadev.com

## What Is Laravel Ganadev Notification Service Package?
Laravel Ganadev Notification Service is a Laravel package that is used to help speed up the integration process between the applications you are developing with the Ganadev Notification Sender API. This package includes ready-to-use functions that really help your work process, so you don't need to do it manually anymore
## Support Version
| Laravel Version | Support |
| --- | --- |
| 4.2.x | `No` |
| 5.x.x | `No` |
| 6.x.x | `No` |
| 7.x.x | `Yes` |
| 8.x.x | `Yes` |
| 9.x.x | `Yes` |
| 10.x.x | `Yes` |

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
GANADEV_SERVER_MAIL_SETTING = true 
GANADEV_MAIL_API_STATUS = true
GANADEV_WA_API_STATUS = true
GANADEV_NOTIF_TOKEN = "YOUR-API-TOKEN"
```
## How To Get API TOKEN
- You must register the "Device" which contains the email and whatsapp configuration that you will use on the Ganadev Notification API. If you already have a registered "Device", you can skip this step.
- Next, you must register the "App" that will use the "Device" that you have. You can have multiple "Apps" for each "Device" you have. Each "App" has a different "API TOKEN".
- The "API TOKEN" of the application that you have registered, you can use to access the Ganadev Notification Sender API.
- To get an API Token for your application, you can contact the GanaDev Com via https://ganadev.com/kontak. Please prepare your email and WhatsApp number before register your application
## Disclaimer
- When you use this package, the local email delivery configuration you are using will be automatically overwritten with the configuration you added to the Ganadev Notification Sender API.
- You can still use your local email configuration by default when you change value key `GANADEV_SERVER_MAIL_SETTING = false` in project .env file
- You can disabled Email or WhatsApp sender by change value of `GANADEV_MAIL_API_STATUS = false` or `GANADEV_WA_API_STATUS = false` in project .env file.
- This package auto request to server Ganadev Notification Sender API every 15 minute to get fresh configuration. That mean, when you request change configuration in Ganadev Notification Sender API to Admin, your change will be affected by default 15 minutes later. You can change value of minute auto request by change value of `idle_time` in file `config/ganadevnotif.php`. We only give you option 15 minute, 30 minute, or 60 minute.
## How To Use
### Method Send Mail Message
You can send email message (without image) using methode name `sendMailMessage`, this method required 3 paramaeters `send_to`,`subject`, and `message`. Example usage :

```php
use DeyanArdi\GanadevNotif\GanadevApi;

public function yourExampleFunction(){
    $send_to = "yourtargetemail@gmail.com";
    $subject = "Test Subject";

    // Using Text Message
    $message = "Text Message"
    GanadevApi::sendMailMessage($send_to, $subject, $message)

    // Using Laravel Views
    $message = view('emails.exampleNotification', compact('subject','send_to'))->render(); // Your custom view with render method
    GanadevApi::sendMailMessage($send_to, $subject, $message)
}

```
### Method Send Mail Media
You can send email message (with image or other media) using methode name `sendMailMedia`, this method required 5 paramaeters `send_to`,`subject`,  `message`, `filename`, and `link`. Example usage :
```php
use DeyanArdi\GanadevNotif\GanadevApi;

public function yourExampleFunction(){
    $send_to = "yourtargetemail@gmail.com";
    $subject = "Test Subject";
    $filename = "test.jpg"; // Filename must with file extension, support all document (docx, doc, xls, xlsx, video, audio, image, pdf dan zip)
    $link = "https://[yourdomain]/images/test.jpg"; //example link of image or other media

    // Using Text Message
    $message = "Text Message"
    GanadevApi::sendMailMedia($send_to, $subject, $message, $filename, $link)

    // Using Laravel Views
    $message = view('emails.exampleNotification', compact('subject','send_to'))->render(); // Your custom view with render method
    GanadevApi::sendMailMedia($send_to, $subject, $message, $filename, $link)
}

```
### Method Send WhatsApp Message
You can send whatsapp message (without image) using methode name `sendWaMessage`, this method required 2 paramaeters `send_to` and `message`. Example usage :
```php
use DeyanArdi\GanadevNotif\GanadevApi;

public function yourExampleFunction(){
    $send_to = "81915003004" // Only support Indonesian Number, indonesian code (+62,62,0) in first number must be delete
    $message = "Text Message"
    GanadevApi::sendWaMessage($send_to, $message)
}

```
### Method Send WhatsApp Media
You can send whatsapp media (with image or other media) using methode name `sendWaMedia`, this method required 2 paramaeters `send_to`, `link`, and `message`. Example usage :
```php
use DeyanArdi\GanadevNotif\GanadevApi;

public function yourExampleFunction(){
    $send_to = "81915003004" // Only support Indonesian Number, indonesian code (+62,62,0) in first number must be delete
    $message = "Text Message"
    $link = "https://[yourdomain]/images/test.jpg"; //example link of image or other media, support all document (docx, doc, xls, xlsx, video, audio, image, pdf dan zip)
    GanadevApi::sendWaMedia($send_to, $link, $message)
}

```
### Method Get Detail Device
The term "Device" means the configuration that contains your email and whatsapp data.You can get detail of device you use by using method `getSingleDevice`, this method not required parameter. Example usage :
```php
use DeyanArdi\GanadevNotif\GanadevApi;

public function yourExampleFunction(){
    GanadevApi::getSingleDevice() // This method return detail of your device using in app, this request using api token to get
}

```
### Method Get Status And Detail App
The term "App" means any application that accesses your "Device". You can get status app using method `getStatusApp`, this method not required parameter. Example usage :
```php
use DeyanArdi\GanadevNotif\GanadevApi;

public function yourExampleFunction(){
    GanadevApi::getStatusApp() // This method return detail of your app, this request using api token to get data
}

```
## Contributing

-   [GanaDev Com](https://ganadev.com)

## License

The Laravel Ganadev Notification Service Package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
