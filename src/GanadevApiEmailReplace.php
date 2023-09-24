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

    /**
     * Device Id
     *
     * @var integer
     */
    protected $device_id;

    public function __construct()
    {
        $this->use_mail_server_setting = config('ganadevnotif.use_mail_server_setting');
        $this->idle_time = config('ganadevnotif.idle_time');
        $this->api_token = config('ganadevnotif.api_token');
        $this->response_to = config('ganadevnotif.response_to');
        $this->device_id = config('ganadevnotif.api_device');
    }

    // ==========================================
    // Star Auto Run Program
    // ==========================================
    public function run()
    {
        if (!isset($this->api_token)) {
            logger('GanadevNotifReplaceEmail: MISSING API TOKEN');
            return;
        }
        if (!$this->use_mail_server_setting) {
            if ($this->getKey()) {
                file_put_contents($this->getKey(), "");
            }
            logger('GanadevNotifReplaceEmail: USING USER LOCAL CONFIG, USING MAIL SERVER SETTING = FALSE');
            return;
        }
        if (!$this->checkValidSignatureFile()) {
            $this->generateAndReplace();
        }

        if (!$this->replaceConfig()) {
            logger('GanadevNotifReplaceEmail: FAILED CHANGE LOCAL CONFIG');
        }
    }
    // ==========================================
    // End Auto Run Program
    // ==========================================

    private function checkIdleTime()
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

    private function removeKey()
    {
        if (!file_exists($this->signature_file)) {
            logger('GanadevNotifReplaceEmail: SIGNATURE FILE NOT FOUND');
            return;
        }
        if (!file_exists($this->getKey()) || !unlink($this->getKey())) {
            return true;
        }
        return false;
    }

    private function getKey()
    {
        if (!file_exists($this->signature_file)) {
            logger('GanadevNotifReplaceEmail: SIGNATURE FILE NOT FOUND');
            return;
        }
        $signature_file = file_get_contents($this->signature_file);
        $signature_file_array = json_decode($signature_file, true);
        if (empty($signature_file_array)) {
            logger('GanadevNotifReplaceEmail: SIGNATURE FILE EMPTY');
            return;
        }
        $key = __DIR__ . '/' . $signature_file_array['file_name'];
        return  $key;
    }

    private function generateSignatureFile()
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
        return file_put_contents($this->signature_file, $json); // true on success
    }

    private function checkValidSignatureFile()
    {
        if (!file_exists($this->signature_file)) {
            logger('GanadevNotifReplaceEmail: SIGNATURE FILE NOT FOUND');
            return false;
        }
        $signature_file = file_get_contents($this->signature_file);
        $array = json_decode($signature_file, true);
        if (empty($array)) {
            logger('GanadevNotifReplaceEmail: INVALID SIGNATURE FILE');
            return false;
        }
        if (!isset($array['created_at']) || !isset($array['expired_at']) || !isset($array['file_name'])) {
            logger('GanadevNotifReplaceEmail: INVALID SIGNATURE FILE');
            return false;
        }
        if (time() > $array['expired_at']) {
            logger('GanadevNotifReplaceEmail: SIGNATURE FILE EXPIRED');
            return false;
        }
        if (!file_exists($this->getKey())) {
            logger('GanadevNotifReplaceEmail: KEY FILE NOT FOUND');
            return false;
        }
        $array = $this->decryptData();
        if (empty($array)) {
            logger('GanadevNotifReplaceEmail: INVALID KEY FILE');
            return false;
        }
        return true;
    }

    private function generateAndReplace()
    {
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
                $encrypt = $this->encryptData($json);
                file_put_contents($this->getKey(), $encrypt);
            }
        }
    }

    private function checkDataIsSame($data)
    {
        if (!file_exists($this->getKey())) {
            return false;
        }

        $array = $this->decryptData();
        if (empty($array)) {
            return false;
        }
        if ($array['mailer'] == $data['mailer'] && $array['host'] == $data['host'] && $array['port'] == $data['port'] && $array['encryption'] == $data['encryption'] && $array['username'] == $data['username'] && $array['password'] == $data['password'] && $array['name'] == $data['name']) {
            return true;
        } else {
            return false;
        }
    }

    // ==========================================
    // Start Replace Configuration
    // ==========================================
    private function replaceConfig()
    {
        if (!file_exists($this->getKey())) {
            return false;
        }

        $array = $this->decryptData();
        if (empty($array)) {
            return false;
        }

        Config::set('mail.mailers.ganadev.transport', $array['mailer']);
        Config::set('mail.mailers.ganadev.host', $array['host']);
        Config::set('mail.mailers.ganadev.port', $array['port']);
        Config::set('mail.mailers.ganadev.encryption', $array['encryption']);
        Config::set('mail.mailers.ganadev.username', $array['username']);
        Config::set('mail.mailers.ganadev.password', $array['password']);
        Config::set('mail.from.address', $array['username']);
        Config::set('mail.from.name', $array['name']);
        
        return true;
    }
    // ==========================================
    // End Replace Configuration
    // ==========================================

    // ==========================================
    // Start Encryption Data For Security 
    // ==========================================
    private function getSecretKey()
    {
        return $this->device_id . $this->api_token;
    }

    private function encryptData($responseData)
    {
        $key = $this->getSecretKey();
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encryptedData = openssl_encrypt($responseData, 'aes-256-cbc', $key, 0, $iv);
        return base64_encode($iv . $encryptedData);
    }

    private function decryptData()
    {
        $key = $this->getSecretKey();
        $encryptedData = file_get_contents($this->getKey());

        $decodedData = base64_decode($encryptedData);
        $ivSize = openssl_cipher_iv_length('aes-256-cbc');
        $iv = substr($decodedData, 0, $ivSize);
        $encryptedDataWithoutIv = substr($decodedData, $ivSize);
        $decryptedData = openssl_decrypt($encryptedDataWithoutIv, 'aes-256-cbc', $key, 0, $iv);
        $decodeJson = json_decode($decryptedData, true);

        return $decodeJson;
    }
    // ==========================================
    // End Encryption Data For Security 
    // ==========================================
}