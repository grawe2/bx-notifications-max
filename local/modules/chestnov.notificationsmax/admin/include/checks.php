<?php

if (!$USER->IsAdmin()) {
    $APPLICATION->AuthForm('ACCESS_DENIED');
}

if (!Bitrix\Main\Loader::includeModule('chestnov.notificationsmax')) {
    die('Module not installed');
}

if (!Bitrix\Main\Loader::includeModule('iblock')) {
    die('Iblock module not installed');
}
