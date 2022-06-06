<?php


namespace app\models;

use app\models\interfaces\ConfirmationInterface;

/**
 * Класс добавляет вопросы в таблицы, которые содержат все вопросы добавляемые на этапах подтверждения гипотез
 *
 * Class CreatorQuestionToGeneralList
 * @package app\models
 */
class CreatorQuestionToGeneralList
{

    /**
     * @param ConfirmationInterface $confirm
     * @param $title
     */
    public function create(ConfirmationInterface $confirm, $title)
    {
        $user = $confirm->hypothesis->project->user;
        $class = self::getClassQuestion($confirm);
        $baseQuestions = $class::find()->where(['user_id' => $user->id])->select('title')->all();
        $existQuestions = 0;

        foreach ($baseQuestions as $baseQuestion){
            if ($baseQuestion->title == $title){
                $existQuestions++;
            }
        }

        if ($existQuestions == 0){
            $general_question = self::getModel($confirm);
            $general_question->title = $title;
            $general_question->user_id = $user->id;
            $general_question->save();
        }
    }


    /**
     * @param $confirm
     * @return bool|string
     */
    private static function getClassQuestion($confirm)
    {
        if ($confirm->stage == StageConfirm::STAGE_CONFIRM_SEGMENT) {
            return AllQuestionsConfirmSegment::class;
        } elseif($confirm->stage == StageConfirm::STAGE_CONFIRM_PROBLEM) {
            return AllQuestionsConfirmProblem::class;
        }elseif($confirm->stage == StageConfirm::STAGE_CONFIRM_GCP) {
            return AllQuestionsConfirmGcp::class;
        }elseif($confirm->stage == StageConfirm::STAGE_CONFIRM_MVP) {
            return AllQuestionsConfirmMvp::class;
        }
        return false;
    }


    /**
     * @param $confirm
     * @return AllQuestionsConfirmGcp|AllQuestionsConfirmMvp|AllQuestionsConfirmProblem|AllQuestionsConfirmSegment|bool
     */
    private static function getModel($confirm)
    {
        if ($confirm->stage == StageConfirm::STAGE_CONFIRM_SEGMENT) {
            return new AllQuestionsConfirmSegment();
        } elseif($confirm->stage == StageConfirm::STAGE_CONFIRM_PROBLEM) {
            return new AllQuestionsConfirmProblem();
        }elseif($confirm->stage == StageConfirm::STAGE_CONFIRM_GCP) {
            return new AllQuestionsConfirmGcp();
        }elseif($confirm->stage == StageConfirm::STAGE_CONFIRM_MVP) {
            return new AllQuestionsConfirmMvp();
        }
        return false;
    }
}