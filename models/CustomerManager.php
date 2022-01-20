<?php


namespace app\models;

use yii\db\ActiveRecord;


/**
 * Класс, который хранит информацию о том, к какому клиенту (организации) какой привязан менеджер по клиентам от платформы spaccel.ru
 *
 * Class CustomerManager
 * @package app\models
 *
 * @property int $id                        идентификатор записи
 * @property int $user_id                   идентификатор менеджера из таблицы User
 * @property int $client_id                 идентификатор клиента (организации)
 * @property int $status                    статус менеджера по данному клиенту
 * @property int $created_at                дата привязки менеджера по клиентам к организации
 * @property int $updated_at                дата изменения статуса менеджера по клиентам к организации
 */
class CustomerManager  extends ActiveRecord
{

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'customer_manager';
    }


    // ПРОДОЛЖИТЬ ЗДЕСЬ!!!
}