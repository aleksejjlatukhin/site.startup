<?php


namespace app\models;

use app\models\forms\FormCreateConfirm;
use app\models\interfaces\ConfirmationInterface;
use app\models\interfaces\CreateRespondsOnConfirmFirstStepInterface;
use yii\base\Model;

class CreatorNewRespondsOnConfirmFirstStep extends Model implements CreateRespondsOnConfirmFirstStepInterface
{

    /**
     * Создание новых респондентов по заданному кол-ву на первом шаге подтверждения
     * @param ConfirmationInterface $confirm
     * @param FormCreateConfirm $form
     */
    public function create(ConfirmationInterface $confirm, FormCreateConfirm $form)
    {
        if ($confirm->stage == StageConfirm::STAGE_CONFIRM_SEGMENT) {
            for ($i = 1; $i <= $form->count_respond; $i++) {
                $newRespond[$i] = self::getCreateModel($confirm);
                $newRespond[$i]->setConfirmId($confirm->id);
                $newRespond[$i]->setName('Респондент ' . $i);
                $newRespond[$i]->save();
            }
        } else {
            for ($i = ++$form->count_respond; $i < array_sum([$form->count_respond, $form->add_count_respond]); $i++ ) {
                $newRespond[$i] = self::getCreateModel($confirm);
                $newRespond[$i]->setConfirmId($confirm->id);
                $newRespond[$i]->setName('Респондент ' . $i);
                $newRespond[$i]->save();
            }
        }
    }


    /**
     * @param ConfirmationInterface $confirm
     * @return RespondsProblem|RespondsGcp|RespondsMvp|RespondsSegment|bool
     */
    private static function getCreateModel(ConfirmationInterface $confirm)
    {
        if ($confirm->stage == StageConfirm::STAGE_CONFIRM_SEGMENT) {
            return new RespondsSegment();
        } elseif($confirm->stage == StageConfirm::STAGE_CONFIRM_PROBLEM) {
            return new RespondsProblem();
        }elseif($confirm->stage == StageConfirm::STAGE_CONFIRM_GCP) {
            return new RespondsGcp();
        }elseif($confirm->stage == StageConfirm::STAGE_CONFIRM_MVP) {
            return new RespondsMvp();
        }
        return false;
    }
}