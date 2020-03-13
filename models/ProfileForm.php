<?php


namespace app\models;

use yii\base\Model;
use Yii;

class ProfileForm extends Model
{
    public $second_name;
    public $first_name;
    public $middle_name;
    public $telephone;
    public $username;
    public $email;


    public function rules()
    {
        return [
            [['second_name', 'first_name', 'middle_name', 'username'], 'required'],
            [['second_name', 'first_name', 'middle_name', 'username', 'email', 'telephone'], 'trim'],

            ['username', 'string', 'min' => 3, 'max' => 32],
            [['second_name', 'first_name', 'middle_name', 'telephone'], 'string', 'max' => 255],

            ['username', 'match', 'pattern' => '/[a-z]+/i', 'message' => '{attribute} должен содержать только латиницу!'],
            ['username', 'uniqUsername'],

            [['email'], 'email'],
            ['email', 'uniqEmail'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'second_name' => 'Фамилия',
            'first_name' => 'Имя',
            'middle_name' => 'Отчество',
            'telephone' => 'Телефон',
            'email' => 'Эл.почта',
            'username' => 'Логин',
        ];
    }


    /*Собственное правило для поля username*/
    /*Переводим все логины в нижний регистр и сравниваем их с тем, что в форме*/
    public function uniqUsername($attr)
    {
        $users = User::find()->all();

        foreach ($users as $user){

            if ($user->id !== \Yii::$app->user->id){

                if (mb_strtolower($this->username) === mb_strtolower($user->username)){
                    $this->addError($attr, 'Этот логин уже занят.');
                }
            }
        }
    }


    public function uniqEmail($attr)
    {
        $users = User::find()->all();

        foreach ($users as $user){

            if ($user->id !== \Yii::$app->user->id){

                if ($this->email === $user->email){
                    $this->addError($attr, 'Эта почта уже зарегистрирована.');
                }
            }
        }
    }


    public function update()
    {
        $user = User::find()->where(['id' => \Yii::$app->user->id])->one();

        $user->second_name = $this->second_name;
        $user->first_name = $this->first_name;
        $user->middle_name = $this->middle_name;
        $user->telephone = $this->telephone;
        $user->email = $this->email;

        $this->updateUserDir($user);

        return $user->save() ? $user : null;
    }


    private function updateUserDir($user)
    {
        if ($user->username != $this->username){

            $old_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/';

            $new_dir = UPLOAD . mb_convert_encoding(mb_strtolower($this['username'], "windows-1251"), "windows-1251")
                . '/';

            rename($old_dir, $new_dir);
        }

        return $user->username = $this->username;
    }
}


