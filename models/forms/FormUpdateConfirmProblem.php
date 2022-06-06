<?php


namespace app\models\forms;

use app\models\ConfirmProblem;
use yii\web\NotFoundHttpException;

/**
 * Форма обновления подтверждения гипотезы проблемы
 *
 * Class FormUpdateConfirmProblem
 * @package app\models\forms
 *
 * @property string $need_consumer                     Потребность потребителя
 */
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
        $this->setEditorCountRespond();

        $this->setParams([
            'id' => $confirmId,
            'count_respond' => $confirm->getCountRespond(),
            'count_positive' => $confirm->getCountPositive(),
            'need_consumer' => $confirm->getNeedConsumer(),
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
        $this->setNeedConsumer($params['need_consumer']);
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
     * @return ConfirmProblem|bool|mixed|null
     * @throws NotFoundHttpException
     */
    public function update()
    {
        if ($this->validate()) {

            $confirm = ConfirmProblem::findOne($this->getId());
            $confirm->setCountRespond($this->getCountRespond());
            $confirm->setCountPositive($this->getCountPositive());
            $confirm->setNeedConsumer($this->getNeedConsumer());

            if ($confirm->save()){
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
    public function getNeedConsumer()
    {
        return $this->need_consumer;
    }

    /**
     * @param string $need_consumer
     */
    public function setNeedConsumer($need_consumer)
    {
        $this->need_consumer = $need_consumer;
    }

}