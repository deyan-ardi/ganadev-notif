<?php

namespace DeyanArdi\GanadevNotif;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class GanadevApiService
{
    /**
     * API Token
     *
     * @var string
     */
    protected $api_token;

    /**
     * API Url
     *
     * @var string
     */
    protected $api_url;

    /**
     * API Device Id
     *
     * @var integer
     */
    protected $api_device;

    /**
     * API Must Queue Setting
     *
     * @var boolean
     */
    protected $must_queue;

    public function __construct()
    {
        $this->api_token = config('ganadevnotif.api_token');
        $this->api_url = config('ganadevnotif.api_url');
        $this->api_device = config('ganadevnotif.api_device');
        $this->must_queue = config('ganadevnotif.must_queue');
    }

    public function sendMailMessage($to, $subject, $text)
    {
        return $this->mustQueueConfig('email/send-message', [
            'subject' => $subject,
            'to' => $to,
            'html' => $text,
        ]);
    }

    public function sendMailMedia($to, $subject, $text, $filename, $link, $mime_type)
    {
        return $this->mustQueueConfig('email/send-media', [
            'subject' => $subject,
            'to' => $to,
            'html' => $text,
            'url' => $link,
            'filename' => $filename,
            'mime_type' => $mime_type,
        ]);
    }

    public function sendWaMessage($receiver, $message)
    {
        return $this->mustQueueConfig('wa/send-message', [
            'number' => intval($receiver),
            'message' => $message,
        ]);
    }

    public function sendWaMedia($receiver, $file, $type, $other = null)
    {
        $data = [
            'number' => intval($receiver),
            'url' => $file,
            'media_type' => $type,
        ];

        if ($type == "image" || $type == "video") {
            $data['caption'] = $other;
        }

        if ($type == "audio") {
            if (!isset($other)) {
                return $this->errorResponse("Required 1 parameter, other value is required if type is audio, true for voice note | false for audio");
            }

            if ($other != "true" && $type == "false") {
                return $this->errorResponse("Value must be true for voice note or false for audio");
            }

            $data['ppt'] = $other;
        }

        return $this->mustQueueConfig('wa/send-media', $data);
    }

    public function getDevice()
    {
        return $this->mustQueueConfig('device', [], false);
    }

    private function mustQueueConfig($endpoint, $data, $send_with_queue = true)
    {
        if (!$this->must_queue || !$send_with_queue) {
            return $this->sendRequest($endpoint, $data);
        }

        dispatch(function () use ($endpoint, $data) {
            logger("GanadevApiService", ["info" => "Message Send In Background Process"]);
            return $this->sendRequest($endpoint, $data);
        });
        
        $body = [
            "status" => 200,
            "msg" => "Message Send In Background Process"
        ];
        return ParsedApiServices::parse($body);
    }

    private function sendRequest($endpoint, $data)
    {
        try {
            $data['api_key'] = $this->api_token;
            $data['sender'] = $this->api_device;

            $url = "{$this->api_url}/api/{$endpoint}";
            $client = new Client();
            $response = $client->request(
                'POST',
                $url,
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'body' => json_encode($data),
                ]
            );

            $body = json_decode($response->getBody(), true);
            return ParsedApiServices::parse($body);
        } catch (GuzzleException | Exception $e) {
            $errorMessage = $e->getMessage();
            logger("GanadevNotifAPI: Failed Send Request, Error: $errorMessage");

            return $this->errorResponse($errorMessage);
        }
    }

    private function errorResponse($message)
    {
        $body = [
            'status' => false,
            'msg' => $message,
            'data' => [
                'wa_notif_status' => 0,
                'email_notif_status' => 0,
            ],
        ];

        return ParsedApiServices::parse($body);
    }
}