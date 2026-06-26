<?php
// 1. Подключаем служебную часть пролога (ядро, сессия, авторизация)
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Config\Option;
Loc::loadMessages(__FILE__);

$mid = "chestnov.notificationsmax";
//Проверка модуля и админ.
if (!Loader::includeModule('chestnov.notificationsmax') || !$USER->IsAdmin()) {
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}

$APPLICATION->SetTitle(Loc::getMessage("CHESTNOV_NOTIFICATIONSMAX_PAGE_TITLE"));

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

$aTabs = [
        [
                'DIV' => 'edit1',
                'TAB' => Loc::getMessage("CHESTNOV_NOTIFICATIONSMAX_TAB_SETTINGS"),
                'TITLE' => Loc::getMessage("CHESTNOV_NOTIFICATIONSMAX_TAB_SETTINGS_TITLE"),
        ]
];

$tabControl = new CAdminTabControl("tabControl", $aTabs);

// ИСПРАВЛЕНО: обработка POST до вывода формы
// Без этого блока данные при сабмите просто терялись
if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_bitrix_sessid()) {
    $exampleValue = trim(htmlspecialchars($_POST['EXAMPLE'] ?? ''));

    // Сохраняем значение через COption — стандартный способ хранения настроек модуля
    Option::Set($mid, "EXAMPLE", $exampleValue);

    // Показываем сообщение об успехе через стандартный битриксовый механизм
    CAdminMessage::ShowMessage([
            "MESSAGE" => Loc::getMessage("CHESTNOV_NOTIFICATIONSMAX_SAVED"),
            "TYPE" => "OK",
    ]);
}

// Читаем сохранённое значение для подстановки в форму
$exampleValue = Option::get($mid, "EXAMPLE", "");
?>
<?php

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$postData = $request->getPostList()->toArray();
?>

<pre>
    <?php
        print_r($postData);
    ?>
</pre>
    <form
            method="post"
            action="<?= $APPLICATION->GetCurPage() ?>?mid=<?= urlencode($mid) ?>&amp;lang=<?= LANGUAGE_ID ?>"
            id="dataload"
            enctype="multipart/form-data"
    >
        <?= bitrix_sessid_post() ?>

        <?php $tabControl->Begin(); ?>

        <?php $tabControl->BeginNextTab(); ?>
        <tr>
            <td><?= Loc::getMessage("NOTIFICATIONSMAX_FIELD_EXAMPLE") ?>:</td>
            <!-- ИСПРАВЛЕНО: value теперь подставляет сохранённое значение, а не пустую строку -->
            <td><input type="text" name="EXAMPLE" value="<?= htmlspecialchars($exampleValue) ?>"></td>
        </tr>

        <?php $tabControl->Buttons(); ?>
        <input type="submit" name="Update" value="<?= GetMessage("MAIN_SAVE") ?>"
               title="<?= GetMessage("MAIN_OPT_SAVE_TITLE") ?>" class="adm-btn-save">
        <?php $tabControl->End(); ?>
    </form>

<?php
// 3. Подключаем эпилог (подвал админки, панель отладки)
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");