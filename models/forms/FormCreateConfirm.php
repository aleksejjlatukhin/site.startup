<?php


namespace app\models\forms;

use app\models\CreatorNewRespondsOnConfirmFirstStep;
use app\models\CreatorRespondsFromAgentsOnConfirmFirstStep;
use yii\base\Model;

/**
 * Форма создания подтверждения гипотезы
 *
 * Class FormCreateConfirm
 * @package app\models\forms
 *
 * @property int $hypothesis_id                                                     Идентификатор гипотезы
 * @property int $count_respond                                                     Количество респондентов
 * @property int $count_positive                                                    Количество респондентов, которые подтверждают гипотезу
 * @property int $add_count_respond                                                 Количество новых респондентов, добавленных к опросу на данном этапе
 * @property CreatorRespondsFromAgentsOnConfirmFirstStep $_creatorResponds          Создатель респондентов для подтверждения гипотезы из респондентов, которые подтвердили ранее идущий этап
 * @property CreatorNewRespondsOnConfirmFirstStep $_creatorNewResponds              Создатель новых респондентов для программы подтверждения
 * @property CacheForm $_cacheManager                                               Менеджер кэширования
 * @property string $cachePath                                                      Путь к файлу кэша
 */
abstract class FormCreateConfirm extends Model
{

    const CACHE_NAME = 'formCreateConfirmCache';

    public $hypothesis_id;
    public $count_respond;
    public $count_positive;
    public $add_count_respond;
    protected $_creatorResponds;
    protected $_creatorNewResponds;
    public $_cacheManager;
    public $cachePath;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['count_respond', 'count_positive'], 'required'],
            [['count_respond', 'count_positive'], 'integer', 'integerOnly' => TRUE, 'min' => '1'],
            [['count_respond', 'count_positive'], 'integer', 'integerOnly' => TRUE, 'max' => '100'],
        ];
    }


    abstract public function create ();

    /**
     * @return int
     */
    public function getHypothesisId()
    {
        return $this->hypothesis_id;
    }

    /**
     * @param int $hypothesis_id
     */
    public function setHypothesisId($hypothesis_id)
    {
        $this->hypothesis_id = $hypothesis_id;
    }

    /**
     * @return int
     */
    public function getCountRespond()
    {
        return $this->count_respond;
    }

    /**
     * @param int $count_respond
     */
    public function setCountRespond($count_respond)
    {
        $this->count_respond = $count_respond;
    }

    /**
     * @return int
     */
    public function getCountPositive()
    {
        return $this->count_positive;
    }

    /**
     * @param int $count_positive
     */
    public function setCountPositive($count_positive)
    {
        $this->count_positive = $count_positive;
    }

    /**
     * @return int
     */
    public function getAddCountRespond()
    {
        return $this->add_count_respond;
    }

    /**
     * @param int $add_count_respond
     */
    public function setAddCountRespond($add_count_respond)
    {
        $this->add_count_respond = $add_count_respond;
    }

    /**
     * @return CreatorRespondsFromAgentsOnConfirmFirstStep
     */
    public function getCreatorResponds()
    {
        return $this->_creatorResponds;
    }

    /**
     *
     */
    public function setCreatorResponds()
    {
        $this->_creatorResponds = new CreatorRespondsFromAgentsOnConfirmFirstStep();
    }

    /**
     * @return CreatorNewRespondsOnConfirmFirstStep
     */
    public function getCreatorNewResponds()
    {
        return $this->_creatorNewResponds;
    }

    /**
     *
     */
    public function setCreatorNewResponds()
    {
        $this->_creatorNewResponds = new CreatorNewRespondsOnConfirmFirstStep();
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
     * @param string $cachePath
     */
    public function setCachePathForm($cachePath)
    {
        $this->cachePath = $cachePath;
    }

    /**
     * @return string
     */
    public function getCachePathForm()
    {
        return $this->cachePath;
    }
}