<?php


namespace app\models\forms;

use yii\base\Model;
use app\models\ConfirmGcp;
use yii\web\NotFoundHttpException;

class FormUpdateConfirmGcp extends Model
{
    public $id;
    public $gcp_id;
    public $count_respond;
    public $count_positive;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gcp_id', 'count_respond', 'count_positive'], 'required'],
            [['gcp_id'], 'integer'],
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
            'count_respond' => 'Количество респондентов, подтвердивших проблему',
            'count_positive' => 'Необходимое количество респондентов, подтверждающих ценностное предложение',
        ];
    }

    /**
     * FormUpdateConfirmGcp constructor.
     * @param $id
     * @param array $config
     */
    public function __construct($id, $config = [])
    {
        $confirm_gcp = ConfirmGcp::findOne($id);

        $this->id = $id;
        $this->gcp_id = $confirm_gcp->gcp_id;
        $this->count_respond = $confirm_gcp->count_respond;
        $this->count_positive = $confirm_gcp->count_positive;

        parent::__construct($config);
    }

    /**
     * @return ConfirmGcp|bool|null
     * @throws NotFoundHttpException
     */
    public function update()
    {
        if ($this->validate()) {

            $confirm_gcp = ConfirmGcp::findOne($this->id);
            $confirm_gcp->count_respond = $this->count_respond;
            $confirm_gcp->count_positive = $this->count_positive;

            if ($confirm_gcp->save()) return $confirm_gcp;
            throw new NotFoundHttpException('Ошибка. Неудалось сохранить изменения');
        }
        return false;
    }
}