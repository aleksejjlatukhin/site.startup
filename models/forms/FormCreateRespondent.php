<?php


namespace app\models\forms;

use app\models\interfaces\ConfirmationInterface;
use yii\base\Model;

abstract class FormCreateRespondent extends Model
{

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
     * Установить имя респондента
     * @param $name
     * @return mixed
     */
    abstract public function setName($name);


    /**
     * Получить имя респондента
     * @return mixed
     */
    abstract public function getName();


    /**
     * Установить id подтверждения
     * @param $id
     * @return mixed
     */
    abstract public function setConfirmId($id);


    /**
     * Получить id подтверждения
     * @return mixed
     */
    abstract public function getConfirmId();


    /**
     * Получить путь к кэшу формы
     * @param ConfirmationInterface $confirm
     * @return string
     */
    abstract public static function getCachePath(ConfirmationInterface $confirm);


    /**
     * Создать респондента
     * @return mixed
     */
    abstract public function create();


    /**
     * Проверка уникального имени
     * респондента в данном подтверждении
     * @param $attr
     * @return mixed
     */
    abstract public function uniqueName($attr);
}