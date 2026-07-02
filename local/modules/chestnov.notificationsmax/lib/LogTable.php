<?php

namespace Chestnov\Notificationsmax;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\Type\DateTime;

class LogTable extends DataManager
{
    public static function getTableName(): string
    {
        return 'b_chestnov_notificationsmax_log';
    }

    public static function getMap(): array
    {
        return [
            // Поле ID: целочисленное, первичный ключ, автоинкремент
            new IntegerField('ID', [
                'primary'      => true,
                'autocomplete' => true,
            ]),

            // Поле Дата/Время события
            new DatetimeField('DATE_CREATE', [
                'required'      => true,
                'default_value' => static function () {
                    return new DateTime();
                },
            ]),

            // Название события
            new StringField('EVENT_TYPE', [
                'required'   => true,
                'validation' => static function () {
                    return [new LengthValidator(null, 50)];
                },
            ]),

            // ID события
            new IntegerField('EVENT_ID'),

            // Поле Сообщение
            new TextField('MESSAGE', [
                'required' => true,
            ]),
        ];
    }
}