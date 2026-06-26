<?php

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$aMenu = [
    [
        "parent_menu" => "global_menu_services", // Привязываем к глобальному разделу "Сервисы"
        "sort" => 500,
        "text" => Loc::getMessage('CHESTNOV_NOTIFICATIONSMAX_MENU_MAIN'),
        "title" => Loc::getMessage('CHESTNOV_NOTIFICATIONSMAX_MAIN_TITLE'),
        "url" => "notificationsmax_list.php?lang=" . LANGUAGE_ID,
        "icon" => "fileman_sticker_icon", // Стандартная иконка
        "page_icon" => "fileman_sticker_icon",
        "items_id" => "menu_chestnov_notificationsmax", // Уникальный ID ветки меню
        "items" => [] // Здесь могут быть вложенные пункты
    ]
];
return $aMenu;
