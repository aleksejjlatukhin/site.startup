<?php

namespace app\models;

use app\models\interfaces\ConfirmationInterface;
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
        $confirm = $respond->confirm;
        $questions = $confirm->questions;

        foreach ($questions as $question){
            $answer = self::getCreateModel($confirm);
            $answer->question_id = $question->id;
            $answer->respond_id = $respond->id;
            $answer->save();
        }
    }


    /**
     * @param ConfirmationInterface $confirm
     * @return AnswersQuestionsConfirmGcp|AnswersQuestionsConfirmMvp|AnswersQuestionsConfirmProblem|AnswersQuestionsConfirmSegment|bool
     */
    private static function getCreateModel(ConfirmationInterface $confirm)
    {
        if ($confirm->stage == StageConfirm::STAGE_CONFIRM_SEGMENT) {
            return new AnswersQuestionsConfirmSegment();
        } elseif($confirm->stage == StageConfirm::STAGE_CONFIRM_PROBLEM) {
            return new AnswersQuestionsConfirmProblem();
        }elseif($confirm->stage == StageConfirm::STAGE_CONFIRM_GCP) {
            return new AnswersQuestionsConfirmGcp();
        }elseif($confirm->stage == StageConfirm::STAGE_CONFIRM_MVP) {
            return new AnswersQuestionsConfirmMvp();
        }
        return false;
    }
}