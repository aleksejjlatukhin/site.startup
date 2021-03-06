<?php


namespace app\models;

use app\models\forms\FormCreateConfirm;
use app\models\interfaces\ConfirmationInterface;
use app\models\interfaces\CreateRespondsOnConfirmFirstStepInterface;
use yii\base\Model;

class CreatorRespondsFromAgentsOnConfirmFirstStep extends Model implements CreateRespondsOnConfirmFirstStepInterface
{

    /**
     * Создание новых респондентов из представителей на первом шаге подтверждения
     * @param ConfirmationInterface $confirm
     * @param FormCreateConfirm $form
     */
    public function create(ConfirmationInterface $confirm, FormCreateConfirm $form)
    {
        foreach ($confirm->hypothesis->respondsAgents as $respond) {
            $respondConfirm = self::getCreateModel($confirm);
            $respondConfirm->setConfirmId($confirm->id);
            $respondConfirm->setName($respond->name);
            $respondConfirm->setParams([
                'info_respond' => $respond->info_respond,
                'place_interview' =>$respond->place_interview,
                'email' => $respond->email]);
            $respondConfirm->save();
        }
    }


    /**
     * @param ConfirmationInterface $confirm
     * @return RespondsProblem|RespondsGcp|RespondsMvp|bool
     */
    private static function getCreateModel(ConfirmationInterface $confirm)
    {
        if($confirm->stage == StageConfirm::STAGE_CONFIRM_PROBLEM) {
            return new RespondsProblem();
        }elseif($confirm->stage == StageConfirm::STAGE_CONFIRM_GCP) {
            return new RespondsGcp();
        }elseif($confirm->stage == StageConfirm::STAGE_CONFIRM_MVP) {
            return new RespondsMvp();
        }
        return false;
    }
}