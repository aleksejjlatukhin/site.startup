<?php


namespace app\models\forms;

use app\models\ConfirmMvp;
use yii\base\Model;

class FormCreateConfirmMvp extends Model
{

    public $mvp_id;
    public $count_respond;
    public $count_positive;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mvp_id', 'count_respond', 'count_positive'], 'required'],
            [['mvp_id'], 'integer'],
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
            'count_respond' => 'Количество респондентов, подтвердивших ценностное предложение',
            'count_positive' => 'Необходимое количество респондентов, подтверждающих продукт (MVP)',
        ];
    }


    public function create()
    {
        $model = new ConfirmMvp();
        $model->mvp_id = $this->mvp_id;
        $model->count_respond = $this->count_respond;
        $model->count_positive = $this->count_positive;

        return $model->save() ? $model : null;
    }

}