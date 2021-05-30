<?php

namespace app\models\forms;

use yii\base\Model;

abstract class FormUpdateConfirm extends Model
{

    public $id;
    public $count_respond;
    public $count_positive;
    public $_editorCountRespond;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['count_respond', 'count_positive'], 'integer', 'integerOnly' => TRUE, 'min' => '1'],
            [['count_respond', 'count_positive'], 'integer', 'integerOnly' => TRUE, 'max' => '100'],
        ];
    }


    /**
     * @return mixed
     */
    abstract public function update ();


    /**
     * @param array $params
     * @return mixed
     */
    abstract protected function setParams(array $params);
}