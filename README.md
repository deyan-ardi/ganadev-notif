
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
| 4.2.x | No |
| 5.x.x | No |
| 6.x.x | No |
| 7.x.x | Yes |
| 8.x.x | Yes |
| 9.x.x | Yes |
| 10.x.x | Yes |

## System Requerements

- PHP "^7.3|7.4|^8.0"
- Guzzle HTTP (Default By Laravel, No Need To Install)
## Installation
- Open terminal, run `composer require deyan-ardi/ganadev-notif`
- After successful, run `php artisan vendor:publish --provider="DeyanArdi\GanadevNotif\GanadevServiceProvider" --tag="config"` to publish config file name `ganadevnotif.php` in config folder Laravel.
- Add key `GANADEV_EMAIL_STATUS` with boolean value in project `.env` file. The default value for this configuration is `true`, you can change value to `false` if you want to use local email configuration.
- Add key `GANADEV_NOTIF_TOKEN` with your API Token in project `.env` file

## Ways of working
- You must register the Email, WhatsApp Contact, and Other Configuration that you will use for sending notifications on the Ganadev Notification Sender API
- Next, you will get an API Token which can be used to access the Ganadev Notification Sender API through your application
- When you use the Ganadev Notification Sender API, the local email delivery configuration you are using will be automatically overwritten with the configuration you added to the Ganadev Notification Sender API.
- You can still use your local email configuration by default when Ganadev Notification Sender API server is down or when you give false value to key GANADEV_NOTIF_STATUS in project .env file
## Support
To get an API Token for your application, you can contact the GanaDev Com via https://ganadev.com/kontak. Please prepare your email and WhatsApp number before register your application
## How To Use


## Contributing

-   [GanaDev Com](https://ganadev.com)

## License

The Laravel Ganadev Notification Service Package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
