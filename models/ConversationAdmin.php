<?php


namespace app\models;

use yii\db\ActiveRecord;

class ConversationAdmin extends ActiveRecord
{

    public static function tableName()
    {
        return 'conversation_admin';
    }

    public function rules()
    {
        return [
            [['id', 'admin_id', 'user_id'], 'integer'],
            ['updated_at', 'default', 'value' => function () {
                return $this->getLastMessage()->created_at;
            }],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'admin_id' => 'Администратор',
            'user_id' => 'Проектант',
            'updated_at' => 'Статус прочтения',
        ];
    }


    public function getAdmin ()
    {
        return $this->hasOne(User::class, ['id' => 'admin_id']);
        /*$admin = User::findOne([
            'role' => User::ROLE_ADMIN,
            'id' => $this->admin_id,
        ]);

        return $admin;*/
    }


    public function getUser ()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
        /*$user = User::findOne([
            'role' => User::ROLE_USER,
            'id' => $this->user_id,
        ]);

        return $user;*/
    }


    public function getMessages ()
    {
        return $this->hasMany(MessageAdmin::class, ['conversation_id' => 'id']);
    }


    public function getLastMessage ()
    {

        $last_message = MessageAdmin::find()->where(['conversation_id' => $this->id])->orderBy('updated_at DESC')->one();

        return $last_message;
    }


    public function getCountNewMessages ()
    {
        $count_new_messages = MessageAdmin::find()
            ->where(['conversation_id' => $this->id, 'status' => MessageAdmin::NO_READ_MESSAGE])->count();

        return $count_new_messages;
    }
}