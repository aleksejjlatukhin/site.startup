<?

namespace app\models;

use app\modules\admin\models\ConversationMainAdmin;
use app\modules\admin\models\MessageMainAdmin;
use app\modules\expert\models\ConversationExpert;
use app\modules\expert\models\MessageExpert;
use Throwable;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;
use yii\helpers\FileHelper;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
use Yii;

class User extends ActiveRecord implements IdentityInterface
{

    const STATUS_DELETED = 0;
    const STATUS_NOT_ACTIVE = 1;
    const STATUS_ACTIVE = 10;
    const STATUS_REMOVE = 200;

    const ROLE_USER = 10;
    const ROLE_ADMIN = 20;
    const ROLE_MAIN_ADMIN = 30;
    const ROLE_EXPERT = 40;
    const ROLE_DEV = 100;

    const CONFIRM = 20;
    const NOT_CONFIRM = 10;

    public $password;


    /**
     * @return string
     */
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
            [['second_name', 'first_name', 'middle_name', 'telephone', 'username', 'email', 'password', 'avatar_max_image', 'avatar_image'], 'filter', 'filter' => 'trim'],
            [['second_name', 'first_name', 'middle_name', 'email', 'telephone', 'avatar_max_image', 'avatar_image'], 'string', 'max' => 255],
            [['second_name', 'first_name', 'middle_name', 'username', 'email'], 'required'],
            [['role', 'status', 'confirm', 'id_admin'], 'integer'],
            ['email', 'email'],
            ['username', 'match', 'pattern' => '/[a-z]+/i', 'message' => '{attribute} должен содержать только латиницу!'],
            ['username', 'string', 'min' => 3, 'max' => 32],
            ['password', 'string', 'min' => 6, 'max' => 32],
            ['password', 'required', 'on' => 'create'],
            ['username', 'unique', 'message' => 'Этот логин уже занят.'],
            ['email', 'unique', 'message' => 'Эта почта уже зарегистрирована.'],
            ['secret_key', 'unique'],
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
            'email' => 'Email',
            'password' => 'Password',
            'status' => 'Статус',
            'role' => 'Проектная роль',
            'auth_key' => 'Auth Key',
            'created_at' => 'Дата регистрации',
            'updated_at' => 'Последнее изменение',
        ];
    }


    public function init()
    {
        $this->on(self::EVENT_AFTER_DELETE, function (){
            if ($expertInfo = $this->expertInfo){
                $expertInfo->delete();
            }
        });

        parent::init();
    }


    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }


    /**
     * Получить все проекты пользователя
     * @return ActiveQuery
     */
    public function getProjects()
    {
        return $this->hasMany(Projects::class, ['user_id' => 'id']);
    }


    /**
     * Получить подробную
     * информацию o эсперте
     * @return ActiveQuery
     */
    public function getExpertInfo()
    {
        return $this->hasOne(ExpertInfo::class, ['user_id' => 'id']);
    }


    /**
     * Аутентификация пользователей
     * @param int|string $id
     * @return User|IdentityInterface|null
     */
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
        //return static::findOne(['access_token' => $token]);
    }


    /**
     * Находит пользователя по имени и возвращает объект найденного пользователя
     * @param $username
     * @return User|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }


    /**
     * Находит пользователя по емайл
     * @param $email
     * @return User|null
     */
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
     * @param $password
     * @return bool
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }


    /**
     * Генерирует случайную строку из 32 шестнадцатеричных символов и присваивает (при записи) полученное значение полю auth_key
     * таблицы user для нового пользователя.
     * Вызываеться из модели RegForm.
     * @throws Exception
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }


    /**
     * Генерирует хеш из введенного пароля и присваивает (при записи)
     * полученное значение полю password_hash таблицы user для нового пользователя.
     * Вызываеться из модели SingupForm.
     * @param $password
     * @throws Exception
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }


    /**
     * Поиск пользователя по переданному секретному ключу
     * для смены пароля через почту
     * @param $key
     * @return User|null
     */
    public static function findBySecretKey($key)
    {
        if (!static::isSecretKeyExpire($key)) {
            return null;
        }
        return static::findOne([
            'secret_key' => $key,
        ]);
    }


    /**
     * Генерация секретного ключа
     * для смены пароля через почту
     * @throws Exception
     */
    public function generateSecretKey()
    {
        $this->secret_key = Yii::$app->security->generateRandomString() . '_' . time();
    }


    /**
     * Удаление секретного ключа
     * для смены пароля через почту
     */
    public function removeSecretKey()
    {
        $this->secret_key = null;
    }


    /**
     * Проверка срока действия секретного ключ
     * @param $key
     * @return bool
     */
    public static function isSecretKeyExpire($key)
    {
        if (empty($key)) {
            return false;
        }
        $expire = Yii::$app->params['secretKeyExpire'];
        $parts = explode('_', $key);
        $timestamp = (int)end($parts);

        return $timestamp + $expire >= time();
    }


    /**
     * Поиск пользователя по email или login
     * @param $identity
     * @return bool|mixed|ActiveRecord
     */
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


    /**
     * Получить объект проверки статуса онлайн
     * @return ActiveQuery
     */
    public function getCheckingOnline()
    {
        return $this->hasOne(CheckingOnlineUser::class, ['user_id' => 'id']);
    }


    /**
     * Получить статус пользователя онлайн или время посл.активности
     * @return bool
     */
    public function getCheckOnline()
    {
        if ($checkingOnline = $this->checkingOnline) return $checkingOnline->isOnline();
        return false;
    }


    /**
     * Получить объект главного Админа
     * @return User|null
     */
    public function getMainAdmin ()
    {
        return User::findOne(['role' => User::ROLE_MAIN_ADMIN]);
    }


    /**
     * Получить объект Админа
     * @return bool|ActiveQuery
     */
    public function getAdmin ()
    {
        if ($this->role === self::ROLE_USER) {
            return $this->hasOne(self::class, ['id' => 'id_admin']);
        }
        return false;
    }


    /**
     * Получить объект техподдержки
     * @return User|null
     */
    public function getDevelopment ()
    {
        return User::findOne(['role' => User::ROLE_DEV]);
    }


    /**
     * Отправка письма на почту пользователю при изменении его статуса
     * @return bool
     */
    public function sendEmailUserStatus()
    {
        /* @var $user User */
        $user = User::findOne(['email' => $this->email]);

        if($user){

            return Yii::$app->mailer->compose('change-status', ['user' => $user])
                ->setFrom([Yii::$app->params['supportEmail'] => 'Spaccel.ru - Акселератор стартап-проектов'])
                ->setTo($this->email)
                ->setSubject('Изменение Вашего статуса на сайте Spaccel.ru')
                ->send();
        }

        return false;
    }


    /**
     * Создание беседы техподдержки и пользователя при активации его статуса
     * @return ConversationDevelopment|null
     */
    public function createConversationDevelopment ()
    {
        $convers = ConversationDevelopment::findOne(['user_id' => $this->id]);

        if (!($convers)) {

            $conversation = new ConversationDevelopment();
            $conversation->user_id = $this->id;
            $conversation->dev_id = $this->development->id;
            return $conversation->save() ? $conversation : null;

        }else{

            return $convers;
        }

    }


    /**
     * Создание беседы главного админа и админа при активации его статуса
     * @return ConversationMainAdmin|null
     */
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


    /**
     * Создание беседы админа и проектанта при активации его статуса
     * @param $user
     * @return ConversationAdmin|null
     */
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


    /**
     * Создание беседы главного админа и
     * эксперта при активации его статуса
     * @param $user
     * @param $expert
     * @return ConversationExpert|null
     */
    public static function createConversationExpert ($user, $expert)
    {
        $convers = ConversationExpert::findOne(['user_id' => $user->id, 'expert_id' => $expert->id]);

        if (!($convers)) {

            $conversation = new ConversationExpert();
            $conversation->user_id = $user->id;
            $conversation->expert_id = $expert->id;
            $conversation->role = $user->role;
            return $conversation->save() ? $conversation : null;

        }else{

            return $convers;
        }

    }


    /**
     * Отправка письма админу
     * @param $user
     * @return bool
     */
    public function sendEmailAdmin($user)
    {
        if($user) {

            return Yii::$app->mailer->compose('signup-admin', ['user' => $user])
                ->setFrom([Yii::$app->params['supportEmail'] => 'Spaccel.ru - Акселератор стартап-проектов'])
                ->setTo([Yii::$app->params['adminEmail']])
                ->setSubject('Регистрация нового пользователя на сайте Spaccel.ru')
                ->send();
        }

        return false;
    }


    /**
     * @return bool|int|string
     * Общее кол-во непрочитанных сообщений пользователя
     */
    public function getCountUnreadMessages()
    {
        $count = 0;

        if (self::isUserSimple($this->username)) {

            $countUnreadMessagesAdmin = MessageAdmin::find()->where(['adressee_id' => $this->id, 'status' => MessageAdmin::NO_READ_MESSAGE])->count();
            $countUnreadMessagesDev = MessageDevelopment::find()->where(['adressee_id' => $this->id, 'status' => MessageDevelopment::NO_READ_MESSAGE])->count();
            $countUnreadMessagesExpert = MessageExpert::find()->where(['adressee_id' => $this->id, 'status' => MessageExpert::NO_READ_MESSAGE])->count();
            $count = ($countUnreadMessagesAdmin + $countUnreadMessagesDev + $countUnreadMessagesExpert);
        }
        elseif (self::isUserAdmin($this->username)) {

            $countUnreadMessagesAdmin = MessageAdmin::find()->where(['adressee_id' => $this->id, 'status' => MessageAdmin::NO_READ_MESSAGE])->count();
            $countUnreadMessagesMainAdmin = MessageMainAdmin::find()->where(['adressee_id' => $this->id, 'status' => MessageMainAdmin::NO_READ_MESSAGE])->count();
            $countUnreadMessagesDev = MessageDevelopment::find()->where(['adressee_id' => $this->id, 'status' => MessageDevelopment::NO_READ_MESSAGE])->count();
            $countUnreadMessagesExpert = MessageExpert::find()->where(['adressee_id' => $this->id, 'status' => MessageExpert::NO_READ_MESSAGE])->count();
            $count = ($countUnreadMessagesAdmin + $countUnreadMessagesMainAdmin + $countUnreadMessagesDev + $countUnreadMessagesExpert);
        }
        elseif (self::isUserMainAdmin($this->username)) {

            $countUnreadMessagesMainAdmin = MessageMainAdmin::find()->where(['adressee_id' => $this->id, 'status' => MessageMainAdmin::NO_READ_MESSAGE])->count();
            $countUnreadMessagesDev = MessageDevelopment::find()->where(['adressee_id' => $this->id, 'status' => MessageDevelopment::NO_READ_MESSAGE])->count();
            $countUnreadMessagesExpert = MessageExpert::find()->where(['adressee_id' => $this->id, 'status' => MessageExpert::NO_READ_MESSAGE])->count();
            $count = ($countUnreadMessagesMainAdmin + $countUnreadMessagesDev + $countUnreadMessagesExpert);
        }
        elseif (self::isUserDev($this->username)) {

            $count = MessageDevelopment::find()->where(['adressee_id' => $this->id, 'status' => MessageDevelopment::NO_READ_MESSAGE])->count();
        }
        elseif (self::isUserExpert($this->username)) {

            $countUnreadMessagesExpert = MessageExpert::find()->where(['adressee_id' => $this->id, 'status' => MessageExpert::NO_READ_MESSAGE])->count();
            $countUnreadMessagesDev = MessageDevelopment::find()->where(['adressee_id' => $this->id, 'status' => MessageDevelopment::NO_READ_MESSAGE])->count();
            $count = ($countUnreadMessagesExpert + $countUnreadMessagesDev);
        }

        return ($count > 0) ? $count : false;
    }


    /**
     * @return bool|int|string
     * Кол-во непрочитанных сообщений от Админа
     */
    public function getCountUnreadMessagesFromAdmin ()
    {
        $count = 0;

        if (self::isUserSimple($this->username)) {

            $count = MessageAdmin::find()->where(['adressee_id' => $this->id, 'status' => MessageAdmin::NO_READ_MESSAGE])->count();
        }

        return ($count > 0) ? $count : false;
    }


    /**
     * @return bool|int|string
     * Кол-во непрочитанных сообщений от Техподдержки
     */
    public function getCountUnreadMessagesFromDev ()
    {
        $count = MessageDevelopment::find()->where(['adressee_id' => $this->id, 'status' => MessageDevelopment::NO_READ_MESSAGE])->count();
        return ($count > 0) ? $count : false;
    }


    /**
     * @return bool|int|string
     * Кол-во непрочитанных сообщений от главного админа
     */
    public function getCountUnreadMessagesFromMainAdmin ()
    {
        $count = 0;

        if (self::isUserAdmin($this->username)) {

            $count = MessageMainAdmin::find()->where(['adressee_id' => $this->id, 'status' => MessageMainAdmin::NO_READ_MESSAGE])->count();
        }

        return ($count > 0) ? $count : false;
    }


    /**
     * @return bool|int|string
     * Кол-во непрочитанных сообщений от главного админа для эксперта
     */
    public function getCountUnreadMessagesExpertFromMainAdmin ()
    {
        $count = 0;

        if (self::isUserExpert($this->username)) {

            $count = MessageExpert::find()->where(['sender_id' => $this->mainAdmin->id, 'adressee_id' => $this->id, 'status' => MessageExpert::NO_READ_MESSAGE])->count();
        }

        return ($count > 0) ? $count : false;
    }


    /**
     * @return bool|int|string
     * Кол-во непрочитанных сообщений пользователя,
     * где он является отправителем
     */
    public function getCountUnreadMessagesFromUser ()
    {
        $count = 0;

        if (self::isUserSimple($this->username)) {

            $count = MessageAdmin::find()->where(['sender_id' => $this->id, 'status' => MessageAdmin::NO_READ_MESSAGE])->count();
        }

        return ($count > 0) ? $count : false;
    }


    /**
     * @return bool|int|string
     */
    public function getCountUnreadMessagesDevelopmentFromUser ()
    {
        $count = MessageDevelopment::find()->where(['sender_id' => $this->id, 'status' => MessageDevelopment::NO_READ_MESSAGE])->count();
        return ($count > 0) ? $count : false;
    }


    /**
     * @return bool|int|string
     * Кол-во непрочитанных сообщений алдминистратора,
     * где он является отправителем
     */
    public function getCountUnreadMessagesMainAdminFromAdmin ()
    {
        $count = 0;

        if (self::isUserAdmin($this->username)) {

            $count = MessageMainAdmin::find()->where(['sender_id' => $this->id, 'status' => MessageMainAdmin::NO_READ_MESSAGE])->count();
        }

        return ($count > 0) ? $count : false;
    }


    /**
     * @return bool|int|string
     * Кол-во непрочитанных сообщений эксперта,
     * где он является отправителем для гл.админа
     */
    public function getCountUnreadMessagesMainAdminFromExpert ()
    {
        $count = 0;

        if (self::isUserExpert($this->username)) {

            $count = MessageExpert::find()->where(['adressee_id' => $this->mainAdmin->id, 'sender_id' => $this->id, 'status' => MessageExpert::NO_READ_MESSAGE])->count();
        }

        return ($count > 0) ? $count : false;
    }


    /**
     * Кол-во непрочитанных сообщений
     * от пользователя для эксперта
     * @param $id
     * @return bool|int|string
     */
    public function getCountUnreadMessagesUserFromExpert($id)
    {

        $count = MessageExpert::find()->where(['adressee_id' => $id, 'sender_id' => $this->id, 'status' => MessageExpert::NO_READ_MESSAGE])->count();

        return ($count > 0) ? $count : false;
    }


    /**
     * Кол-во непрочитанных сообщений
     * от эксперта для пользователя
     * @param $id
     * @return bool|int|string
     */
    public function getCountUnreadMessagesExpertFromUser($id)
    {

        $count = MessageExpert::find()->where(['adressee_id' => $this->id, 'sender_id' => $id, 'status' => MessageExpert::NO_READ_MESSAGE])->count();

        return ($count > 0) ? $count : false;
    }


    /**
     * Проверка на пользователя
     * @param $username
     * @return bool
     */
    public static function isUserSimple($username)
    {
        if (static::findOne(['username' => $username, 'role' => self::ROLE_USER, 'status' => self::STATUS_ACTIVE]))
        {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Проверка на Админа
     * @param $username
     * @return bool
     */
    public static function isUserAdmin($username)
    {
        if (static::findOne(['username' => $username, 'role' => self::ROLE_ADMIN, 'status' => self::STATUS_ACTIVE]))
        {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Проверка на Главного Админа
     * @param $username
     * @return bool
     */
    public static function isUserMainAdmin($username)
    {
        if (static::findOne(['username' => $username, 'role' => self::ROLE_MAIN_ADMIN, 'status' => self::STATUS_ACTIVE]))
        {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Проверка на Эксперта
     * @param $username
     * @return bool
     */
    public static function isUserExpert($username)
    {
        if (static::findOne(['username' => $username, 'role' => self::ROLE_EXPERT, 'status' => self::STATUS_ACTIVE]))
        {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Проверка на Разработчика
     * @param $username
     * @return bool
     */
    public static function isUserDev($username)
    {
        if (static::findOne(['username' => $username, 'role' => self::ROLE_DEV, 'status' => self::STATUS_ACTIVE]))
        {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Проверка на Статус
     * @param $username
     * @return bool
     */
    public static function isActiveStatus($username)
    {
        if (static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]))
        {
            return true;
        } else {
            return false;
        }
    }


    /**
     * @throws Throwable
     * @throws ErrorException
     * @throws StaleObjectException
     */
    public function removeAllDataUser ()
    {
        // Удаление проектов
        if ($projects = $this->projects){
            foreach ($projects as $project) {
                $project->deleteStage();
            }
        }

        if ($this->role === self::ROLE_USER) {
            // Удаление беседы и сообщений с админом
            if ($conversation_admin = ConversationAdmin::findOne(['user_id' => $this->id])) {
                MessageAdmin::deleteAll(['conversation_id' => $conversation_admin->id]);
                $conversation_admin->delete();
            }
        }
        elseif ($this->role === self::ROLE_ADMIN) {
            // Удаление беседы и сообщений с главным админом
            if ($conversations_main_admin = ConversationMainAdmin::findOne(['admin_id' => $this->id])) {
                MessageMainAdmin::deleteAll(['conversation_id' => $conversations_main_admin->id]);
                $conversations_main_admin->delete();
            }
        }

        // Удаление беседы и сообщений с тех.поддержкой
        if ($conversation_dev = ConversationDevelopment::findOne(['user_id' => $this->id])) {
            MessageDevelopment::deleteAll(['conversation_id' => $conversation_dev->id]);
            $conversation_dev->delete();
        }

        // Удаление проверки на онлайн
        $checkingOnline = $this->checkingOnline;
        if ($checkingOnline) $checkingOnline->delete();

        // Удаление директории пользователя
        $projectPathDelete = UPLOAD.'/user-'.$this->id;
        if (file_exists($projectPathDelete)) FileHelper::removeDirectory($projectPathDelete);

        // Удаление кэша для форм пользователя
        $cachePathDelete = '../runtime/cache/forms/user-'.$this->id;
        if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

        // Удаление пользователя
        $this->delete();

        return true;
    }
}
