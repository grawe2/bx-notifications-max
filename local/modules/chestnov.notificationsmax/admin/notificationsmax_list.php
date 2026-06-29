<?php
// 1. Подключаем служебную часть пролога (ядро, сессия, авторизация)
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");



Bitrix\Main\Localization\Loc::loadMessages(__FILE__);

$mid = "chestnov.notificationsmax";


// Проверка подключение модулей и пользователя

$APPLICATION->SetTitle(Bitrix\Main\Localization\Loc::getMessage("CHESTNOV_NOTIFICATIONSMAX_PAGE_TITLE"));

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

include(__DIR__ . "/include/checks.php");

include(__DIR__ . "/include/actions.php");

include(__DIR__ . "/include/form.php");

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");