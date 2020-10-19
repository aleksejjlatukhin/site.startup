<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "questions".
 *
 * @property string $id
 * @property int $interview_id
 * @property string $title
 * @property string $status
 */
class Questions extends \yii\db\ActiveRecord
{

    public $list_questions;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'questions';
    }

    public function getInterview()
    {
        return $this->hasOne(Interview::class, ['id' => 'interview_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['interview_id', 'title'], 'required'],
            [['interview_id'], 'integer'],
            [['status'], 'boolean'],
            [['status'], 'default', 'value' => '1'],
            [['title', 'list_questions'], 'string', 'max' => 255],
            [['title'], 'trim'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'interview_id' => 'Interview ID',
            'title' => 'Описание вопроса',
            'status' => 'Status',
        ];
    }
}
