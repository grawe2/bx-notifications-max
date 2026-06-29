<?php

$fields = ['BOT_TOKEN', 'CHAT_ID', 'IBLOCK_ID'];
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest()
if ($request->isPost() === 'POST' && check_bitrix_sessid()) {

   ;
    $postData = $request->getPostList()->toArray();
    switch ($postData['action']) {
        case "save":
            // 💾 SAVE SETTINGS
            foreach ($fields as $field) {
                Bitrix\Main\Config\Option::Set($mid, $field, $postData[$field] ?? "");
            }
            CAdminMessage::ShowMessage([
                "MESSAGE" => Loc::getMessage("CHESTNOV_NOTIFICATIONSMAX_SAVED"),
                "TYPE" => "OK",
            ]);
            break;
        case "send":
            // 📩 SEND TEST MESSAGE
            // пока заглушка
            break;
        default:
            break;
    }
}


// Читаем сохранённое значение для подстановки в форму
foreach ($fields as $field) {
    $arrValue[$field] = Bitrix\Main\Config\Option::get($mid, $field, "");
}