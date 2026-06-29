<?php
use Bitrix\Main\Loader;

if (!$USER->IsAdmin()) {
    $APPLICATION->AuthForm('ACCESS_DENIED');
}

if (!Loader::includeModule('chestnov.notificationsmax')) {
    CAdminMessage::ShowMessage('Модуль chestnov.notificationsmax не установлен');
    return;
}

if (!Loader::includeModule('iblock')) {
    CAdminMessage::ShowMessage('Модуль iblock не установлен');
    return;
}
