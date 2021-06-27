<?php


namespace app\models;

use app\models\interfaces\ConfirmationInterface;

class ManagerForAnswersAtQuestion
{

    /**
     * Создание пустого ответа для нового вопроса для каждого респондента
     * @param ConfirmationInterface $confirm
     * @param $question_id
     */
    public function create(ConfirmationInterface $confirm, $question_id)
    {
        foreach ($confirm->responds as $respond) {
            $answer = self::getModel($confirm);
            $answer->question_id = $question_id;
            $answer->respond_id = $respond->id;
            $answer->save();
        }
    }


    /**
     * @param $confirm
     * @return AnswersQuestionsConfirmGcp|AnswersQuestionsConfirmMvp|AnswersQuestionsConfirmProblem|AnswersQuestionsConfirmSegment|bool
     */
    private static function getModel($confirm)
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


    /**
     * @param ConfirmationInterface $confirm
     * @param $question_id
     */
    public function delete(ConfirmationInterface $confirm, $question_id)
    {
        $class = self::getClassAnswer($confirm);
        foreach ($confirm->responds as $respond) {
            $answer = $class::find()->where(['question_id' => $question_id, 'respond_id' => $respond->id])->one();
            $answer->delete();
        }
    }


    /**
     * @param $confirm
     * @return bool|string
     */
    private static function getClassAnswer($confirm)
    {
        if ($confirm->stage == StageConfirm::STAGE_CONFIRM_SEGMENT) {
            return AnswersQuestionsConfirmSegment::class;
        } elseif($confirm->stage == StageConfirm::STAGE_CONFIRM_PROBLEM) {
            return AnswersQuestionsConfirmProblem::class;
        }elseif($confirm->stage == StageConfirm::STAGE_CONFIRM_GCP) {
            return AnswersQuestionsConfirmGcp::class;
        }elseif($confirm->stage == StageConfirm::STAGE_CONFIRM_MVP) {
            return AnswersQuestionsConfirmMvp::class;
        }
        return false;
    }
}