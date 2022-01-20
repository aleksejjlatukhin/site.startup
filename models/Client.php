<?php


namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;


/**
 * Класс, который хранит информацию о клиентах (организациях)
 *
 * Class Client
 * @package app\models
 *
 * @property int $id                    идентификатор клиента
 * @property string $name               наименование клиента
 * @property string $fullname           полное наименование клиента
 * @property string $city               город клиента
 * @property string $description        описание клиента
 * @property int $created_at            дата регистраниции клиента
 * @property int $updated_at            дата редактирования клиента
 */
class Client extends ActiveRecord
{

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'client';
    }


    /**
     * Получить все записи по клиенту
     * в таблице client_activation
     *
     * @return ActiveQuery
     */
    public function getClientActivationRecords()
    {
        return $this->hasMany(ClientActivation::class, ['client_id' => 'id']);
    }


    /**
     * Получить настройки клиента
     *
     * @return ActiveQuery
     */
    public function getSettings()
    {
        return $this->hasMany(ClientSettings::class, ['client_id' => 'id']);
    }


    /**
     * Поиск записей в таблице client_user
     * по данному клиенту
     *
     * @return ActiveQuery
     */
    public function getClientUsers()
    {
        return $this->hasMany(ClientUser::class, ['client_id' => 'id']);
    }


    /**
     * Найти пользователей зарегистрированных
     * на платформе в организации клиента
     *
     * @return array|ActiveRecord[]
     */
    public function findUsers()
    {
        return User::find()->with('clientUsers')
            ->leftJoin('client_user', '`client_user`.`user_id` = `user`.`id`')
            ->where(['client_user.client_id' => $this->id])->all();
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


    /**
     * @return string
     */
    public function getFullname()
    {
        return $this->fullname;
    }


    /**
     * @param string $fullname
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;
    }


    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }


    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }


    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }


    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'fullname', 'city', 'description'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            ['name', 'string', 'min' => 3, 'max' => 32],
            [['fullname', 'city'], 'string', 'max' => 255],
            ['description', 'string', 'max' => 2000],
        ];
    }


    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Наименование организации',
            'fullname' => 'Полное наименование организации',
            'city' => 'Город, в котором находится организация',
            'description' => 'Описание организации',
            'created_at' => 'Дата регистрации',
            'updated_at' => 'Дата обновления'
        ];
    }

}