<?php

$fields = ['BOT_TOKEN', 'CHAT_ID', 'IBLOCK_ID'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_bitrix_sessid()) {

    $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
    $postData = $request->getPostList()->toArray();
    switch ($postData['action']) {
        case "save":
            foreach ($fields as $field) {
                Bitrix\Main\Config\Option::Set($mid, $field, $postData[$field] ?? "");
            }
            CAdminMessage::ShowMessage([
                "MESSAGE" => Loc::getMessage("CHESTNOV_NOTIFICATIONSMAX_SAVED"),
                "TYPE" => "OK",
            ]);
            break;
        case "send":
            // Тут будит отправка сообщеия
            break;
        default:
            break;
    }
}
// Читаем сохранённое значение для подстановки в форму
foreach ($fields as $field) {
    $arrValue[$field] = Bitrix\Main\Config\Option::get($mid, $field, "");
}