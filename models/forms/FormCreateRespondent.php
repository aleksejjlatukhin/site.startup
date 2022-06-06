<?php


namespace app\models\forms;

use app\models\CreatorAnswersForNewRespond;
use app\models\interfaces\ConfirmationInterface;
use yii\base\Model;

/**
 * Форма создания респондента
 *
 * Class FormCreateRespondent
 * @package app\models\forms
 *
 * @property string $name                                               ФИО респондента
 * @property int $confirm_id                                            Идентификатор записи подтверждения гипотезы
 * @property CreatorAnswersForNewRespond $_creatorAnswers               Создатель пустых ответов на вопросы для нового респондента
 * @property CacheForm $_cacheManager                                   Менеджер кэширования
 * @property string $cachePath                                          Путь к файлу кэша
 */
abstract class FormCreateRespondent extends Model
{
    const CACHE_NAME = 'formCreateRespondCache';

    public $name;
    public $confirm_id;
    public $_creatorAnswers;
    public $_cacheManager;
    public $cachePath;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'trim'],
            [['name'], 'uniqueName'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Фамилия, имя, отчество',
        ];
    }

    /**
     * Получить путь к кэшу формы
     *
     * @param ConfirmationInterface $confirm
     * @return string
     */
    abstract public static function getCachePath(ConfirmationInterface $confirm);

    /**
     * Создать респондента
     *
     * @return mixed
     */
    abstract public function create();

    /**
     * Проверка уникального имени
     * респондента в данном подтверждении
     *
     * @param $attr
     * @return mixed
     */
    abstract public function uniqueName($attr);

    /**
     * @param int $id
     */
    public function setConfirmId($id)
    {
        $this->confirm_id = $id;
    }

    /**
     * @return int
     */
    public function getConfirmId()
    {
        return $this->confirm_id;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return CreatorAnswersForNewRespond
     */
    public function getCreatorAnswers()
    {
        return $this->_creatorAnswers;
    }

    /**
     *
     */
    public function setCreatorAnswers()
    {
        $this->_creatorAnswers = new CreatorAnswersForNewRespond();
    }

    /**
     * @return CacheForm
     */
    public function getCacheManager()
    {
        return $this->_cacheManager;
    }

    /**
     *
     */
    public function setCacheManager()
    {
        $this->_cacheManager = new CacheForm();
    }

    /**
     * @return string
     */
    public function getCachePathForm()
    {
        return $this->cachePath;
    }

    /**
     * @param string $cachePath
     */
    public function setCachePathForm($cachePath)
    {
        $this->cachePath = $cachePath;
    }
}