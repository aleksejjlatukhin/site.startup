<?php


namespace app\models\forms;

use app\models\EditorCountResponds;
use app\models\ConfirmSegment;
use Throwable;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

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
        $this->_editorCountRespond = new EditorCountResponds();

        $this->setParams([
            'id' => $confirmId,
            'count_respond' => $confirm->count_respond,
            'count_positive' => $confirm->count_positive,
            'greeting_interview' => $confirm->greeting_interview,
            'view_interview' => $confirm->view_interview,
            'reason_interview' => $confirm->reason_interview
        ]);

        parent::__construct($config);
    }


    /**
     * @param array $params
     * @return mixed|void
     */
    protected function setParams(array $params)
    {
        $this->id = $params['id'];
        $this->count_respond = $params['count_respond'];
        $this->count_positive = $params['count_positive'];
        $this->greeting_interview = $params['greeting_interview'];
        $this->view_interview = $params['view_interview'];
        $this->reason_interview = $params['reason_interview'];
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
     * @return ConfirmSegment|bool|null
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function update()
    {
        if ($this->validate()) {

            $confirm = ConfirmSegment::findOne($this->id);
            $confirm->setCountRespond($this->count_respond);
            $confirm->setCountPositive($this->count_positive);
            $confirm->setParams([
                'greeting_interview' => $this->greeting_interview,
                'view_interview' => $this->view_interview,
                'reason_interview' => $this->reason_interview
            ]);

            if ($confirm->save()) {
                $this->_editorCountRespond->edit($confirm);
                return $confirm;
            }
            throw new NotFoundHttpException('Ошибка. Неудалось сохранить изменения');
        }
        return false;
    }

}