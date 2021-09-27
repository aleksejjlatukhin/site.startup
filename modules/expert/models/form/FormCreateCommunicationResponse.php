<?php


namespace app\modules\expert\models\form;


use app\models\CommunicationResponse;
use yii\base\Model;
use yii\web\NotFoundHttpException;


/**
 * Класс формы создания ответа на коммуникацию
 *
 * FormCreateCommunicationResponse
 * @package app\modules\expert\models\form
 */
class FormCreateCommunicationResponse extends Model
{

    public $communication_id;
    public $answer;
    public $comment;
    public $_model;


    /**
     * FormCreateCommunicationResponse constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->_model = new CommunicationResponse();
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['answer', 'communication_id'], 'integer'],
            [['answer', 'communication_id'], 'required'],
            [['comment'], 'string', 'max' => 255],
            ['answer', 'in', 'range' => [
                CommunicationResponse::POSITIVE_RESPONSE,
                CommunicationResponse::NEGATIVE_RESPONSE
            ]],
        ];
    }


    public function attributeLabels()
    {
        return [
            'answer' => 'Дайте ответ на вопрос',
            'comment' => 'Напишите комментарий'
        ];
    }


    /**
     * Получить массив
     * значений для ответа
     *
     * @return array
     */
    public static function getAnswers()
    {
        return [
            CommunicationResponse::POSITIVE_RESPONSE => 'Готов(-а) провести экспертизу проекта',
            CommunicationResponse::NEGATIVE_RESPONSE => 'Не готов(-а) провести экспертизу проекта'
        ];
    }


    /**
     * Установить параметр
     * communication_id
     *
     * @param int $id
     */
    public function setCommunicationId($id)
    {
        $this->communication_id = $id;
    }


    /**
     * Сохранение формы
     *
     * @return bool
     * @throws NotFoundHttpException
     */
    public function create()
    {
        $this->_model->setParams($this->answer, $this->comment, $this->communication_id);
        if ($this->_model->save()) return true;
        throw new NotFoundHttpException('Неудалось сохранить сегмент');
    }
}