<?php

namespace DeyanArdi\GanadevNotif;

use Illuminate\Support\Facades\Facade;

/**
 * @see \DeyanArdi\GanadevApi\
 * @method static void getDevice()
 * @method static void sendWaMedia($send_to, $link, $type, $other)
 * @method static void sendWaMessage($send_to, $message)
 * @method static void sendMailMessage($send_to, $subject, $message)
 * @method static void sendMailMedia($send_to, $subject, $message, $filename, $link, $mime_type)
 */
class GanadevApi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ganadevnotif';
    }
}