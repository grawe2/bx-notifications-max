<?php

namespace Chestnov\Notificationsmax;
use Bitrix\Main\Type\DateTime;

class Logger
{
    public static function save(string $eventType, int $id, string $message)
    {
        $result = LogTable::add([
            "EVENT_TYPE" => $eventType,
            "EVENT_ID" => $id,
            "MESSAGE" => $message,
        ]);
        if ($result->isSuccess()) {
            return $result->getId();
        }
        return null;
    }
}
