<?php


namespace app\models;

use yii\db\ActiveRecord;

class QuestionsConfirmMvp extends ActiveRecord
{

    public $list_questions;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'questions_confirm_mvp';
    }

    public function getConfirm ()
    {
        return $this->hasOne(ConfirmMvp::class, ['id' => 'confirm_mvp_id']);
    }

    public function getAnswer()
    {
        return $this->hasOne(AnswersQuestionsConfirmMvp::class, ['question_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['confirm_mvp_id', 'title'], 'required'],
            [['confirm_mvp_id'], 'integer'],
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
            'title' => 'Описание вопроса',
            'status' => 'Status',
        ];
    }

}