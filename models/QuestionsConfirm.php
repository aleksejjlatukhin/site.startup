<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "questions_confirm".
 *
 * @property string $id
 * @property int $confirm_problem_id
 * @property string $title
 * @property string $status
 */
class QuestionsConfirm extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'questions_confirm';
    }

    public function getProblem()
    {
        return $this->hasOne(ConfirmProblem::class, ['id' => 'confirm_problem_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['confirm_problem_id'], 'required'],
            [['confirm_problem_id'], 'integer'],
            [['status'], 'boolean'],
            [['status'], 'default', 'value' => '1'],
            [['title'], 'string', 'max' => 255],
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
            'confirm_problem_id' => 'Confirm Problem ID',
            'title' => 'Title',
            'status' => 'Status',
        ];
    }
}
