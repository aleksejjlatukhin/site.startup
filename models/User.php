<?

namespace app\models;

use app\modules\admin\models\ConversationMainAdmin;
use app\modules\admin\models\MessageMainAdmin;
use app\modules\admin\models\MessageManager;
use app\modules\expert\models\ConversationExpert;
use app\modules\expert\models\MessageExpert;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * Класс, который хранит информацию о пользователях
 *
 * Class User
 * @package app\models
 *
 * @property int $id                                Идентификатор пользователя
 * @property string $second_name                    Фамилия пользователя
 * @property string $first_name                     Имя пользователя
 * @property string $middle_name                    Отчетство пользователя
 * @property string $telephone                      Номер телефона пользователя
 * @property string $email                          Адрес эл. почты пользователя
 * @property string $username                       Логин пользователя
 * @property string $password_hash                  Хэшированный пароль пользователя хранится в бд
 * @property string $password                       Пароль пользователя не хранится в бд
 * @property string $avatar_max_image               Название загруженного файла с аватаром пользователя
 * @property string $avatar_image                   Название сформированного файла с аватаром пользователя
 * @property string $auth_key                       Ключ авторизации пользователя (пока не используется)
 * @property string $secret_key                     Секретный ключ для подтверждения регистрации (ограничен по времени действия)
 * @property int $role                              Проектная роль пользователя
 * @property int $status                            Статус пользователя
 * @property int $confirm                           Подтверждена ли регистрация пользователя
 * @property int $id_admin                          Поле для привязки проектанта к трекеру
 * @property int $created_at                        Дата регистрации пользователя
 * @property int $updated_at                        Дата обновления пользователя (его данных)
 */
class User extends ActiveRecord implements IdentityInterface
{

    const STATUS_DELETED = 0; // Заблокирован
    const STATUS_NOT_ACTIVE = 1; // Не активирован
    const STATUS_ACTIVE = 10; // Активирован

    const ROLE_USER = 10;           // Роль проектанта
    const ROLE_ADMIN = 20;          // Роль трекера
    const ROLE_ADMIN_COMPANY = 25;  // Роль администратора организации
    const ROLE_MAIN_ADMIN = 30;     // Роль гл.администратора платформы
    const ROLE_EXPERT = 40;         // Роль эксперта
    const ROLE_MANAGER = 50;        // Роль менеждера по клиентам (организациям) от платформы
    const ROLE_DEV = 100;           // Роль тех.поддержки

    const CONFIRM = 20; // Регистрация подтверждена
    const NOT_CONFIRM = 10; // Регистрация не подтверждена

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
            if ($keywords = $this->keywords) {
                $keywords->delete();
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
     * Получить ключевые слова
     * о деятельности эксперта
     * @return ActiveQuery
     */
    public function getKeywords()
    {
        return $this->hasOne(KeywordsExpert::class, ['expert_id' => 'id']);
    }


    /**
     * Получить объекты доступа класса UserAccessToProjects
     * стороннего пользователя к проектам
     *
     * @return ActiveQuery
     */
    public function getUserAccessToProject()
    {
        return $this->hasOne(UserAccessToProjects::class, ['user_id' => 'id']);
    }


    /**
     * Получить объект доступа класса UserAccessToProjects
     * стороннего пользователя к конкретному проекту
     *
     * @param $id
     * @return array|ActiveRecord|null
     */
    public function findUserAccessToProject($id)
    {
        $access = UserAccessToProjects::find()
            ->where(['user_id' => $this->id])
            ->andWhere(['project_id' => $id])
            ->orderBy('id DESC')
            ->one();

        return $access;
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
     * Поиск записи в таблице client_user
     * по данному пользователю
     *
     * @return ActiveQuery
     */
    public function getClientUser()
    {
        return $this->hasOne(ClientUser::class, ['user_id' => 'id']);
    }


    /**
     * @return ActiveQuery
     */
    public function getCustomerManagersByUserId()
    {
        return $this->hasMany(CustomerManager::class, ['user_id' => 'id']);
    }


    /**
     * @return CustomerManager|null
     */
    public function findCustomerManagersByUserId()
    {
        return CustomerManager::findOne(['user_id' => $this->id]);
    }


    /**
     * @return ActiveQuery
     */
    public function getCustomerTrackersByUserId()
    {
        return $this->hasMany(CustomerTracker::class, ['user_id' => 'id']);
    }


    /**
     * @return CustomerTracker|null
     */
    public function findCustomerTrackersByUserId()
    {
        return CustomerTracker::findOne(['user_id' => $this->id, 'status' => CustomerTracker::ACTIVE]);
    }


    /**
     * @return ActiveQuery
     */
    public function getCustomerExpertsByUserId()
    {
        return $this->hasMany(CustomerExpert::class, ['user_id' => 'id']);
    }


    /**
     * @return CustomerExpert|null
     */
    public function findCustomerExpertsByUserId()
    {
        return CustomerExpert::findOne(['user_id' => $this->id, 'status' => CustomerExpert::ACTIVE]);
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
     *
     * @return bool
     */
    public function getCheckOnline()
    {
        if ($checkingOnline = $this->checkingOnline) return $checkingOnline->isOnline();
        return false;
    }


    /**
     * Получить объект главного админа или админа организации
     *
     * @return User|null
     */
    public function getMainAdmin ()
    {
        if ($this->role !== self::ROLE_ADMIN_COMPANY) {
            /** @var ClientUser $clientUser */
            $clientUser = $this->clientUser;
            $mainAdminId = ClientSettings::find()
                ->select('admin_id')
                ->where(['client_id' => $clientUser->getClientId()])
                ->one();

            return self::findOne($mainAdminId);
        }
        return self::findOne(['role' => self::ROLE_MAIN_ADMIN]);
    }


    /**
     * Получить объект трекера
     *
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
     *
     * @return User|null
     */
    public function getDevelopment ()
    {
        return User::findOne(['role' => User::ROLE_DEV]);
    }


    /**
     * Отправка письма на почту пользователю при изменении его статуса
     *
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
     *
     * @return ConversationDevelopment|null
     */
    public function createConversationDevelopment ()
    {
        $con = ConversationDevelopment::findOne(['user_id' => $this->id]);

        if (!$con) {
            $conversation = new ConversationDevelopment();
            $conversation->setUserId($this->id);
            $conversation->setDevId($this->getDevelopment()->id);
            return $conversation->save() ? $conversation : null;
        }else{
            return $con;
        }
    }


    /**
     * Создание беседы админа организации и трекера
     *
     * @return ConversationMainAdmin|null
     */
    public function createConversationMainAdmin ()
    {
        $mainAdmin = $this->getMainAdmin();
        $con = ConversationMainAdmin::findOne([
            'main_admin_id' => $mainAdmin->id,
            'admin_id' => $this->id
        ]);

        if (!$con) {
            $conversation = new ConversationMainAdmin();
            $conversation->setAdminId($this->id);
            $conversation->setMainAdminId($mainAdmin->getId());
            return $conversation->save() ? $conversation : null;
        }else{
            return $con;
        }
    }


    /**
     * Создание беседы трекером и проектанта
     *
     * @param User $user
     * @return ConversationAdmin|null
     */
    public function createConversationAdmin ($user)
    {
        $con = ConversationAdmin::findOne(['user_id' => $user->id]);

        if (!$con) {
            $conversation = new ConversationAdmin();
            $conversation->setUserId($user->getId());
            $conversation->setAdminId($user->getIdAdmin());
            return $conversation->save() ? $conversation : null;
        }else{
            return $con;
        }
    }


    /**
     * Создание беседы любого пользователя (только не эксперта) и
     * эксперта при активации его статуса
     *
     * @param User $user
     * @param User $expert
     * @return ConversationExpert|null
     */
    public static function createConversationExpert ($user, $expert)
    {
        $con = ConversationExpert::findOne(['user_id' => $user->id, 'expert_id' => $expert->id]);

        if (!$con) {
            $conversation = new ConversationExpert();
            $conversation->setUserId($user->getId());
            $conversation->setExpertId($expert->getId());
            $conversation->setRole($user->getRole());
            return $conversation->save() ? $conversation : null;
        }else{
            return $con;
        }

    }


    /**
     * Отправка письма админу организации
     *
     * @param User $user
     * @return bool
     */
    public function sendEmailAdmin($user)
    {
        if($user) {

            /** @var User $admin*/
            $admin = $user->getMainAdmin();

            return Yii::$app->mailer->compose('signup-admin', ['user' => $user])
                ->setFrom([Yii::$app->params['supportEmail'] => 'Spaccel.ru - Акселератор стартап-проектов'])
                ->setTo([$admin->email])
                ->setSubject('Регистрация нового пользователя на сайте Spaccel.ru')
                ->send();
        }
        return false;
    }


    /**
     * Общее кол-во непрочитанных
     * сообщений пользователя
     *
     * @return bool|int|string
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
        elseif (self::isUserMainAdmin($this->username) || self::isUserAdminCompany($this->username)) {

            $countUnreadMessagesMainAdmin = MessageMainAdmin::find()->where(['adressee_id' => $this->id, 'status' => MessageMainAdmin::NO_READ_MESSAGE])->count();
            $countUnreadMessagesDev = MessageDevelopment::find()->where(['adressee_id' => $this->id, 'status' => MessageDevelopment::NO_READ_MESSAGE])->count();
            $countUnreadMessagesExpert = MessageExpert::find()->where(['adressee_id' => $this->id, 'status' => MessageExpert::NO_READ_MESSAGE])->count();
            $countUnreadMessagesManager = MessageManager::find()->where(['adressee_id' => $this->id, 'status' => MessageManager::NO_READ_MESSAGE])->count();
            $count = ($countUnreadMessagesMainAdmin + $countUnreadMessagesDev + $countUnreadMessagesExpert + $countUnreadMessagesManager);
        }
        elseif (self::isUserDev($this->username)) {

            $count = MessageDevelopment::find()->where(['adressee_id' => $this->id, 'status' => MessageDevelopment::NO_READ_MESSAGE])->count();
        }
        elseif (self::isUserExpert($this->username)) {

            $countUnreadMessagesExpert = MessageExpert::find()->where(['adressee_id' => $this->id, 'status' => MessageExpert::NO_READ_MESSAGE])->count();
            $countUnreadMessagesDev = MessageDevelopment::find()->where(['adressee_id' => $this->id, 'status' => MessageDevelopment::NO_READ_MESSAGE])->count();
            $count = ($countUnreadMessagesExpert + $countUnreadMessagesDev);
        }
        elseif (self::isUserManager($this->username)) {

            $countUnreadMessagesManager = MessageManager::find()->where(['adressee_id' => $this->id, 'status' => MessageManager::NO_READ_MESSAGE])->count();
            $countUnreadMessagesDev = MessageDevelopment::find()->where(['adressee_id' => $this->id, 'status' => MessageDevelopment::NO_READ_MESSAGE])->count();
            $count = ($countUnreadMessagesManager + $countUnreadMessagesDev);
        }

        return ($count > 0) ? $count : false;
    }


    /**
     * Общее кол-во непрочитанных
     * уведомлений пользователя
     *
     * @return bool|int|string
     */
    public function getCountUnreadCommunications()
    {
        $count = 0;

        if (self::isUserExpert($this->username)) {

            $countUnreadProjectCommunications = ProjectCommunications::find()->where(['adressee_id' => $this->getId(), 'status' => ProjectCommunications::NO_READ])->count();
            $count += $countUnreadProjectCommunications;
        }
        elseif (self::isUserMainAdmin($this->username) || self::isUserAdminCompany($this->username)) {

            $countUnreadProjectCommunications = ProjectCommunications::find()->where(['adressee_id' => $this->getId(), 'status' => ProjectCommunications::NO_READ])->count();
            $count += $countUnreadProjectCommunications;
        }
        elseif (self::isUserAdmin($this->username)) {

            $countDuplicateCommunications = DuplicateCommunications::find()->where(['adressee_id' => $this->getId(), 'status' => DuplicateCommunications::NO_READ])->count();
            $count += $countDuplicateCommunications;
        }
        elseif (self::isUserSimple($this->username)) {

            $countDuplicateCommunications = DuplicateCommunications::find()->where(['adressee_id' => $this->getId(), 'status' => DuplicateCommunications::NO_READ])->count();
            $count += $countDuplicateCommunications;
        }

        return ($count > 0) ? $count : false;
    }


    /**
     * Количество непрочитанных
     * уведомлений пользователя
     * по проекту
     *
     * @param int $id
     * @return bool|int|string
     */
    public function getCountUnreadCommunicationsByProject($id)
    {
        $count = 0;

        if (self::isUserExpert($this->username)) {

            $countUnreadProjectCommunications = ProjectCommunications::find()
                ->where([
                    'adressee_id' => $this->getId(),
                    'status' => ProjectCommunications::NO_READ,
                    'project_id' => $id
                ])->count();

            $count += $countUnreadProjectCommunications;
        }
        elseif (self::isUserMainAdmin($this->username)) {

            $countUnreadProjectCommunications = ProjectCommunications::find()
                ->where([
                    'adressee_id' => $this->getId(),
                    'status' => ProjectCommunications::NO_READ,
                    'project_id' => $id
                ])->count();

            $count += $countUnreadProjectCommunications;
        }

        return ($count > 0) ? $count : false;
    }


    /**
     * @return bool|int|string
     * Кол-во непрочитанных сообщений от трекера
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

        elseif (self::isUserManager($this->username)) {

            $count = MessageManager::find()->where(['sender_id' => $this->mainAdmin->id, 'adressee_id' => $this->id, 'status' => MessageManager::NO_READ_MESSAGE])->count();
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
     * Кол-во непрочитанных сообщений гл.алдминистратора,
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
     *
     * Кол-во непрочитанных сообщений эксперта,
     * где он является отправителем для админа организации
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
     * @return bool|int|string
     *
     * Кол-во непрочитанных сообщений менеджера,
     * где он является отправителем для админа Spaccel
     */
    public function getCountUnreadMessagesMainAdminFromManager ()
    {
        $count = 0;

        if (self::isUserManager($this->username)) {

            $count = MessageManager::find()->where(['adressee_id' => $this->mainAdmin->id, 'sender_id' => $this->id, 'status' => MessageManager::NO_READ_MESSAGE])->count();
        }

        return ($count > 0) ? $count : false;
    }


    /**
     * Кол-во непрочитанных сообщений от менеджера
     * для пользователя, у которого id => $userId
     *
     * @param int $userId
     * @return bool|int|string
     */
    public function getCountUnreadMessagesFromManager($userId)
    {
        $count = 0;

        if (self::isUserManager($this->username)) {

            $count = MessageManager::find()->where(['adressee_id' => $userId, 'sender_id' => $this->id, 'status' => MessageManager::NO_READ_MESSAGE])->count();
        }

        return ($count > 0) ? $count : false;
    }


    /**
     * Кол-во непрочитанных сообщений
     * от пользователя для эксперта
     *
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
     *
     * @param $id
     * @return bool|int|string
     */
    public function getCountUnreadMessagesExpertFromUser($id)
    {

        $count = MessageExpert::find()->where(['adressee_id' => $this->id, 'sender_id' => $id, 'status' => MessageExpert::NO_READ_MESSAGE])->count();

        return ($count > 0) ? $count : false;
    }


    /**
     * Количество непрочитанных сообщений у менеджера от пользователя
     * (админа организации, трекера или админа Spaccel)
     *
     * @param $id
     * @return bool|int|string
     */
    public function getCountUnreadMessagesManager($id)
    {

        $count = MessageManager::find()->where(['adressee_id' => $this->id, 'sender_id' => $id, 'status' => MessageManager::NO_READ_MESSAGE])->count();

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
     * Проверка на трекера
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
     *
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
     * Проверка на Техподдержку
     *
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
     * Проверка на менеджера по клиентам
     * @param $username
     * @return bool
     */
    public static function isUserManager($username)
    {
        if (static::findOne(['username' => $username, 'role' => self::ROLE_MANAGER, 'status' => self::STATUS_ACTIVE]))
        {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Проверка на администратора организации
     * @param $username
     * @return bool
     */
    public static function isUserAdminCompany($username)
    {
        if (static::findOne(['username' => $username, 'role' => self::ROLE_ADMIN_COMPANY, 'status' => self::STATUS_ACTIVE]))
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
     * @return string
     */
    public function getSecondName()
    {
        return $this->second_name;
    }

    /**
     * @param string $second_name
     */
    public function setSecondName($second_name)
    {
        $this->second_name = $second_name;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @param string $first_name
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
    }

    /**
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middle_name;
    }

    /**
     * @param string $middle_name
     */
    public function setMiddleName($middle_name)
    {
        $this->middle_name = $middle_name;
    }

    /**
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param string $telephone
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getAvatarMaxImage()
    {
        return $this->avatar_max_image;
    }

    /**
     * @param string $avatar_max_image
     */
    public function setAvatarMaxImage($avatar_max_image)
    {
        $this->avatar_max_image = $avatar_max_image;
    }

    /**
     * @return string
     */
    public function getAvatarImage()
    {
        return $this->avatar_image;
    }

    /**
     * @param string $avatar_image
     */
    public function setAvatarImage($avatar_image)
    {
        $this->avatar_image = $avatar_image;
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
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getConfirm()
    {
        return $this->confirm;
    }

    /**
     * @param int $confirm
     */
    public function setConfirm($confirm)
    {
        $this->confirm = $confirm;
    }

    /**
     * @return int
     */
    public function getIdAdmin()
    {
        return $this->id_admin;
    }

    /**
     * @param int $id_admin
     */
    public function setIdAdmin($id_admin)
    {
        $this->id_admin = $id_admin;
    }

    /**
     * @return int
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @return int
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
}
