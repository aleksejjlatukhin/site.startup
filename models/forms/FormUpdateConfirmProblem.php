<?php


namespace app\models\forms;

use app\models\ConfirmProblem;
use app\models\EditorCountResponds;
use Throwable;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

class FormUpdateConfirmProblem extends FormUpdateConfirm
{

    public $need_consumer;


    /**
     * FormUpdateConfirmProblem constructor.
     * @param $confirmId
     * @param array $config
     */
    public function __construct($confirmId, $config = [])
    {
        $confirm = ConfirmProblem::findOne($confirmId);
        $this->_editorCountRespond = new EditorCountResponds();

        $this->setParams([
            'id' => $confirmId,
            'count_respond' => $confirm->count_respond,
            'count_positive' => $confirm->count_positive,
            'need_consumer' => $confirm->need_consumer,
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
        $this->need_consumer = $params['need_consumer'];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['need_consumer', 'trim'],
            [['need_consumer'], 'string', 'max' => 255],
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
            'count_positive' => 'Количество респондентов, соответствующих проблеме',
            'need_consumer' => 'Потребность потребителя',
        ];
    }


    /**
     * @return ConfirmProblem|bool|null
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function update()
    {
        if ($this->validate()) {

            $confirm = ConfirmProblem::findOne($this->id);
            $confirm->setCountRespond($this->count_respond);
            $confirm->setCountPositive($this->count_positive);
            $confirm->setNeedConsumer($this->need_consumer);

            if ($confirm->save()){
                $this->_editorCountRespond->edit($confirm);
                return $confirm;
            }
            throw new NotFoundHttpException('Ошибка. Неудалось сохранить изменения');
        }
        return false;
    }

}