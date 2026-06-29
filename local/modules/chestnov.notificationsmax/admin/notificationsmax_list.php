<?php
// 1. Подключаем служебную часть пролога (ядро, сессия, авторизация)
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

Bitrix\Main\Localization\Loc::loadMessages(__FILE__);

$mid = "chestnov.notificationsmax";

$APPLICATION->SetTitle(Bitrix\Main\Localization\Loc::getMessage("CHESTNOV_NOTIFICATIONSMAX_PAGE_TITLE"));

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

// Проверка прав пользователя и доступов к админке
include(__DIR__ . "/include/checks.php");

// Обработка POST действий: save / send test message
include(__DIR__ . "/include/actions.php");

// Отрисовка формы настроек модуля
include(__DIR__ . "/include/form.php");

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");