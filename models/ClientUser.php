<?php


namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;


/**
 * Класс, который хранит информацию о том, в какой организации зарегистрирован пользователи
 * или какие пользователи зарегистрированы в тех или иных организациях (клиентах)
 *
 * Class ClientUser
 * @package app\models
 *
 * @property int $id                        идентификатор записи
 * @property int $user_id                   идентификатор пользователя из таблицы User
 * @property int $client_id                 идентификатор клиента (организации)
 */
class ClientUser extends ActiveRecord
{

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'client_user';
    }


    /**
     * @return ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::class, ['id' => 'client_id']);
    }


    /**
     * @return Client|null
     */
    public function findClient()
    {
        return Client::findOne($this->client_id);
    }


    /**
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }


    /**
     * @return User|null
     */
    public function findUser()
    {
        return User::findOne($this->user_id);
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
    public function getUserId()
    {
        return $this->user_id;
    }


    /**
     * @param int $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
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
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'user_id'], 'required'],
            [['client_id', 'user_id'], 'integer'],
            [['user_id'], 'unique'],
        ];
    }


    /**
     * Создание новой записи
     *
     * @param $client_id
     * @param $user_id
     * @return bool
     */
    public static function createRecord($client_id, $user_id)
    {
        $model = new self();
        $model->client_id = $client_id;
        $model->user_id = $user_id;
        return $model->save();
    }
}