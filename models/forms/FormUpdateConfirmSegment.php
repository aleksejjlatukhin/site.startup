<?php


namespace app\models\forms;

use app\models\ConfirmSegment;
use yii\web\NotFoundHttpException;

/**
 * Форма обновления подтверждения гипотезы сегмента
 *
 * Class FormUpdateConfirmSegment
 * @package app\models\forms
 *
 * @property string $greeting_interview                 Приветствие в начале встречи
 * @property string $view_interview                     Информация о вас для респондентов
 * @property string $reason_interview                   Причина и тема (что побудило) для проведения исследования
 */
class FormUpdateConfirmSegment extends FormUpdateConfirm
{

    public $greeting_interview;
    public $view_interview;
    public $reason_interview;


    /**
     * FormUpdateConfirmSegment constructor.
     * @param $confirmId
     * @param array $config
     */
    public function __construct($confirmId, $config = [])
    {
        $confirm = ConfirmSegment::findOne($confirmId);
        $this->setEditorCountRespond();

        $this->setParams([
            'id' => $confirmId,
            'count_respond' => $confirm->getCountRespond(),
            'count_positive' => $confirm->getCountPositive(),
            'greeting_interview' => $confirm->getGreetingInterview(),
            'view_interview' => $confirm->getViewInterview(),
            'reason_interview' => $confirm->getReasonInterview()
        ]);

        parent::__construct($config);
    }


    /**
     * @param array $params
     * @return mixed|void
     */
    protected function setParams(array $params)
    {
        $this->setId($params['id']);
        $this->setCountRespond($params['count_respond']);
        $this->setCountPositive($params['count_positive']);
        $this->setGreetingInterview($params['greeting_interview']);
        $this->setViewInterview($params['view_interview']);
        $this->setReasonInterview($params['reason_interview']);
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['greeting_interview', 'view_interview', 'reason_interview'], 'trim'],
            [['greeting_interview', 'view_interview', 'reason_interview'], 'string', 'max' => '2000'],
            [['count_respond', 'count_positive'], 'integer', 'integerOnly' => TRUE, 'min' => '1'],
            [['count_respond', 'count_positive'], 'integer', 'integerOnly' => TRUE, 'max' => '100'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'count_respond' => 'Количество респондентов',
            'count_positive' => 'Количество респондентов, соответствующих сегменту',
            'greeting_interview' => 'Приветствие в начале встречи',
            'view_interview' => 'Информация о вас для респондентов',
            'reason_interview' => 'Причина и тема (что побудило) для проведения исследования',
        ];
    }


    /**
     * @return ConfirmSegment|bool|mixed|null
     * @throws NotFoundHttpException
     */
    public function update()
    {
        if ($this->validate()) {

            $confirm = ConfirmSegment::findOne($this->getId());
            $confirm->setCountRespond($this->getCountRespond());
            $confirm->setCountPositive($this->getCountPositive());
            $confirm->setParams([
                'greeting_interview' => $this->getGreetingInterview(),
                'view_interview' => $this->getViewInterview(),
                'reason_interview' => $this->getReasonInterview()
            ]);

            if ($confirm->save()) {
                $this->getEditorCountRespond()->edit($confirm);
                return $confirm;
            }
            throw new NotFoundHttpException('Ошибка. Неудалось сохранить изменения');
        }
        return false;
    }

    /**
     * @return string
     */
    public function getGreetingInterview()
    {
        return $this->greeting_interview;
    }

    /**
     * @param string $greeting_interview
     */
    public function setGreetingInterview($greeting_interview)
    {
        $this->greeting_interview = $greeting_interview;
    }

    /**
     * @return string
     */
    public function getViewInterview()
    {
        return $this->view_interview;
    }

    /**
     * @param string $view_interview
     */
    public function setViewInterview($view_interview)
    {
        $this->view_interview = $view_interview;
    }

    /**
     * @return string
     */
    public function getReasonInterview()
    {
        return $this->reason_interview;
    }

    /**
     * @param string $reason_interview
     */
    public function setReasonInterview($reason_interview)
    {
        $this->reason_interview = $reason_interview;
    }

}