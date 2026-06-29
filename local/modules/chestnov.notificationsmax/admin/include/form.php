
<?php

$aTabs = [
        [
                'DIV' => 'edit1',
                'TAB' => Bitrix\Main\Localization\Loc::getMessage("CHESTNOV_NOTIFICATIONSMAX_TAB_SETTINGS"),
                'TITLE' => Bitrix\Main\Localization\Loc::getMessage("CHESTNOV_NOTIFICATIONSMAX_TAB_SETTINGS_TITLE"),
        ]
];
$tabControl = new CAdminTabControl("tabControl", $aTabs);
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
            <?= Bitrix\Main\Localization\Loc::getMessage("CHESTNOV_NOTIFICATIONSMAX_HEADING_MAIN") ?>
        </td>
    </tr>
    <tr>
        <td><?= Bitrix\Main\Localization\Loc::getMessage("CHESTNOV_NOTIFICATIONSMAX_FIELD_BOT_TOKEN") ?>:</td>
        <td><input type="text" name="BOT_TOKEN" value="<?= htmlspecialchars($arrValue['BOT_TOKEN']) ?>"></td>
    </tr>

    <tr>
        <td><?= Bitrix\Main\Localization\Loc::getMessage("CHESTNOV_NOTIFICATIONSMAX_FIELD_CHAT_ID") ?>:</td>

        <td><input type="text" name="CHAT_ID" value="<?= htmlspecialchars($arrValue['CHAT_ID']) ?>"></td>
    </tr>
    <tr>
        <td><?= Bitrix\Main\Localization\Loc::getMessage("CHESTNOV_NOTIFICATIONSMAX_FIELD_IBLOCK_ID") ?>:</td>

        <td>
            <?php
            $iblocks = Bitrix\Iblock\IblockTable::getList(['select' => ['ID', 'NAME'], 'order' => ['SORT' => 'ASC'],]);
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
            <?= Bitrix\Main\Localization\Loc::getMessage("CHESTNOV_NOTIFICATIONSMAX_HEADING_TEST") ?>
        </td>
    </tr>
    <tr>
        <td>
            <?= Bitrix\Main\Localization\Loc::getMessage("CHESTNOV_NOTIFICATIONSMAX_FIELD_MESSAGE_TEXT") ?>
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