<?php


namespace app\models\forms;

use yii\base\Model;
use app\models\ConfirmProblem;
use yii\web\NotFoundHttpException;

class FormUpdateConfirmProblem extends Model
{

    public $id;
    public $gps_id;
    public $count_respond;
    public $count_positive;
    public $need_consumer;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gps_id'], 'integer'],
            ['need_consumer', 'trim'],
            [['need_consumer'], 'string', 'max' => 255],
            [['count_respond', 'count_positive'], 'integer', 'integerOnly' => TRUE, 'min' => '1'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gps_id' => 'Gps ID',
            'count_respond' => 'Количество респондентов',
            'count_positive' => 'Необходимое количество позитивных ответов',
            'need_consumer' => 'Потребность потребителя',
        ];
    }

    /**
     * FormUpdateConfirmProblem constructor.
     * @param $id
     * @param array $config
     */
    public function __construct($id, $config = [])
    {
        $confirm_problem = ConfirmProblem::findOne($id);

        $this->id = $id;
        $this->gps_id = $confirm_problem->gps_id;
        $this->count_respond = $confirm_problem->count_respond;
        $this->count_positive = $confirm_problem->count_positive;
        $this->need_consumer = $confirm_problem->need_consumer;

        parent::__construct($config);
    }

    /**
     * @return ConfirmProblem|bool|null
     * @throws NotFoundHttpException
     */
    public function update()
    {
        if ($this->validate()) {

            $confirm_problem = ConfirmProblem::findOne($this->id);
            $confirm_problem->count_respond = $this->count_respond;
            $confirm_problem->count_positive = $this->count_positive;
            $confirm_problem->need_consumer = $this->need_consumer;

            if ($confirm_problem->save()) return $confirm_problem;
            throw new NotFoundHttpException('Ошибка. Неудалось сохранить изменения');
        }
        return false;
    }

}