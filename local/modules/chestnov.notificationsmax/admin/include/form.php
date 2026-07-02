<?php

// При переходе по пагинации лога принудительно открываем вторую вкладку.
// CAdminTabControl определяет активную вкладку по параметру tabControl_active_tab,
// а ссылки пагинации его не сохраняют — поэтому форсируем его сами.
if (isset($_GET['nav-log'])) {
    $_REQUEST['tabControl_active_tab'] = 'edit2';
}

$aTabs = [
        [
                'DIV' => 'edit1',
                'TAB' => Bitrix\Main\Localization\Loc::getMessage("CHESTNOV_NOTIFICATIONSMAX_TAB_SETTINGS"),
                'TITLE' => Bitrix\Main\Localization\Loc::getMessage("CHESTNOV_NOTIFICATIONSMAX_TAB_SETTINGS_TITLE"),
        ],
        [
                "DIV" => "edit2",
                "TAB" => Bitrix\Main\Localization\Loc::getMessage("CHESTNOV_NOTIFICATIONSMAX_PAGE_LOG_TITLE"),
                "TITLE" => Bitrix\Main\Localization\Loc::getMessage("CHESTNOV_NOTIFICATIONSMAX_TAB_LOG_SETTINGS"),
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
        <td>
            Активировать модуль
        </td>
        <td>
            <input type="checkbox" <?=$arrValue['ACTIVE']?'checked':''?>   name="ACTIVE">
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
    <?php $tabControl->BeginNextTab(); ?>



    <?php
    // 1. НАВИГАЦИЯ: 10 записей на страницу, сама читает ?page=N из URL
    $nav = new \Bitrix\Main\UI\AdminPageNavigation('nav-log');
    $nav->setPageSize(10);
    $nav->initFromUri();

    // 2. ЗАПРОС: только нужные 10 записей + count_total считает ВСЕ строки
    $rsLog = \Chestnov\Notificationsmax\LogTable::getList([
            'select'      => ['ID', 'DATE_CREATE', 'EVENT_TYPE', 'EVENT_ID', 'MESSAGE'],
            'order'       => ['ID' => 'DESC'],   // свежие сверху
            'count_total' => true,                // ← важно: для подсчёта общего числа
            'offset'      => $nav->getOffset(),
            'limit'       => $nav->getLimit(),
    ]);

    // 3. Передаём навигации общее количество записей
    $nav->setRecordCount($rsLog->getCount());
    $rows = $rsLog->fetchAll();
    ?>


    <?php if (empty($rows)): ?>
        <tr>
            <td colspan="2" style="text-align:center; padding:30px; color:#888;">
                Лог пуст
            </td>
        </tr>
    <?php else: ?>

        <!-- Навигация СВЕРХУ -->
        <tr>
            <td colspan="2" style="text-align:right; padding:10px 0;">
                <?php $APPLICATION->IncludeComponent(
                        'bitrix:main.pagenavigation',
                        '',
                        ['NAV_OBJECT' => $nav]
                ); ?>
            </td>
        </tr>

        <!-- Заголовок раздела лога -->
        <tr class="heading">
            <td colspan="2"><?= GetMessage("CHESTNOV_NOTIFICATIONSMAX_HEADING_LOG") ?></td>
        </tr>

        <!-- Строки лога: 2 колонки под сетку CAdminTabControl -->
        <?php foreach ($rows as $row): ?>
            <tr>
                <td width="140" valign="top" style="padding:8px; white-space:nowrap; color:#777;">
                    <div>#<?= (int)$row['ID'] ?></div>
                    <div><?= htmlspecialchars((string)$row['DATE_CREATE']) ?></div>
                </td>
                <td valign="top" style="padding:8px;">
                    <div>
                        <strong><?= htmlspecialchars($row['EVENT_TYPE']) ?></strong>
                        <span style="color:#999;">
                            (<?= GetMessage("CHESTNOV_NOTIFICATIONSMAX_LOG_ELEMENT") ?>: <?= (int)$row['EVENT_ID'] ?>)
                        </span>
                    </div>
                    <div style="margin-top:4px; color:#444;">
                        <?= nl2br(htmlspecialchars($row['MESSAGE'])) ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>

        <!-- Навигация СНИЗУ -->
        <tr>
            <td colspan="2" style="text-align:right; padding:10px 0;">
                <?php $APPLICATION->IncludeComponent(
                        'bitrix:main.pagenavigation',
                        '',
                        ['NAV_OBJECT' => $nav]
                ); ?>
            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:left; padding:10px 0;">
                <button type="submit" name="action" value="clear">
                    <?= GetMessage("CHESTNOV_NOTIFICATIONSMAX_BTN_CLEAR_TEXT") ?>

                </button>
            </td>
        </tr>



    <?php endif; ?>



    <?php $tabControl->Buttons(); ?>
    <button class="adm-btn-save " type="submit" name="action" value="save">
        <?= GetMessage("MAIN_SAVE") ?>
    </button>




    <?php $tabControl->End(); ?>
</form>