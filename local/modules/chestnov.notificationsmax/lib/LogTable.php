<?php


namespace Chestnov\Notificationsmax;

use Bitrix\Main\Entity;
use Bitrix\Main\Type\DateTime;


class LogTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'b_chestnov_notificationsmax_log';
    }

    public static function getMap()
    {
        return [
            // Поле ID: целочисленное, первичный ключ, автоинкремент
            new Entity\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
            ]),

            // Поле Дата/Время события
            new Entity\DatetimeField('DATE_CREATE', [
                'required' => true,
                'default_value' => function () {
                    return new DateTime();
                },
            ]),

            // Название сабытия
            new Entity\StringField('EVENT_TYPE', [
                'required' => true,
                'validation' => function () {
                    return [new Entity\Validator\Length(null, 50)];
                },
            ]),

            // ID - события
            new Entity\IntegerField('EVENT_ID'),


            // Поле Сообщение
            new Entity\TextField('MESSAGE', [
                'required' => true,
            ]),
        ];
    }
}
