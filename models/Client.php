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
     * Найти последнюю (актуальную) запись по клиенту
     * в таблице client_activation
     *
     * @return array|ActiveRecord|null
     */
    public function findClientActivation()
    {
        return ClientActivation::find()
            ->where(['client_id' => $this->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->one();
    }


    /**
     * Проверить активна ли в данный момент организация
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->findClientActivation()->getStatus() == ClientActivation::ACTIVE;
    }


    /**
     * Найти всех активированных клиентов
     *
     * @return array
     */
    public static function findAllActiveClients()
    {
        $clients = self::find()->all();
        $result = array();

        foreach ($clients as $client) {
            if ($client->findClientActivation()->status == ClientActivation::ACTIVE) {
                $result[] = $client;
            }
        }
        return $result;
    }


    /**
     * Получить настройки клиента
     *
     * @return ActiveQuery
     */
    public function getSettings()
    {
        return $this->hasOne(ClientSettings::class, ['client_id' => 'id']);
    }


    /**
     * @return ClientSettings|null
     */
    public function findSettings()
    {
        return ClientSettings::findOne(['client_id' => $this->id]);
    }


    /**
     * Получить все тарифы на которые
     * когда либо была подписана организация (клиент)
     *
     * @return ActiveQuery
     */
    public function getClientRatesPlans()
    {
        return $this->hasMany(ClientRatesPlan::class, ['client_id' => 'id']);
    }


    /**
     * @return ClientRatesPlan[]
     */
    public function findClientRatesPlans()
    {
        return ClientRatesPlan::findAll(['client_id' => $this->id]);
    }


    /**
     * Получить последний установленный тариф для организации(клиента)
     *
     * @return array|ActiveRecord|null
     */
    public function findLastClientRatesPlan()
    {
        return ClientRatesPlan::find()
            ->where(['client_id' => $this->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->one();
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
     * @return ActiveQuery
     */
    public function getCustomerManagers()
    {
        return $this->hasMany(CustomerManager::class, ['client_id' => 'id']);
    }


    /**
     * @return ActiveRecord|array|null
     */
    public function findCustomerManager()
    {
        return CustomerManager::find()->where(['client_id' => $this->id])->orderBy(['created_at' => SORT_DESC])->one();
    }


    /**
     * @return ActiveQuery
     */
    public function getCustomerTrackers()
    {
        return $this->hasMany(CustomerTracker::class, ['client_id' => 'id']);
    }


    /**
     * @return CustomerTracker|null
     */
    public function findCustomerTrackers()
    {
        return CustomerTracker::findOne(['client_id' => $this->id, 'status' => CustomerTracker::ACTIVE]);
    }


    /**
     * Получить количество трекеров,
     * зарегистрированных в данной организации
     *
     * @return int|string
     */
    public function getCountTrackers()
    {
        return User::find()->with('clientUser')
            ->leftJoin('client_user', '`client_user`.`user_id` = `user`.`id`')
            ->where(['client_user.client_id' => $this->id, 'role' => User::ROLE_ADMIN])->count();
    }


    /**
     * Получить количество экспертов,
     * зарегистрированных в данной организации
     *
     * @return int|string
     */
    public function getCountExperts()
    {
        return User::find()->with('clientUser')
            ->leftJoin('client_user', '`client_user`.`user_id` = `user`.`id`')
            ->where(['client_user.client_id' => $this->id, 'role' => User::ROLE_EXPERT])->count();
    }


    /**
     * Получить количество проектантов,
     * зарегистрированных в данной организации
     *
     * @return int|string
     */
    public function getCountUsers()
    {
        return User::find()->with('clientUser')
            ->leftJoin('client_user', '`client_user`.`user_id` = `user`.`id`')
            ->where(['client_user.client_id' => $this->id, 'role' => User::ROLE_USER])->count();
    }


    /**
     * Получить количество проектов,
     * зарегистрированных в данной организации
     *
     * @return int
     */
    public function getCountProjects()
    {
        $users = User::find()->with('clientUser')
            ->leftJoin('client_user', '`client_user`.`user_id` = `user`.`id`')
            ->where(['client_user.client_id' => $this->id, 'role' => User::ROLE_USER])->all();

        $arrayCountProjects = array();
        foreach ($users as $user) {
            $arrayCountProjects[] = Projects::find()->where(['user_id' => $user->id])->count();
        }
        return array_sum($arrayCountProjects);
    }


    /**
     * @return ActiveQuery
     */
    public function getCustomerExperts()
    {
        return $this->hasMany(CustomerExpert::class, ['client_id' => 'id']);
    }


    /**
     * @return CustomerExpert|null
     */
    public function findCustomerExperts()
    {
        return CustomerExpert::findOne(['client_id' => $this->id, 'status' => CustomerExpert::ACTIVE]);
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
     * Проверка готовности организации к активации
     *
     * @return bool
     */
    public function checkingReadinessActivation()
    {
        if ($this->findCustomerManager() && $this->findLastClientRatesPlan()) {
            return true;
        }
        return false;
    }


    /**
     * Поиск организации по id
     *
     * @param int $id
     * @return Client|null
     */
    public static function findById($id)
    {
        return self::findOne($id);
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


    public function init()
    {
        $this->on(self::EVENT_AFTER_INSERT, function (){
            $this->createClientActivationDefault();
        });

        parent::init();
    }


    /**
     * @return bool
     */
    private function createClientActivationDefault()
    {
        $clientActivation = new ClientActivation();
        $clientActivation->setClientId($this->id);
        return $clientActivation->save();
    }

}