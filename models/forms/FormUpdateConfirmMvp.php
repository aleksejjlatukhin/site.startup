<?php


namespace app\models\forms;

use yii\base\Model;
use app\models\ConfirmMvp;
use yii\web\NotFoundHttpException;

class FormUpdateConfirmMvp extends Model
{
    public $id;
    public $mvp_id;
    public $count_respond;
    public $count_positive;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mvp_id'], 'integer'],
            [['count_respond', 'count_positive'], 'integer', 'integerOnly' => TRUE, 'min' => '1'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'count_respond' => 'Количество респондентов',
            'count_positive' => 'Необходимое количество позитивных ответов',
        ];
    }

    /**
     * FormUpdateConfirmMvp constructor.
     * @param $id
     * @param array $config
     */
    public function __construct($id, $config = [])
    {
        $confirm_mvp = ConfirmMvp::findOne($id);

        $this->id = $id;
        $this->mvp_id = $confirm_mvp->mvp_id;
        $this->count_respond = $confirm_mvp->count_respond;
        $this->count_positive = $confirm_mvp->count_positive;

        parent::__construct($config);
    }

    /**
     * @return ConfirmMvp|bool|null
     * @throws NotFoundHttpException
     */
    public function update()
    {
        if ($this->validate()) {

            $confirm_mvp = ConfirmMvp::findOne($this->id);
            $confirm_mvp->count_respond = $this->count_respond;
            $confirm_mvp->count_positive = $this->count_positive;

            if ($confirm_mvp->save()) return $confirm_mvp;
            throw new NotFoundHttpException('Ошибка. Неудалось сохранить изменения');
        }
        return false;
    }
}