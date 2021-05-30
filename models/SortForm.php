<?php


namespace app\models;

use yii\base\Model;

class SortForm extends Model
{

    public $field;
    public $type;
    public $limit;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['field', 'type'], 'trim'],
            ['limit', 'integer'],
        ];
    }
}