<?php

namespace DeyanArdi\GanadevNotif;

use Illuminate\Support\Facades\Config;

class GanadevApiEmailReplace
{
    /**
     * API Token
     *
     * @var string
     */
    protected $api_token;

    /**
     * API Email Status
     *
     * @var bool
     */
    protected $api_email_status;

    /**
     * Ganadevkey.json
     *
     * @var string
     */
    protected $ganadev_key = __DIR__ . '/../ganadevkey.json';

    public function __construct()
    {
        $this->api_email_status = config('ganadevnotif.api_email_status');
        $this->api_token = config('ganadevnotif.api_token');
    }


    public function run()
    {
        if (isset($this->api_token)) {
            if ($this->api_email_status == true) {
                $con = new GanadevApiService();
                $get_mail_data = $con->getSingleDevice();
                if ($get_mail_data['status'] == 200) {
                    $config_email = [
                        'mailer' => 'smtp',
                        'host' => $get_mail_data['data']['appEmailHost'],
                        'port' => $get_mail_data['data']['appEmailPort'],
                        'encryption' => 'ssl',
                        'username' => $get_mail_data['data']['appEmailUsername'],
                        'password' => $get_mail_data['data']['appEmailPassword'],
                        'name' => $get_mail_data['data']['appEmailFromName'],
                    ];
                    $checkIsSame = $this->checkDataIsSame($config_email);
                    if (!$checkIsSame) {
                        file_put_contents($this->ganadev_key, "");
                        $json = json_encode($config_email);
                        file_put_contents($this->ganadev_key, $json);
                    }

                    $replaceConfig = $this->replaceConfig();
                    if ($replaceConfig) {
                        logger('GanadevNotifReplaceEmail = SUCCESS CHANGE LOCAL CONFIG');
                    } else {
                        logger('GanadevNotifReplaceEmail = FAILED CHANGE LOCAL CONFIG');
                    }
                }
            } else {
                file_put_contents($this->ganadev_key, "");
                logger('GanadevNotifReplaceEmail = USING USER LOCAL CONFIG, API_EMAIL_STATUS = FALSE');
            }
        } else {
            logger('GanadevNotifReplaceEmail = MISSING API TOKEN');
        }
    }

    public function checkDataIsSame($data)
    {
        if (file_exists($this->ganadev_key)) {
            $ganadev_key = file_get_contents($this->ganadev_key);
            $array = json_decode($ganadev_key, true);
            if (!empty($array)) {
                if ($array['mailer'] == $data['mailer'] && $array['host'] == $data['host'] && $array['port'] == $data['port'] && $array['encryption'] == $data['encryption'] && $array['username'] == $data['username'] && $array['password'] == $data['password'] && $array['name'] == $data['name']) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function replaceConfig()
    {
        if (file_exists($this->ganadev_key)) {
            $ganadev_key = file_get_contents($this->ganadev_key);
            $array = json_decode($ganadev_key, true);
            if (!empty($array)) {
                Config::set('mail.mailers.smtp.transport', $array['mailer']);
                Config::set('mail.mailers.smtp.host', $array['host']);
                Config::set('mail.mailers.smtp.port', $array['port']);
                Config::set('mail.mailers.smtp.encryption', $array['encryption']);
                Config::set('mail.mailers.smtp.username', $array['username']);
                Config::set('mail.mailers.smtp.password', $array['password']);
                Config::set('mail.from.address', $array['username']);
                Config::set('mail.from.name', $array['name']);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
