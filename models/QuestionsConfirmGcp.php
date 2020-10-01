<?php


namespace app\models;

use yii\db\ActiveRecord;

class QuestionsConfirmGcp extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'questions_confirm_gcp';
    }

    public function getConfirmProblem ()
    {
        return $this->hasOne(ConfirmGcp::class, ['id' => 'confirm_gcp_id']);
    }

    public function getAnswer()
    {
        return $this->hasOne(AnswersQuestionsConfirmGcp::class, ['question_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['confirm_gcp_id', 'title'], 'required'],
            [['confirm_gcp_id'], 'integer'],
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
            'title' => 'Описание вопроса',
            'status' => 'Status',
        ];
    }
}