<?php


namespace app\models;


use app\models\interfaces\CommunicationsInterface;
use yii\helpers\Html;

class PatternsDescriptionDuplicateCommunication
{

    private $description;


    /**
     * @param CommunicationsInterface $source
     * @param User $adressee
     * @return string
     */
    public static function getValue($source, $adressee)
    {
        $model = new self();

        if (is_a($source, ProjectCommunications::class)) {

            return $model->getDescriptionDuplicateProjectCommunication($source, $adressee);
        }
        return 'Извините! Произошла ошибка формирования уведомления, пожалуйста, сообщите об этом в техподдержку.';
    }


    /**
     * @param ProjectCommunications $source
     * @param User $adressee
     * @return string
     */
    private function getDescriptionDuplicateProjectCommunication($source, $adressee)
    {

        if ($source->type == CommunicationTypes::MAIN_ADMIN_APPOINTS_EXPERT_PROJECT) {

            if (User::isUserSimple($adressee->username)) {

                $this->description = 'На ваш проект «' . $source->project->project_name.'» назначен эксперт ' . $source->expert->second_name
                    . ' ' . $source->expert->first_name . ' ' . $source->expert->middle_name . '. В сообщениях создана беседа с экспертом.';

            } elseif (User::isUserAdmin($adressee->username)) {

                $this->description = 'На проект «' . Html::a($source->project->project_name, ['/projects/index', 'id' => $source->project->user_id])
                    . '» (проектант: ' . Html::a($source->project->user->second_name . ' ' . $source->project->user->first_name . ' ' .
                    $source->project->user->middle_name, ['/profile/index', 'id' => $source->project->user->id]) . ') назначен эксперт ' .
                    $source->expert->second_name . ' ' . $source->expert->first_name . ' ' . $source->expert->middle_name . '.';
            }

        } elseif ($source->type == CommunicationTypes::MAIN_ADMIN_WITHDRAWS_EXPERT_FROM_PROJECT) {

            if (User::isUserSimple($adressee->username)) {

                $this->description = 'С вашего проекта «' . $source->project->project_name.'» отозван эксперт ' . $source->expert->second_name
                    . ' ' . $source->expert->first_name . ' ' . $source->expert->middle_name . '.';

            } elseif (User::isUserAdmin($adressee->username)) {

                $this->description = 'С проекта «' . Html::a($source->project->project_name, ['/projects/index', 'id' => $source->project->user_id])
                    . '» (проектант: ' . Html::a($source->project->user->second_name . ' ' . $source->project->user->first_name . ' ' .
                        $source->project->user->middle_name, ['/profile/index', 'id' => $source->project->user->id]) . ') отозван эксперт ' .
                    $source->expert->second_name . ' ' . $source->expert->first_name . ' ' . $source->expert->middle_name . '.';
            }
        }
        return $this->description;
    }
}