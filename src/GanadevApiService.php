<?php

namespace DeyanArdi\GanadevNotif;

use Exception;
use GuzzleHttp\Client;

class GanadevApiService
{
    /**
     * API Token
     *
     * @var string
     */
    protected $api_token;

    /**
     * API URL
     *
     *  @var string
     */
    protected $api_url;

    /**
     * WhatsApp API Status
     *
     *  @var string
     */
    protected $api_wa_status;

    /**
     * Email API Status
     *
     *  @var string
     */
    protected $api_email_status;

    public function __construct()
    {
        $this->api_token = config('ganadevnotif.api_token');
        $this->api_url = config('ganadevnotif.api_url');
        $this->api_wa_status = config('ganadevnotif.api_wa_status');
        $this->api_email_status = config('ganadevnotif.api_email_status');
    }


    public function sendMailMessage($to, $subject, $text)
    {
        try {
            if ($this->api_email_status == true) {

                $data = [
                    'apiToken' => $this->api_token,
                    'to' => $to,
                    'subject' => $subject,
                    'html' => $text,
                ];
                $url = $this->api_url . '/email/send/message';
                $client = new Client();
                $response = $client->request(
                    'POST',
                    $url,
                    [
                        // don't forget to set the header
                        'headers' => [
                            'Content-Type' => 'application/json',
                        ],
                        'body' => json_encode($data),
                    ]
                );
                $body = json_decode($response->getBody(), true);

                return $body;
            }

            logger('GanadevNotifAPI: Failed Send Request, Error: API WA STATUS DISABLED');
            return [
                'status' => 400,
                'info' => 'Bad Request',
                'error' => 'Pelase Set API Email Status To True',
            ];
        } catch (Exception $e) {
            logger('GanadevNotifAPI: Failed Send Request, Error:' . $e->getMessage());
            return  [
                'status' => 500,
                'info' => 'Internal Server Error',
                'error' => $e->getMessage(),
            ];
        }
    }

    public function sendMailMedia($to, $subject, $text, $filename, $link)
    {
        try {
            if ($this->api_email_status == true) {

                $data = [
                    'apiToken' => $this->api_token,
                    'to' => $to,
                    'subject' => $subject,
                    'html' => $text,
                    'filename' => $filename,
                    'link' => $link,
                ];
                $url = $this->api_url . '/email/send/media';
                $client = new Client();
                $response = $client->request(
                    'POST',
                    $url,
                    [
                        // don't forget to set the header
                        'headers' => [
                            'Content-Type' => 'application/json',
                        ],
                        'body' => json_encode($data),
                    ]
                );
                $body = json_decode($response->getBody(), true);

                return $body;
            }

            logger('GanadevNotifAPI: Failed Send Request, Error: API EMAIL STATUS DISABLED');
            return [
                'status' => 400,
                'info' => 'Bad Request',
                'error' => 'Pelase Set API Email Status To True',
            ];
        } catch (Exception $e) {
            logger('GanadevNotifAPI: Failed Send Request, Error:' . $e->getMessage());
            return  [
                'status' => 500,
                'info' => 'Internal Server Error',
                'error' => $e->getMessage(),
            ];
        }
    }

    public function sendWaMessage($receiver, $message, $kode_negara = null)
    {
        try {
            if ($this->api_wa_status == true) {
                if (isset($kode_negara)) {
                    $kode_negara = $kode_negara;
                } else {
                    $kode_negara = '62';
                }
                $data = [
                    'apiToken' => $this->api_token,
                    'no_hp' => intval($kode_negara . $receiver), //include string 62 to the front of user's phone number
                    'pesan' => $message,
                ];
                $url = $this->api_url . '/whatsapp/send/message';
                $client = new Client();
                $response = $client->request(
                    'POST',
                    $url,
                    [
                        // don't forget to set the header
                        'headers' => [
                            'Content-Type' => 'application/json',
                        ],
                        'body' => json_encode($data),
                    ]
                );

                $body = json_decode($response->getBody(), true);

                return $body;
            }

            logger('GanadevNotifAPI: Failed Send Request, Error: API WA STATUS DISABLED');
            return [
                'status' => 400,
                'info' => 'Bad Request',
                'error' => 'Pelase Set API WA Status To True',
            ];
        } catch (Exception $e) {
            logger('GanadevNotifAPI: Failed Send Request, Error:' . $e->getMessage());
            return  [
                'status' => 500,
                'info' => 'Internal Server Error',
                'error' => $e->getMessage(),
            ];
        }
    }

    public function sendWaMedia($receiver, $file, $message, $kode_negara = null)
    {
        try {
            if ($this->api_wa_status == true) {
                if (isset($kode_negara)) {
                    $kode_negara = $kode_negara;
                } else {
                    $kode_negara = '62';
                }
                $data = [
                    'apiToken' => $this->api_token,
                    'no_hp' => intval($kode_negara . $receiver), //include string 62 to the front of user's phone number
                    'pesan' => $message,
                    'link' => $file,
                ];
                $url = $this->api_url . '/whatsapp/send/media';
                $client = new Client();
                $response = $client->request(
                    'POST',
                    $url,
                    [
                        // don't forget to set the header
                        'headers' => [
                            'Content-Type' => 'application/json',
                        ],
                        'body' => json_encode($data),
                    ]
                );
                $body = json_decode($response->getBody(), true);

                return $body;
            }

            logger('GanadevNotifAPI: Failed Send Request, Error: API WA STATUS DISABLED');
            return [
                'status' => 400,
                'info' => 'Bad Request',
                'error' => 'Pelase Set API WA Status To True',
            ];
        } catch (Exception $e) {
            logger('GanadevNotifAPI: Failed Send Request, Error:' . $e->getMessage());
            return  [
                'status' => 500,
                'info' => 'Internal Server Error',
                'error' => $e->getMessage(),
            ];
        }
    }

    public function getSingleDevice()
    {
        try {
            $data = [
                'apiToken' => $this->api_token,
            ];
            $url = $this->api_url . '/target-api/single';
            $client = new Client();
            $response = $client->request(
                'POST',
                $url,
                [
                    // don't forget to set the header
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'body' => json_encode($data),
                ]
            );
            $body = json_decode($response->getBody(), true);

            return $body;
        } catch (Exception $e) {
            logger('GanadevNotifAPI: Failed Send Request, Error:' . $e->getMessage());
            return  [
                'status' => 500,
                'info' => 'Internal Server Error',
                'error' => $e->getMessage(),
            ];
        }
    }

    public function getStatusApp()
    {
        try {
            $data = [
                'apiToken' => $this->api_token,
            ];
            $url = $this->api_url . '/app-access/single';
            $client = new Client();
            $response = $client->request(
                'POST',
                $url,
                [
                    // don't forget to set the header
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'body' => json_encode($data),
                ]
            );
            $body = json_decode($response->getBody(), true);

            return $body;
        } catch (Exception $e) {
            logger('GanadevNotifAPI: Failed Send Request, Error:' . $e->getMessage());
            return  [
                'status' => 500,
                'info' => 'Internal Server Error',
                'error' => $e->getMessage(),
            ];
        }
    }
}
