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
     * API Device
     *
     *  @var string
     */
    protected $api_device;



    public function __construct()
    {
        $this->api_token = config('ganadevnotif.api_token');
        $this->api_url = config('ganadevnotif.api_url');
        $this->api_device = config('ganadevnotif.api_device');
    }


    public function sendMailMessage($to, $subject, $text)
    {
        try {
            $data = [
                'api_key' => $this->api_token,
                'sender' => $this->api_device,
                'subject' => $subject,
                'to' => $to,
                'html' => $text,
            ];
            $url = $this->api_url . '/api/email/send-message';
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
            $response = ParsedApiServices::parse($body);
            return $response;
        } catch (Exception $e) {
            logger('GanadevNotifAPI: Failed Send Request, Error:' . $e->getMessage());
            $body = [
                'status' => false,
                'msg' => $e->getMessage(),
            ];
            $response = ParsedApiServices::parse($body);
            return $response;
        }
    }

    public function sendMailMedia($to, $subject, $text, $filename, $link, $mime_type)
    {
        try {

            $data = [
                'api_key' => $this->api_token,
                'sender' => $this->api_device,
                'subject' => $subject,
                'to' => $to,
                'html' => $text,
                'url' => $link,
                'filename' => $filename,
                'mime_type' => $mime_type
            ];
            $url = $this->api_url . '/api/email/send-media';
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
            $response = ParsedApiServices::parse($body);
            return $response;
        } catch (Exception $e) {
            logger('GanadevNotifAPI: Failed Send Request, Error:' . $e->getMessage());
            $body = [
                'status' => false,
                'msg' => $e->getMessage(),
            ];
            $response = ParsedApiServices::parse($body);
            return $response;
        }
    }

    public function sendWaMessage($receiver, $message)
    {
        try {

            $data = [
                'api_key' => $this->api_token,
                'sender' => $this->api_device,
                'number' => intval($receiver),
                'message' => $message,
            ];
            $url = $this->api_url . '/api/wa/send-message';
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
            $response = ParsedApiServices::parse($body);
            return $response;
        } catch (Exception $e) {
            logger('GanadevNotifAPI: Failed Send Request, Error:' . $e->getMessage());
            $body = [
                'status' => false,
                'msg' => $e->getMessage(),
            ];
            $response = ParsedApiServices::parse($body);
            return $response;
        }
    }

    public function sendWaMedia($receiver, $file, $type, $other = null)
    {
        try {
            $data = [
                'api_key' => $this->api_token,
                'sender' => $this->api_device,
                'number' => intval($receiver), //include string 62 to the front of user's phone number
                'url' => $file,
                'media_type' => $type,
            ];

            if ($type == "image" || $type == "video") {
                $data['caption'] = $other;
            }

            if ($type == "audio") {
                if (!isset($other)) {
                    $body = [
                        'status' => false,
                        'msg' => "Required 1 parameter, other value is required if type is audio, true for voice note | false for audio",
                    ];
                    $response = ParsedApiServices::parse($body);
                    return $response;
                }

                if ($other != "true" && $type == "false") {
                    $body = [
                        'status' => false,
                        'msg' => "Value must be true for voice note or false for audio",
                    ];
                    $response = ParsedApiServices::parse($body);
                    return $response;
                }

                $data['ppt'] = $other;
            }

            $url = $this->api_url . '/api/wa/send-media';
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
            $response = ParsedApiServices::parse($body);
            return $response;
        } catch (Exception $e) {
            logger('GanadevNotifAPI: Failed Send Request, Error:' . $e->getMessage());
            $body = [
                'status' => false,
                'msg' => $e->getMessage(),
            ];
            $response = ParsedApiServices::parse($body);
            return $response;
        }
    }

    public function getDevice()
    {
        try {
            $data = [
                'api_key' => $this->api_token,
                'sender' => $this->api_device,
            ];
            $url = $this->api_url . '/api/device';
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
            $response = ParsedApiServices::parse($body);
            return $response;
        } catch (Exception $e) {
            logger('GanadevNotifAPI: Failed Send Request, Error:' . $e->getMessage());
            $body = [
                'status' => false,
                'msg' => $e->getMessage(),
            ];
            $response = ParsedApiServices::parse($body);
            return $response;
        }
    }
}
