<?php


if (!$USER->IsAdmin()) {
    $APPLICATION->AuthForm('ACCESS_DENIED');
}

if (!Bitrix\Main\Loader::includeModule('chestnov.notificationsmax')) {
    CAdminMessage::ShowMessage('Модуль chestnov.notificationsmax не установлен');
    return;
}

if (!Bitrix\Main\Loader::includeModule('iblock')) {
    CAdminMessage::ShowMessage('Модуль iblock не установлен');
    return;
}
