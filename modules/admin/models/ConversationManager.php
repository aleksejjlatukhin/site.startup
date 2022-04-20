<?php


namespace app\modules\admin\models;

use app\models\User;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

/**
 * Класс, который хранит беседы менеджеров Spaccel по клиентам (организациям)
 * с другими пользователями, кроме админа Spaccel
 *
 * Class ConversationManager
 * @package app\modules\admin\models
 *
 * @property int $id                    идентификатор беседы
 * @property int $manager_id            идентификатор менеджера
 * @property int $user_id               идентификатор пользователя
 * @property int $role                  идентификатор пользователя
 * @property int $updated_at            дата обновления
 */
class ConversationManager extends ActiveRecord
{

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'conversation_manager';
    }


    /**
     * @return array
     */
    public function rules()
    {
        return [

            [['id', 'manager_id', 'user_id', 'role', 'updated_at'], 'integer'],
        ];
    }


    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            //Использование поведения TimestampBehavior ActiveRecord
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['updated_at'],
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],

                ],
            ],
        ];
    }


    /**
     * Создать или передать существующую беседу
     * менеджера Spaccel с админом компании
     *
     * @param int $managerId
     * @param User $user
     *
     * @return ConversationManager|null
     */
    public function createOrUpdateRecordWithAdminCompany($managerId, $user)
    {
        $record = self::findOne(['user_id' => $user->getId(), 'role' => $user->getRole()]);

        if ($record) {
            $record->setManagerId($managerId);
            return $record->save() ? $record : null;
        } else {
            $this->manager_id = $managerId;
            $this->user_id = $user->getId();
            $this->role = $user->getRole();
            return $this->save() ? $this : null;
        }
    }


    /**
     * Создать беседу менеджера Spaccel с гл.админом Spaccel
     *
     * @param int $managerId
     * @param User $user
     *
     * @return ConversationManager|null
     */
    public static function createRecordWithMainAdmin($managerId, $user)
    {
        $record = self::findOne(['manager_id' => $managerId, 'role' => User::ROLE_MAIN_ADMIN]);

        if (!$record) {
            $record = new self();
            $record->manager_id = $managerId;
            $record->user_id = $user->getId();
            $record->role = $user->getRole();
            return $record->save() ? $record : null;
        }
        return $record;
    }


    /**
     * Получить объект менеджера
     * @return ActiveQuery
     */
    public function getManager ()
    {
        return $this->hasOne(User::class, ['id' => 'manager_id']);
    }


    /**
     * Получить объект пользователя
     * @return ActiveQuery
     */
    public function getUser ()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }


    /**
     * Получить все сообщения беседы
     * @return ActiveQuery
     */
    public function getMessages ()
    {
        return $this->hasMany(MessageManager::class, ['conversation_id' => 'id']);
    }


    /**
     * Получить последнее сообщение беседы
     * @return ActiveQuery
     */
    public function getLastMessage ()
    {
        return $this->hasOne(MessageManager::class, ['conversation_id' => 'id'])->orderBy('created_at DESC');
    }


    /**
     * Получить кол-во непрочитанных сообщений беседы
     * @return int|string
     */
    public function getCountNewMessages ()
    {
        $count_new_messages = MessageManager::find()
            ->where(['conversation_id' => $this->id, 'status' => MessageManager::NO_READ_MESSAGE])->count();

        return $count_new_messages;
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
    public function getManagerId()
    {
        return $this->manager_id;
    }

    /**
     * @param int $manager_id
     */
    public function setManagerId($manager_id)
    {
        $this->manager_id = $manager_id;
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
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param int $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return int
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
}