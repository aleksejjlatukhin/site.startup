<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "responds_gcp".
 *
 * @property string $id
 * @property int $confirm_gcp_id
 * @property string $name
 * @property string $info_respond
 * @property string $date_plan
 * @property string $place_interview
 */
class RespondsGcp extends \yii\db\ActiveRecord
{

    const LIMIT_COUNT = 100;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'responds_gcp';
    }


    public function getDescInterview()
    {
        return $this->hasOne(DescInterviewGcp::class, ['responds_gcp_id' => 'id']);
    }

    public function getConfirm()
    {
        return $this->hasOne(ConfirmGcp::class, ['id' => 'confirm_gcp_id']);
    }

    public function getAnswers()
    {
        return $this->hasMany(AnswersQuestionsConfirmGcp::class, ['respond_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['confirm_gcp_id', 'name',], 'required'],
            [['confirm_gcp_id'], 'integer'],
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
            'confirm_gcp_id' => 'Confirm Gcp ID',
            'name' => 'Респондент',
            'info_respond' => 'Данные респондента',
            'email' => 'Адрес электронной почты',
        ];
    }


    public function addAnswersForNewRespond()
    {
        $questions = QuestionsConfirmGcp::find()->where(['confirm_gcp_id' => $this->confirm_gcp_id])->all();

        foreach ($questions as $question){

            $answer = new AnswersQuestionsConfirmGcp();
            $answer->question_id = $question->id;
            $answer->respond_id = $this->id;
            $answer->save();
        }
    }

}
