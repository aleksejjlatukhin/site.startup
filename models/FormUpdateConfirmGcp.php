<?php


namespace app\models;

use yii\base\Model;

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
            [['gcp_id'], 'integer'],
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
        ];
    }

    public function __construct($id, $config = [])
    {
        $confirm_gcp = ConfirmGcp::findOne($id);

        $this->id = $id;
        $this->gcp_id = $confirm_gcp->gcp_id;
        $this->count_respond = $confirm_gcp->count_respond;
        $this->count_positive = $confirm_gcp->count_positive;

        parent::__construct($config);
    }

    public function update()
    {

        if ($this->validate()) {

            $confirm_gcp = ConfirmGcp::findOne($this->id);
            $confirm_gcp->count_respond = $this->count_respond;
            $confirm_gcp->count_positive = $this->count_positive;

            return $confirm_gcp->save() ? $confirm_gcp : null;
        }
        return false;
    }
}