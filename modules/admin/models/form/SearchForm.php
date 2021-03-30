<?php

namespace app\modules\admin\models\form;

use yii\base\Model;

class SearchForm extends Model
{

    public $search;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['search'], 'trim'],
        ];
    }
}