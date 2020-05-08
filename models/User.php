<?

namespace app\models;

use app\modules\admin\models\ConversationMainAdmin;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
use Yii;

class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_NOT_ACTIVE = 1;
    const STATUS_ACTIVE = 10;

    const ROLE_USER = 10;
    const ROLE_ADMIN = 20;
    const ROLE_MAIN_ADMIN = 30;
    const ROLE_DEV = 100;

    const CONFIRM = 20;
    const NOT_CONFIRM = 10;

    public $password;

    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['second_name', 'first_name', 'middle_name', 'telephone', 'username', 'email', 'password', 'avatar_image'], 'filter', 'filter' => 'trim'],
            [['second_name', 'first_name', 'middle_name', 'email', 'telephone', 'avatar_image'], 'string', 'max' => 255],
            [['second_name', 'first_name', 'middle_name', 'username', 'email', 'status', 'role', 'confirm'], 'required'],
            [['role', 'status', 'confirm', 'id_admin'], 'integer'],
            ['email', 'email'],
            ['username', 'match', 'pattern' => '/[a-z]+/i', 'message' => '{attribute} должен содержать только латиницу!'],
            ['username', 'string', 'min' => 3, 'max' => 32],
            ['password', 'string', 'min' => 6, 'max' => 32],
            ['password', 'required', 'on' => 'create'],
            ['username', 'unique', 'message' => 'Этот логин уже занят.'],
            ['email', 'unique', 'message' => 'Эта почта уже зарегистрирована.'],
            ['secret_key', 'unique'],
            ['avatar_image', 'default', 'value' => function () {
                return \Yii::getAlias('@web/images/avatar/default.jpg');
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
            'second_name' => 'Фамилия',
            'first_name' => 'Имя',
            'middle_name' => 'Отчество',
            'telephone' => 'Телефон',
            'username' => 'Логин',
            'email' => 'Эл.почта',
            'password' => 'Password',
            'status' => 'Статус',
            'role' => 'Проектная роль',
            'auth_key' => 'Auth Key',
            'created_at' => 'Дата регистрации',
            'updated_at' => 'Последнее изменение',
        ];
    }


    /* Связи */
    public function getProjects()
    {
        return $this->hasMany(Projects::class, ['user_id' => 'id']);
    }


    /* Поведения */
    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }


    /* Аутентификация пользователей */
    public static function findIdentity($id)
    {
        return static::findOne([
            'id' => $id,
            'confirm' => self::CONFIRM,
        ]);
    }


    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
//        return static::findOne(['access_token' => $token]);
    }

    /** Находит пользователя по имени и возвращает объект найденного пользователя.
     *  Вызываеться из модели LoginForm.
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /* Находит пользователя по емайл */
    public static function findByEmail($email)
    {
        return static::findOne([
            'email' => $email
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }


    /**
     * Сравнивает полученный пароль с паролем в поле password_hash, для текущего пользователя, в таблице user.
     * Вызываеться из модели LoginForm.
     */
    public function validatePassword($password)
    {
        //return $this->password === $password;
        return \Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Генерирует случайную строку из 32 шестнадцатеричных символов и присваивает (при записи) полученное значение полю auth_key
     * таблицы user для нового пользователя.
     * Вызываеться из модели RegForm.
     */
    public function generateAuthKey()
    {
        $this->auth_key = \Yii::$app->security->generateRandomString();
    }

    /**
     * Генерирует хеш из введенного пароля и присваивает (при записи) полученное значение полю password_hash таблицы user для
     * нового пользователя.
     * Вызываеться из модели SingupForm.
     */
    public function setPassword($password)
    {
        $this->password_hash = \Yii::$app->security->generatePasswordHash($password);
    }

    //Поиск пользователя по переданному секретному ключу
    // для смены пароля через почту
    public static function findBySecretKey($key)
    {
        if (!static::isSecretKeyExpire($key)) {
            return null;
        }
        return static::findOne([
            'secret_key' => $key,
        ]);
    }

    // Генерация секретного ключа
    // для смены пароля через почту
    public function generateSecretKey()
    {
        $this->secret_key = \Yii::$app->security->generateRandomString() . '_' . time();
    }


    // Удаление секретного ключа
    // для смены пароля через почту
    public function removeSecretKey()
    {
        $this->secret_key = null;
    }


    //Проверка срока действия секретного ключа
    public static function isSecretKeyExpire($key)
    {
        if (empty($key)) {
            return false;
        }
        $expire = \Yii::$app->params['secretKeyExpire'];
        $parts = explode('_', $key);
        $timestamp = (int)end($parts);

        return $timestamp + $expire >= time();
    }

    //Создание папки username
    public function createDirName()
    {
        if ($this->status === self::STATUS_ACTIVE) {

            $user_dir = UPLOAD . mb_convert_encoding($this->username, "windows-1251") . '/';
            $user_dir = mb_strtolower($user_dir, "windows-1251");
            if (!file_exists($user_dir)) {
                mkdir($user_dir, 0777);
            }

            return true;
        } else {
            return false;
        }
    }


    //Поиск пользователя по email или login
    public static function findIdentityByUsernameOrEmail($identity)
    {
        $users = self::find()->all();
        foreach ($users as $user) {
            if (($identity == $user->username) || ($identity == $user->email)){
                return $user;
            }
        }
        return false;
    }



    //Получить объект главного Админа
    public function getMainAdmin ()
    {
        return User::findOne(['role' => User::ROLE_MAIN_ADMIN]);
    }


    //Отправка письма на почту пользователю при изменении его статуса
    public function sendEmailUserStatus()
    {
        /* @var $user User */
        $user = User::findOne(
            [
                'email' => $this->email,
            ]
        );

        if($user){

            return Yii::$app->mailer->compose('change-status', ['user' => $user])
                //->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name.' (отправлено роботом)'])
                ->setFrom([Yii::$app->params['supportEmail'] => 'StartPool - Акселератор стартап-проектов'])
                ->setTo($this->email)
                ->setSubject('Изменение Вашего статуса на сайте StartPool')
                ->send();
        }

        return false;
    }



    //Создание беседы главного админа и админа при активации его статуса
    public function createConversationMainAdmin ()
    {

        $convers = ConversationMainAdmin::findOne(['admin_id' => $this->id]);

        if (!($convers)) {

            $conversation = new ConversationMainAdmin();
            $conversation->admin_id = $this->id;
            $conversation->main_admin_id = $this->mainAdmin->id;
            return $conversation->save() ? $conversation : null;

        }else{

            return $convers;
        }

    }



    //Создание беседы админа и проектанта при активации его статуса
    public function createConversationAdmin ($user)
    {

        $convers = ConversationAdmin::findOne(['user_id' => $user->id]);

        if (!($convers)) {

            $conversation = new ConversationAdmin();
            $conversation->user_id = $user->id;
            $conversation->admin_id = $user->id_admin;
            return $conversation->save() ? $conversation : null;

        }else{

            return $convers;
        }

    }



    //Отправка письма админу
    public function sendEmailAdmin($user)
    {

        if($user) {

            return Yii::$app->mailer->compose('signup-admin', ['user' => $user])
                //->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name.' (отправлено роботом)'])
                ->setFrom([Yii::$app->params['supportEmail'] => 'StartPool - Акселератор стартап-проектов'])
                ->setTo([Yii::$app->params['adminEmail']])
                ->setSubject('Регистрация нового пользователя на сайте StartPool')
                ->send();
        }

        return false;
    }



    //Проверка на пользователя
    public static function isUserSimple($username)
    {
        if (static::findOne(['username' => $username, 'role' => self::ROLE_USER, 'status' => self::STATUS_ACTIVE]))
        {
            return true;
        } else {
            return false;
        }
    }

    //Проверка на Админа
    public static function isUserAdmin($username)
    {
        if (static::findOne(['username' => $username, 'role' => self::ROLE_ADMIN, 'status' => self::STATUS_ACTIVE]))
        {
            return true;
        } else {
            return false;
        }
    }

    //Проверка на Главного Админа
    public static function isUserMainAdmin($username)
    {
        if (static::findOne(['username' => $username, 'role' => self::ROLE_MAIN_ADMIN, 'status' => self::STATUS_ACTIVE]))
        {
            return true;
        } else {
            return false;
        }
    }

    //Проверка на Разработчика
    public static function isUserDev($username)
    {
        if (static::findOne(['username' => $username, 'role' => self::ROLE_DEV, 'status' => self::STATUS_ACTIVE]))
        {
            return true;
        } else {
            return false;
        }
    }

    //Проверка на Статус
    public static function isActiveStatus($username)
    {
        if (static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]))
        {
            return true;
        } else {
            return false;
        }
    }
}
