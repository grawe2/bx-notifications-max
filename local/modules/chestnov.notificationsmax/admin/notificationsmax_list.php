<?php
// 1. Подключаем служебную часть пролога (ядро, сессия, авторизация)
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

use Bitrix\Iblock\IblockTable;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;


Loc::loadMessages(__FILE__);

$mid = "chestnov.notificationsmax";


// Проверка подключение модулей и пользователя

$APPLICATION->SetTitle(Loc::getMessage("CHESTNOV_NOTIFICATIONSMAX_PAGE_TITLE"));

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");


$aTabs = [
        [
                'DIV' => 'edit1',
                'TAB' => Loc::getMessage("CHESTNOV_NOTIFICATIONSMAX_TAB_SETTINGS"),
                'TITLE' => Loc::getMessage("CHESTNOV_NOTIFICATIONSMAX_TAB_SETTINGS_TITLE"),
        ]
];




require_once(__DIR__ . "/include/checks.php");
$tabControl = new CAdminTabControl("tabControl", $aTabs);


$fields = ['BOT_TOKEN', 'CHAT_ID', 'IBLOCK_ID'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_bitrix_sessid()) {

    $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
    $postData = $request->getPostList()->toArray();
    switch ($postData['action']) {
        case "save":
            foreach ($fields as $field) {
                Option::Set($mid, $field, $postData[$field] ?? "");
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
    $arrValue[$field] = Option::get($mid, $field, "");
}

?>

    <form
            method="post"
            action="<?= $APPLICATION->GetCurPage() ?>?mid=<?= urlencode($mid) ?>&amp;lang=<?= LANGUAGE_ID ?>"
            id="dataload"
    >
        <?= bitrix_sessid_post() ?>

        <?php $tabControl->Begin(); ?>
        <?php $tabControl->BeginNextTab(); ?>

        <tr class="heading">
            <td colspan="2">
                <?= Loc::getMessage("CHESTNOV_NOTIFICATIONSMAX_HEADING_MAIN") ?>
            </td>
        </tr>
        <tr>
            <td><?= Loc::getMessage("CHESTNOV_NOTIFICATIONSMAX_FIELD_BOT_TOKEN") ?>:</td>
            <td><input type="text" name="BOT_TOKEN" value="<?= htmlspecialchars($arrValue['BOT_TOKEN']) ?>"></td>
        </tr>

        <tr>
            <td><?= Loc::getMessage("CHESTNOV_NOTIFICATIONSMAX_FIELD_CHAT_ID") ?>:</td>

            <td><input type="text" name="CHAT_ID" value="<?= htmlspecialchars($arrValue['CHAT_ID']) ?>"></td>
        </tr>
        <tr>
            <td><?= Loc::getMessage("CHESTNOV_NOTIFICATIONSMAX_FIELD_IBLOCK_ID") ?>:</td>

            <td>
                <?php
                $iblocks = IblockTable::getList(['select' => ['ID', 'NAME'], 'order' => ['SORT' => 'ASC'],]);
                ?>
                <select name="IBLOCK_ID">
                    <?php while ($iblock = $iblocks->fetch()): ?>
                        <option value="<?= $iblock['ID'] ?>"
                                <?php if ($iblock['ID'] == $arrValue['IBLOCK_ID']): ?>selected<?php endif; ?>>
                            <?= $iblock['NAME'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </td>
        </tr>
        <tr class="heading">
            <td colspan="2">
                <?= Loc::getMessage("CHESTNOV_NOTIFICATIONSMAX_HEADING_TEST") ?>
            </td>
        </tr>
        <tr>
            <td>
                <?= Loc::getMessage("CHESTNOV_NOTIFICATIONSMAX_FIELD_MESSAGE_TEXT") ?>
            </td>
            <td>
                <div>
                    <textarea name="MESSAGE_TEXT"></textarea>
                </div>
                <div>
                    <button type="submit" name="action" value="send">
                        <?= GetMessage("CHESTNOV_NOTIFICATIONSMAX_BTN_SEND_TEXT") ?>
                    </button>
                </div>
            </td>
        </tr>
        <?php $tabControl->Buttons(); ?>
        <button class="adm-btn-save " type="submit" name="action" value="save">
            <?= GetMessage("MAIN_SAVE") ?>
        </button>

        <?php $tabControl->End(); ?>
    </form>

<?php
// 3. Подключаем эпилог (подвал админки, панель отладки)
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");