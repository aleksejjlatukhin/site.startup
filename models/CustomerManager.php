<?php


namespace app\models;

use app\modules\admin\models\ConversationManager;
use yii\db\ActiveQuery;
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
 * @property int $created_at                дата привязки менеджера по клиентам к организации
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
     * Создание новой записи при назначении менеджера клиенту
     *
     * @param int $clientId
     * @param int $userId
     * @return CustomerManager|null
     */
    public static function addManager($clientId, $userId)
    {
        $model = new self();
        $model->setClientId($clientId);
        $model->setUserId($userId);
        return $model->save() ? $model : null;
    }


    /**
     * Создать или передать существующую беседу
     * менеджера Spaccel с админом компании
     *
     * @return void
     */
    private function createConversationManagerWithAdminCompany()
    {
        $client = Client::findOne($this->getClientId());
        $adminCompany = User::findOne($client->findSettings()->getAdminId());
        $conversationManager = new ConversationManager();
        $conversationManager->createOrUpdateRecordWithAdminCompany($this->getUserId(), $adminCompany);
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'user_id'], 'required'],
            [['client_id', 'user_id', 'created_at'], 'integer'],
        ];
    }


    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [ActiveRecord::EVENT_BEFORE_INSERT => ['created_at']],
            ],
        ];
    }


    public function init()
    {
        $this->on(self::EVENT_AFTER_INSERT, function (){
            $this->createConversationManagerWithAdminCompany();
        });

        parent::init();
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

}