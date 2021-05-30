<?php

namespace app\models;

use app\models\interfaces\RespondsInterface;
use yii\base\Model;

class CreatorAnswersForNewRespond extends Model
{

    /**
     * Создание пустых ответов на вопросы для нового респондента
     * @param RespondsInterface $respond
     */
    public function create(RespondsInterface $respond)
    {

        switch (get_class($respond)) {

            case 'app\models\Respond':

                $questions = QuestionsConfirmSegment::find()->where(['interview_id' => $respond->confirmId])->all();
                foreach ($questions as $question){
                    $answer = new AnswersQuestionsConfirmSegment();
                    $answer->question_id = $question->id;
                    $answer->respond_id = $respond->id;
                    $answer->save();
                }
                break;

            case 'app\models\RespondsConfirm':

                $questions = QuestionsConfirmProblem::find()->where(['confirm_problem_id' => $respond->confirmId])->all();
                foreach ($questions as $question){
                    $answer = new AnswersQuestionsConfirmProblem();
                    $answer->question_id = $question->id;
                    $answer->respond_id = $respond->id;
                    $answer->save();
                }
                break;

            case 'app\models\RespondsGcp':

                $questions = QuestionsConfirmGcp::find()->where(['confirm_gcp_id' => $respond->confirmId])->all();
                foreach ($questions as $question){
                    $answer = new AnswersQuestionsConfirmGcp();
                    $answer->question_id = $question->id;
                    $answer->respond_id = $respond->id;
                    $answer->save();
                }
                break;

            case 'app\models\RespondsMvp':

                $questions = QuestionsConfirmMvp::find()->where(['confirm_mvp_id' => $respond->confirmId])->all();
                foreach ($questions as $question){
                    $answer = new AnswersQuestionsConfirmMvp();
                    $answer->question_id = $question->id;
                    $answer->respond_id = $respond->id;
                    $answer->save();
                }
                break;
        }
    }
}