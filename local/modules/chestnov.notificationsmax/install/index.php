<?php

//подключаем основные классы для работы с модулем
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

/*
 use Bitrix\Main\Entity\Base;
use Bitrix\Main\Loader;
 */

Loc::loadMessages(__FILE__);

class chestnov_notificationsmax extends CModule
{
    private const OPTIONS = [
        'BOT_TOKEN',
        'CHAT_ID',
        'IBLOCK_ID'
    ];

    public function __construct()
    {
        $arModuleVersion = [];
        //подключаем версию модуля (файл будет следующим в списке)
        include __DIR__ . '/version.php';


        // Модуль ID совпадает с именем папки
        $this->MODULE_ID = 'chestnov.notificationsmax';

        //  Версия модуля и дата последний версии
        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        // Название модуля
        $this->MODULE_NAME = Loc::getMessage('CHESTNOV_NOTIFICATIONSMAX_MODULE_NAME');
        //описание модуля
        $this->MODULE_DESCRIPTION = Loc::getMessage('CHESTNOV_NOTIFICATIONSMAX_MODULE_DESCRIPTION');
        //используем ли индивидуальную схему распределения прав доступа, мы ставим N, так как не используем ее
        $this->MODULE_GROUP_RIGHTS = 'N';
        //название компании партнера предоставляющей модуль
        $this->PARTNER_NAME = Loc::getMessage('CHESTNOV_NOTIFICATIONSMAX_MODULE_PARTNER_NAME');
        //адрес вашего сайта
        $this->PARTNER_URI = Loc::getMessage('CHESTNOV_NOTIFICATIONSMAX_MODULE_PARTNER_URL');
    }

    // Запускается при нажатии кнопки Установить на странице Модули административного раздела, осуществляет инсталляцию модуля.
    public function DoInstall(): void
    {
        ModuleManager::registerModule($this->MODULE_ID);

        $this->InstallVariables();
        $this->InstallFiles();

    }

    // Запускается при нажатии кнопки Удалить на странице Модули административного раздела, осуществляет деинсталляцию модуля.
    public function DoUninstall(): void
    {
        $this->UnInstallVariables();
        $this->UnInstallFiles();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }


    //  Создание дефолтных настроек модуля
    public function InstallVariables(): void
    {
        foreach (self::OPTIONS as $field) {
            Option::set($this->MODULE_ID, $field, "");
        }
    }

    // Удаление настроек модуля
    public function UnInstallVariables(): void
    {
        foreach (self::OPTIONS as $field) {
            Option::delete($this->MODULE_ID, ['name' => $field]);
        }
    }

    // Устнаовка файлов
    public function InstallFiles(): void
    {
        // Копируем файлы административного интерфейса
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . "/local/modules/chestnov.notificationsmax/install/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin", true, true);

    }

    // Удаление файлов
    public function UnInstallFiles(): void
    {
        // Удаляем файлы административного интерфейса
        DeleteDirFiles($_SERVER["DOCUMENT_ROOT"] . "/local/modules/chestnov.notificationsmax/install/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin");

    }

}