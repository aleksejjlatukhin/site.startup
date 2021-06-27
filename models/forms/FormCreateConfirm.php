<?php


namespace app\models\forms;

use yii\base\Model;

abstract class FormCreateConfirm extends Model
{

    public $hypothesis_id;
    public $count_respond;
    public $count_positive;
    public $add_count_respond;
    protected $_creatorResponds;
    protected $_creatorNewResponds;
    public $_cacheManager;
    public $cachePath;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['count_respond', 'count_positive'], 'required'],
            [['count_respond', 'count_positive'], 'integer', 'integerOnly' => TRUE, 'min' => '1'],
            [['count_respond', 'count_positive'], 'integer', 'integerOnly' => TRUE, 'max' => '100'],
        ];
    }


    abstract public function create ();
}