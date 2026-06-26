<?

//подключаем основные классы для работы с модулем
// Локализация
use Bitrix\Main\Config\Option;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

class chestnov_notificationsmax extends CModule
{
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
        $this->PARTNER_URI = Loc::getMessage('CHESTNOV_NOTIFICATIONSMAX_MODULE_PARTNER_URL');;//адрес вашего сайта
    }

    // Запускается при нажатии кнопки Установить на странице Модули административного раздела, осуществляет инсталляцию модуля.
    public function DoInstall()
    {

        // Регистрация модуля
        ModuleManager::registerModule($this->MODULE_ID);
    }

    // Запускается при нажатии кнопки Удалить на странице Модули административного раздела, осуществляет деинсталляцию модуля.
    public function DoUninstall()
    {
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }



    // Устнаовка файлов
    public function InstallFile()
    {
        // Копируем файлы административного интерфейса
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . "/local/modules/chestnov.notificationsmax/install/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin", true, true);

    }
    // Удаление файлов
    public function DoInstallFile(){
        // Удаляем файлы административного интерфейса
        DeleteDirFiles($_SERVER["DOCUMENT_ROOT"] . "/local/modules/chestnov.notificationsmax/install/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin"); //

    }

}


?>
