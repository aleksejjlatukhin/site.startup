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
        switch (get_class($confirm)) {
            case 'app\models\Interview':
                // На данном этапе не возможно выполнить запрос
                break;
            case 'app\models\ConfirmProblem':
                foreach ($confirm->problem->respondsAgents as $respond) {
                    $respondConfirm = new RespondsConfirm();
                    $respondConfirm->setConfirmId($confirm->id);
                    $respondConfirm->setName($respond->name);
                    $respondConfirm->setParams([
                        'info_respond' => $respond->info_respond,
                        'place_interview' =>$respond->place_interview,
                        'email' => $respond->email]);
                    $respondConfirm->save();
                }
                break;
            case 'app\models\ConfirmGcp':
                foreach ($confirm->gcp->respondsAgents as $respond) {
                    $respondConfirm = new RespondsGcp();
                    $respondConfirm->setConfirmId($confirm->id);
                    $respondConfirm->setName($respond->name);
                    $respondConfirm->setParams([
                        'info_respond' => $respond->info_respond,
                        'place_interview' =>$respond->place_interview,
                        'email' => $respond->email]);
                    $respondConfirm->save();
                }
                break;
            case 'app\models\ConfirmMvp':
                foreach ($confirm->mvp->respondsAgents as $respond) {
                    $respondConfirm = new RespondsMvp();
                    $respondConfirm->setConfirmId($confirm->id);
                    $respondConfirm->setName($respond->name);
                    $respondConfirm->setParams([
                        'info_respond' => $respond->info_respond,
                        'place_interview' =>$respond->place_interview,
                        'email' => $respond->email]);
                    $respondConfirm->save();
                }
                break;
        }
    }
}