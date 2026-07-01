<?php

namespace Chestnov\Notificationsmax;

use Bitrix\Main\Web\Http\Method;
use Bitrix\Main\Web\Http\MultipartStream;
use Bitrix\Main\Web\Http\Request;
use Bitrix\Main\Web\HttpClient;
use Bitrix\Main\Web\Uri;
use Exception;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
class MaxMessengerNewsSender
{
    private string $accessToken;
    private string $chatId;

    private const API_URL = 'https://platform-api2.max.ru/';

    public function __construct(string $accessToken, string $chatId)
    {
        $this->accessToken = $accessToken;
        $this->chatId = $chatId;
    }

    private function getHttpClient(): HttpClient
    {
        return new HttpClient([
            'socketTimeout' => 30,
            'streamTimeout' => 30,
        ]);
    }

    private function getUploadUrl(): string
    {
        $http = $this->getHttpClient();
        $http->setHeader('Authorization', $this->accessToken, true);

        $response = $http->post(
            self::API_URL . 'uploads?' . http_build_query([
                'type' => 'image'
            ])
        );

        $data = json_decode($response, true);

        if (empty($data['url'])) {
            throw new Exception(Loc::getMessage('CHESTNOV_NOTIFICATIONSMAX_URL_ERROR') . $response);
        }

        return $data['url'];
    }

    public function uploadImage(string $imagePath): string
    {
        if (!file_exists($imagePath)) {
            throw new Exception(Loc::getMessage('CHESTNOV_NOTIFICATIONSMAX_FILE_NOT_ERROR') . $imagePath);
        }

        $uploadUrl = $this->getUploadUrl();

        $resource = fopen($imagePath, 'r');

        if (!$resource) {
            throw new Exception(Loc::getMessage('CHESTNOV_NOTIFICATIONSMAX_FILE_OPEN_ERROR'));
        }

        $data = [
            'data' => [
                'resource' => $resource,
                'filename' => basename($imagePath),
            ],
        ];

        $body = new MultipartStream($data);

        $headers = [
            'Content-Type' => 'multipart/form-data; boundary=' . $body->getBoundary(),
        ];

        $request = new Request(
            Method::POST,
            new Uri($uploadUrl),
            $headers,
            $body
        );

        $http = $this->getHttpClient();
        $response = $http->sendRequest($request);

        fclose($resource);

        $result = json_decode((string)$response->getBody(), true);

        if (empty($result['photos']) || !is_array($result['photos'])) {
            throw new Exception(
                Loc::getMessage('CHESTNOV_NOTIFICATIONSMAX_UPLOAD_ERROR') .
                (string)$response->getBody()
            );
        }

        $photo = reset($result['photos']);

        if (empty($photo['token'])) {
            throw new Exception(
                Loc::getMessage('CHESTNOV_NOTIFICATIONSMAX_TOKEN_ERROR_IMG') .
                (string)$response->getBody()
            );
        }

        return $photo['token'];
    }

    /*
     * $message['IMAGES']
     * $message['BTN_NAME']
     * $message['URL']
     * $message['TEXT']
     */

    public function send(array $message): array
    {
        $attachments = [];

        if (!empty($message['IMAGE'])) {
            $imageToken = $this->uploadImage($message['IMAGE']);

            $attachments[] = [
                'type' => 'image',
                'payload' => [
                    'token' => $imageToken
                ]
            ];
        }
        if (!empty($message['URL'])) {
            $message['BTN_NAME']=!empty($message['BTN_NAME'])?$message['BTN_NAME']:Loc::getMessage('CHESTNOV_NOTIFICATIONSMAX_BTN_NAME');
            $attachments[] = [
                'type' => 'inline_keyboard',
                'payload' => [
                    'buttons' => [
                        [
                            [
                                'type' => 'link',
                                'text' => $message['BTN_NAME'],
                                'url' => $message['URL']
                            ]
                        ]
                    ]
                ]
            ];
        }

        $body = json_encode([
            'text' => $message['TEXT'],
            'attachments' => $attachments
        ], JSON_UNESCAPED_UNICODE);

        $http = $this->getHttpClient();
        $http->setHeader('Authorization', $this->accessToken, true);
        $http->setHeader('Content-Type', 'application/json', true);

        $response = $http->post(
            self::API_URL . 'messages?' . http_build_query([
                'chat_id' => $this->chatId
            ]),
            $body
        );

        return [
            'HTTP_CODE' => $http->getStatus(),
            'RESPONSE' => $response
        ];
    }
}