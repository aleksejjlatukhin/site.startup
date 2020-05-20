<?php

namespace app\modules\admin\models;

use app\models\User;
use yii\db\ActiveRecord;
use Yii;


class ConversationMainAdmin extends ActiveRecord
{

    public static function tableName()
    {
        return 'conversation_main_admin';
    }

    public function rules()
    {
        return [

            [['id', 'main_admin_id', 'admin_id'], 'integer'],
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
            'main_admin_id' => 'Главный Администратор',
            'admin_id' => 'Администратор',
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



    public function getMainAdmin ()
    {
        return $this->hasOne(User::class, ['id' => 'main_admin_id']);
        /*$main_admin = User::findOne([
            'role' => User::ROLE_MAIN_ADMIN,
            'id' => $this->main_admin_id,
        ]);

        return $main_admin;*/
    }


    public function getMessages ()
    {
        return $this->hasMany(MessageMainAdmin::class, ['conversation_id' => 'id']);
    }


    public function getLastMessage ()
    {

        $last_message = MessageMainAdmin::find()->where(['conversation_id' => $this->id])->orderBy('updated_at DESC')->one();

        return $last_message;
    }


    public function getCountNewMessages ()
    {
        $count_new_messages = MessageMainAdmin::find()
            ->where(['conversation_id' => $this->id, 'status' => MessageMainAdmin::NO_READ_MESSAGE])->count();

        return $count_new_messages;
    }
 }