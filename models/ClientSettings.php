<?php


namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;


/**
 * Класс, который хранит информацию о настройках клиентов (организациях)
 *
 * Class ClientSettings
 * @package app\models
 *
 * @property int $id                        идентификатор записи
 * @property int $client_id                 идентификатор клиента
 * @property int $admin_id                  идентификатор гл.админа организации (клиента)
 * @property string $avatar_max_image       оригинальное загруженное фото аватара клиента
 * @property string $avatar_image           урезанное (которое все видят) фото аватара клиента
 */
class ClientSettings extends ActiveRecord
{

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'client_settings';
    }


    /**
     * Получить объект клиента
     *
     * @return ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::class, ['id' => 'client_id']);
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return int
     */
    public function getClientId()
    {
        return $this->client_id;
    }


    /**
     * @param int $client_id
     */
    public function setClientId($client_id)
    {
        $this->client_id = $client_id;
    }


    /**
     * @return int
     */
    public function getAdminId()
    {
        return $this->admin_id;
    }


    /**
     * @param int $admin_id
     */
    public function setAdminId($admin_id)
    {
        $this->admin_id = $admin_id;
    }


    /**
     * @return string
     */
    public function getAvatarMaxImage()
    {
        return $this->avatar_max_image;
    }


    /**
     * @param string $avatar_max_image
     */
    public function setAvatarMaxImage($avatar_max_image)
    {
        $this->avatar_max_image = $avatar_max_image;
    }


    /**
     * @return string
     */
    public function getAvatarImage()
    {
        return $this->avatar_image;
    }


    /**
     * @param string $avatar_image
     */
    public function setAvatarImage($avatar_image)
    {
        $this->avatar_image = $avatar_image;
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'admin_id'], 'required'],
            [['client_id', 'admin_id'], 'integer'],
            [['client_id', 'admin_id'], 'unique'],
            [['avatar_max_image', 'avatar_image'], 'string', 'max' => 255]
        ];
    }

}