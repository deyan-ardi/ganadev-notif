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
     * Use Mail Server Settings
     *
     * @var bool
     */
    protected $use_mail_server_setting;


    /**
     * Idle Time Access
     *
     * @var integer
     */
    protected $idle_time;

    /**
     * Signature File
     *
     * @var string
     */
    protected $signature_file = __DIR__ . '/../signature.ganadevkey.json';

    /**
     * Response To Data
     *
     * @var integer
     */
    protected $response_to;

    public function __construct()
    {
        $this->use_mail_server_setting = config('ganadevnotif.use_mail_server_setting');
        $this->idle_time = config('ganadevnotif.idle_time');
        $this->api_token = config('ganadevnotif.api_token');
        $this->response_to = config('ganadevnotif.response_to');
    }

    public function checkIdleTime()
    {
        $idle_time = "+15 minutes";
        if ($this->idle_time == 30) {
            $idle_time = "+30 minutes";
        }
        if ($this->idle_time == 60) {
            $idle_time = "+60 minutes";
        }
        return $idle_time;
    }

    public function removeKey()
    {
        if (file_exists($this->signature_file)) {
            if (file_exists($this->getKey())) {
                if (!unlink($this->getKey())) {
                    return true;
                } else {
                    return false;
                }
            }
            return true;
        }
        logger('GanadevNotifReplaceEmail: SIGNATURE FILE NOT FOUND');
    }

    public function getKey()
    {
        if (file_exists($this->signature_file)) {
            $signature_file = file_get_contents($this->signature_file);
            $signature_file_array = json_decode($signature_file, true);
            if (!empty($signature_file_array)) {
                $key = __DIR__ . '/' . $signature_file_array['file_name'];
                return  $key;
            }
            logger('GanadevNotifReplaceEmail: SIGNATURE FILE EMPTY');
        }
        logger('GanadevNotifReplaceEmail: SIGNATURE FILE NOT FOUND');
    }

    public function generateSignatureFile()
    {
        if (file_exists($this->signature_file)) {
            $this->removeKey();
            file_put_contents($this->signature_file, "");
        }

        $data = [
            'created_at' => time(),
            'expired_at' => strtotime($this->checkIdleTime(), time()),
            'file_name'  => strtotime($this->checkIdleTime(), time()) . ".ganadevkey.json",
        ];
        $json = json_encode($data);
        file_put_contents($this->signature_file, $json);
        return true;
    }

    public function checkValidSignatureFile()
    {
        if (file_exists($this->signature_file)) {
            $signature_file = file_get_contents($this->signature_file);
            $array = json_decode($signature_file, true);
            if (!empty($array)) {
                if (isset($array['created_at']) && isset($array['expired_at']) && isset($array['file_name'])) {
                    if (time() <= $array['expired_at']) {
                        if (file_exists($this->getKey())) {
                            $ganadev_key = file_get_contents($this->getKey());
                            $array = json_decode($ganadev_key, true);
                            if (!empty($array)) {
                                return true;
                            }
                        }
                    }
                }
            }
        }
        logger('GanadevNotifReplaceEmail: INVALID SIGNATURE FILE');
        return false;
    }


    public function run()
    {
        if (isset($this->api_token)) {
            if ($this->use_mail_server_setting == true) {
                if ($this->checkValidSignatureFile()) {
                    $replaceConfig = $this->replaceConfig();
                    if ($replaceConfig) {
                        logger('GanadevNotifReplaceEmail: SUCCESS CHANGE LOCAL CONFIG');
                    } else {
                        logger('GanadevNotifReplaceEmail: FAILED CHANGE LOCAL CONFIG');
                    }
                } else {
                    $this->generateSignatureFile();

                    $con = new GanadevApiService();
                    $get_mail_data = $con->getDevice();
                    if ($this->response_to == "array") {
                        $status_devices = $get_mail_data['status'];
                        $data_body = $get_mail_data;
                    } else {
                        $to_json_data = json_decode($get_mail_data->getContent(), true);
                        $data_body = $to_json_data;
                        $status_devices = $to_json_data['status'];
                    }
                    if ($status_devices == 200) {
                        $config_email = [
                            'mailer' => 'smtp',
                            'host' => $data_body['data']['app_email_host'],
                            'port' => $data_body['data']['app_email_port'],
                            'encryption' => $data_body['data']['app_email_port'] == "465" ? "ssl" : "tls",
                            'username' => $data_body['data']['app_email_username'],
                            'password' => $data_body['data']['app_email_password'],
                            'name' => $data_body['data']['app_email_from_name'],
                        ];
                        $checkIsSame = $this->checkDataIsSame($config_email);
                        if (!$checkIsSame) {
                            file_put_contents($this->getKey(), "");
                            $json = json_encode($config_email);
                            file_put_contents($this->getKey(), $json);
                        }

                        $replaceConfig = $this->replaceConfig();
                        if ($replaceConfig) {
                            logger('GanadevNotifReplaceEmail: SUCCESS CHANGE LOCAL CONFIG');
                        } else {
                            logger('GanadevNotifReplaceEmail: FAILED CHANGE LOCAL CONFIG');
                        }
                    }
                }
            } else {
                if ($this->getKey()) {
                    file_put_contents($this->getKey(), "");
                }
                logger('GanadevNotifReplaceEmail: USING USER LOCAL CONFIG, USING MAIL SERVER SETTING = FALSE');
            }
        } else {
            logger('GanadevNotifReplaceEmail: MISSING API TOKEN');
        }
    }

    public function checkDataIsSame($data)
    {
        if (file_exists($this->getKey())) {
            $ganadev_key = file_get_contents($this->getKey());
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
        if (file_exists($this->getKey())) {
            $ganadev_key = file_get_contents($this->getKey());
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
