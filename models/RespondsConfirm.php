<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "responds_confirm".
 *
 * @property string $id
 * @property int $confirm_problem_id
 * @property string $name
 * @property string $info_respond
 * @property string $date_plan
 * @property string $place_interview
 */
class RespondsConfirm extends \yii\db\ActiveRecord
{
    public $exist_respond;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'responds_confirm';
    }

    public function getDescInterview()
    {
        return $this->hasOne(DescInterviewConfirm::class, ['responds_confirm_id' => 'id']);
    }

    public function getConfirm()
    {
        return $this->hasOne(ConfirmProblem::class, ['id' => 'confirm_problem_id']);
    }

    public function getAnswers()
    {
        return $this->hasMany(AnswersQuestionsConfirmProblem::class, ['respond_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['confirm_problem_id', 'name'], 'required'],
            [['confirm_problem_id'], 'integer'],
            [['name', 'info_respond', 'email'], 'trim'],
            [['name', 'info_respond', 'email'], 'string', 'max' => 255],
            ['email', 'email', 'message' => 'Неверный формат адреса электронной почты'],
            ['exist_respond', 'boolean'],
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
            'name' => 'Респондент',
            'info_respond' => 'Данные респондента',
            'email' => 'Адрес электронной почты',
        ];
    }


    public function addAnswersForNewRespond()
    {
        $questions = QuestionsConfirmProblem::find()->where(['confirm_problem_id' => $this->confirm_problem_id])->all();

        foreach ($questions as $question){

            $answer = new AnswersQuestionsConfirmProblem();
            $answer->question_id = $question->id;
            $answer->respond_id = $this->id;
            $answer->save();
        }
    }


    public function listQuestions()
    {
        $questions = QuestionsConfirmProblem::find()->where(['confirm_problem_id' => $this->confirm_problem_id])->all();

        $answers = $this->answers;

        $listQuestions = '';

        foreach ($questions as $i => $question){

            $listQuestions .= '<p>' . ($i+1) . '. ' . $question->title . '</p><p style="padding-left: 15px;">' . $answers[$i]->answer . '</p>';

            if (($i+1) != count($questions)){
                $listQuestions .= '<hr>';
            }
        }

        return $listQuestions;
    }
}
