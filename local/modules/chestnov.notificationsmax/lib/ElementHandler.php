<?php

namespace Chestnov\Notificationsmax;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class ElementHandler
{
    private static ?string $accessToken = null;
    private static ?string $chatId = null;
    private static ?int $iblockId = null;

    public static function init()
    {
        self::$accessToken = Option::get('chestnov.notificationsmax', 'BOT_TOKEN');
        self::$chatId = Option::get('chestnov.notificationsmax', 'CHAT_ID');
        self::$iblockId = (int)Option::get('chestnov.notificationsmax', 'IBLOCK_ID');
    }

    private static function getDomain()
    {
        $request = Context::getCurrent()->getRequest();
        $protocol = $request->isHttps() ? 'https' : 'http';
        $host = $request->getHttpHost();
        return $protocol . '://' . $host;
    }

    private static function getSender()
    {
        return new MaxMessengerNewsSender(self::$accessToken, self::$chatId);
    }

    public static function onAfterElementAdd(array &$fields)
    {

        try {
            self::init();

            if (!self::$accessToken || !self::$chatId || !self::$iblockId) {
                return;
            }

            if (!Loader::includeModule('iblock')) {
                return;
            }

            if ((int)$fields['IBLOCK_ID'] != self::$iblockId) {
                return;
            }

            $link = self::getDomain() . \CIBlockElement::GetByID($fields['ID'])->GetNext()['DETAIL_PAGE_URL'];
            $message = [
                'TEXT' => $fields['PREVIEW_TEXT'],
                'BTN_NAME' => $fields['NAME'],
                'URL' => $link
            ];

            if (!empty($fields['PREVIEW_PICTURE_ID'])) {
                $message['IMAGE'] = $_SERVER['DOCUMENT_ROOT'] . \CFile::GetPath($fields['PREVIEW_PICTURE_ID']);
            }

            $res = self::getSender()->send($message);

            Logger::save(Loc::getMessage('CHESTNOV_NOTIFICATIONSMAX_DEFAULT_EVENT_NAME'), (int)$fields['ID'], print_r($res, true));

        } catch (\Throwable $ex) {
            Logger::save( Loc::getMessage('CHESTNOV_NOTIFICATIONSMAX_DEFAULT_EVENT_ERROR') , (int)$fields['ID'], $ex->getMessage());
            \CAdminMessage::ShowMessage([
                'MESSAGE' => Loc::getMessage('CHESTNOV_NOTIFICATIONSMAX_DEFAULT_EVENT_ERROR') . $ex->getMessage(),
                'TYPE' => 'ERROR',
            ]);
        }
    }
}