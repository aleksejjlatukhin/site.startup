<?php


namespace app\models;


use app\models\interfaces\CommunicationsInterface;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;


/**
 * Класс коммуникаций, которые дублируют
 * другие коммуникации
 *
 * Class DuplicateCommunications
 * @package app\models
 */
class DuplicateCommunications extends ActiveRecord implements CommunicationsInterface
{

    const READ = 2468;
    const NO_READ = 3579;


    /**
     * @return string
     */
    public static function tableName()
    {
        return 'duplicate_communications';
    }


    /**
     * Получить объект оригинальной коммуникации
     *
     * @return ProjectCommunications|null
     */
    public function getSource()
    {
        if ($this->type == TypesDuplicateCommunication::PROJECT_COMMUNICATIONS) {

            return ProjectCommunications::findOne($this->source_id);
        }

        return null;
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['source_id', 'sender_id', 'adressee_id', 'type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['source_id', 'sender_id', 'adressee_id', 'type', 'description'], 'required'],
            ['description', 'string', 'max' => 1000],
            ['type', 'in', 'range' => TypesDuplicateCommunication::getTypes()],
            ['status', 'default', 'value' => self::NO_READ],
            ['status', 'in', 'range' => [
                self::READ,
                self::NO_READ
            ]],
        ];
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
     * Создание дублирующей коммуникации
     *
     * @param CommunicationsInterface $source
     * @param User $adressee
     * @return DuplicateCommunications|null
     */
    public static function create($source, $adressee)
    {
        $model = new self();

        if ($model->setParams($source, $adressee)) {

            return $model->save() ? $model : null;
        }
        return null;
    }


    /**
     * Установить параметры
     * дублирующей коммуникации
     *
     * @param CommunicationsInterface $source
     * @param User $adressee
     * @return bool
     */
    private function setParams($source, $adressee)
    {
        if (is_a($source, ProjectCommunications::class)) {

            $this->type = TypesDuplicateCommunication::PROJECT_COMMUNICATIONS;
            $this->source_id = $source->getId();
            $this->sender_id = $source->getSenderId();
            $this->adressee_id = $adressee->getId();
            $this->description = PatternsDescriptionDuplicateCommunication::getValue($source, $adressee);

            return true;
        }
        return false;
    }


    /**
     * Показывать ли кнопку
     * прочтения уведомления
     *
     * @return bool
     */
    public function isNeedReadButton()
    {
        if ($this->status == self::NO_READ) {
            return true;
        }
        return false;
    }


    /**
     * Установить параметр
     * прочтения коммуникации
     */
    public function setStatusRead()
    {
        $this->status = self::READ;
    }


    /**
     * Получить id получателя коммуникации
     *
     * @return int
     */
    public function getAdresseeId()
    {
        return $this->adressee_id;
    }


    /**
     * Получить id отправителя коммуникации
     *
     * @return int
     */
    public function getSenderId()
    {
        return $this->sender_id;
    }


    /**
     * Получить id коммуникации
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}