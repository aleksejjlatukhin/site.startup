<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "responds_mvp".
 *
 * @property string $id
 * @property int $confirm_mvp_id
 * @property string $name
 * @property string $info_respond
 * @property string $email
 */
class RespondsMvp extends \yii\db\ActiveRecord
{

    const LIMIT_COUNT = 100;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'responds_mvp';
    }

    public function getDescInterview()
    {
        return $this->hasOne(DescInterviewMvp::class, ['responds_mvp_id' => 'id']);
    }

    public function getConfirm()
    {
        return $this->hasOne(ConfirmMvp::class, ['id' => 'confirm_mvp_id']);
    }

    public function getAnswers()
    {
        return $this->hasMany(AnswersQuestionsConfirmMvp::class, ['respond_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['confirm_mvp_id', 'name'], 'required'],
            [['confirm_mvp_id'], 'integer'],
            [['name', 'info_respond', 'email'], 'trim'],
            [['name'], 'string', 'max' => 100],
            [['info_respond', 'email'], 'string', 'max' => 255],
            ['email', 'email', 'message' => 'Неверный формат адреса электронной почты'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'confirm_mvp_id' => 'Confirm Mvp ID',
            'name' => 'Респондент',
            'info_respond' => 'Данные респондента',
            'email' => 'Адрес электронной почты',
        ];
    }


    public function addAnswersForNewRespond()
    {
        $questions = QuestionsConfirmMvp::find()->where(['confirm_mvp_id' => $this->confirm_mvp_id])->all();

        foreach ($questions as $question){

            $answer = new AnswersQuestionsConfirmMvp();
            $answer->question_id = $question->id;
            $answer->respond_id = $this->id;
            $answer->save();
        }
    }
}
