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

    public function __construct()
    {
        $this->api_token = config('ganadevnotif.api_token');
        $this->api_url = "https://sv1.notif.ganadev.com";
    }


    public function sendMailMessage($to, $subject, $text)
    {
        try {
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
        } catch (Exception $e) {
            $info = [
                'status' => '500',
                'data' => [
                    'waNotifStatus' => 0,
                    'emailNotifStatus' => 0,
                ],
                'info' => 'Fitur ini sedang dalam perbaikan',
                'error' => $e->getMessage(),
            ];

            return $info;
        }
    }

    public function sendMailMedia($to, $subject, $text, $filename, $link)
    {
        try {
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
        } catch (Exception $e) {
            $info = [
                'status' => '500',
                'data' => [
                    'waNotifStatus' => 0,
                    'emailNotifStatus' => 0,
                ],
                'info' => 'Fitur ini sedang dalam perbaikan',
                'error' => $e->getMessage(),
            ];

            return $info;
        }
    }

    public function sendWaMessage($receiver, $message)
    {
        try {
            $data = [
                'apiToken' => $this->api_token,
                'no_hp' => intval('62' . $receiver), //include string 62 to the front of user's phone number
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
        } catch (Exception $e) {
            $info = [
                'status' => '500',
                'data' => [
                    'waNotifStatus' => 0,
                    'emailNotifStatus' => 0,
                ],
                'info' => 'Fitur ini sedang dalam perbaikan',
                'error' => $e->getMessage(),
            ];

            return $info;
        }
    }

    public function sendWaMedia($receiver, $file, $message)
    {
        try {
            $data = [
                'apiToken' => $this->api_token,
                'no_hp' => (int) '62' . $receiver, //include string 62 to the front of user's phone number
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
        } catch (Exception $e) {
            $info = [
                'status' => '500',
                'data' => [
                    'waNotifStatus' => 0,
                    'emailNotifStatus' => 0,
                ],
                'info' => 'Fitur ini sedang dalam perbaikan',
                'error' => $e->getMessage(),
            ];

            return $info;
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
            // dd($e);
            $info = [
                'status' => '500',
                'data' => [
                    'waNotifStatus' => 0,
                    'emailNotifStatus' => 0,
                ],
                'info' => 'Fitur ini sedang dalam perbaikan',
                'error' => $e->getMessage(),
            ];

            return $info;
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
            $info = [
                'status' => '500',
                'data' => [
                    'waNotifStatus' => 0,
                    'emailNotifStatus' => 0,
                ],
                'info' => 'Fitur ini sedang dalam perbaikan',
                'error' => $e->getMessage(),
            ];

            return $info;
        }
    }
}
