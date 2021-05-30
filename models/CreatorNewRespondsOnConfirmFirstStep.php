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
        switch (get_class($confirm)) {
            case 'app\models\Interview':
                for ($i = 1; $i <= $form->count_respond; $i++ ) {
                    $newRespond[$i] = new Respond();
                    $newRespond[$i]->setConfirmId($confirm->id);
                    $newRespond[$i]->setName('Респондент ' . $i);
                    $newRespond[$i]->save();
                }
                break;
            case 'app\models\ConfirmProblem':
                for ($i = ++$form->count_respond; $i < array_sum([$form->count_respond, $form->add_count_respond]); $i++ ) {
                    $newRespond[$i] = new RespondsConfirm();
                    $newRespond[$i]->setConfirmId($confirm->id);
                    $newRespond[$i]->setName('Респондент ' . $i);
                    $newRespond[$i]->save();
                }
                break;
            case 'app\models\ConfirmGcp':
                for ($i = ++$form->count_respond; $i < array_sum([$form->count_respond, $form->add_count_respond]); $i++ ) {
                    $newRespond[$i] = new RespondsGcp();
                    $newRespond[$i]->setConfirmId($confirm->id);
                    $newRespond[$i]->setName('Респондент ' . $i);
                    $newRespond[$i]->save();
                }
                break;
            case 'app\models\ConfirmMvp':
                for ($i = ++$form->count_respond; $i < array_sum([$form->count_respond, $form->add_count_respond]); $i++ ) {
                    $newRespond[$i] = new RespondsMvp();
                    $newRespond[$i]->setConfirmId($confirm->id);
                    $newRespond[$i]->setName('Респондент ' . $i);
                    $newRespond[$i]->save();
                }
                break;
        }
    }
}