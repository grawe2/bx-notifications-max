<?php

$fields = ['BOT_TOKEN', 'CHAT_ID', 'IBLOCK_ID'];
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
if ($request->isPost() && check_bitrix_sessid()) {
    $postData = $request->getPostList()->toArray();

    switch ($postData['action']) {
        case "save":
            foreach ($fields as $field) {
                Bitrix\Main\Config\Option::set($mid, $field, $postData[$field] ?? "");
            }
            CAdminMessage::ShowMessage([
                "MESSAGE" => Bitrix\Main\Localization\Loc::getMessage("CHESTNOV_NOTIFICATIONSMAX_SAVED"),
                "TYPE" => "OK",
            ]);
            break;
        case "send":
            $token = Bitrix\Main\Config\Option::get($mid, 'BOT_TOKEN');
            $chatId = Bitrix\Main\Config\Option::get($mid, 'CHAT_ID');
            $maxSen = new Chestnov\Notificationsmax\MaxMessengerNewsSender(
                $token,
                $chatId
            );
            try {
                $result = $maxSen->send([
                    'TEXT' => $postData['MESSAGE_TEXT']
                ]);
            } catch (\Throwable $e) {
                CAdminMessage::ShowMessage([
                    'MESSAGE' => $e->getMessage(),
                    'TYPE' => 'ERROR',
                ]);
                return;
            }
            if ($result['HTTP_CODE'] >= 200 && $result['HTTP_CODE'] < 300){
                CAdminMessage::ShowMessage([
                    "MESSAGE" => "Сообщение отправлено",
                    "TYPE" => "OK",
                ]);
            }else{
                $response = json_decode($result['RESPONSE'], true);
                CAdminMessage::ShowMessage([
                    "MESSAGE" => "Сообщение не отправлено",
                    "TYPE" => "ERROR",
                    "DETAILS" =>
                        'HTTP_CODE: ' . $result['HTTP_CODE'] . '<br>' .
                        'ERROR_CODE: ' . ($response['code'] ?? '-') . '<br>' .
                        'MESSAGE: ' . ($response['message'] ?? '-')
                ]);
            }
            break;
        default:
            break;
    }
}


// Читаем сохранённое значение для подстановки в форму
foreach ($fields as $field) {
    $arrValue[$field] = Bitrix\Main\Config\Option::get($mid, $field);
}
