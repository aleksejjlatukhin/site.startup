<?php


namespace app\models;


use app\models\interfaces\CommunicationsInterface;
use app\models\interfaces\ConfirmationInterface;
use yii\helpers\Html;


/**
 * Класс, который определяет какой шаблон коммуникации DuplicateCommunication отправить
 *
 * Class PatternsDescriptionDuplicateCommunication
 * @package app\models
 *
 * @property string $description
 */
class PatternsDescriptionDuplicateCommunication
{

    /**
     * Описание шаблона коммуникации
     * @var string
     */
    private $description;


    /**
     * @param CommunicationsInterface $source
     * @param User $adressee
     * @param int $type
     * @param false|Expertise $expertise
     * @return string
     */
    public static function getValue($source, $adressee, $type, $expertise)
    {
        $model = new self();

        if (is_a($source, ProjectCommunications::class)) {
            /** @var ProjectCommunications $source */
            if ($type == TypesDuplicateCommunication::MAIN_ADMIN_TO_EXPERT) {
                return $model->getDescriptionDuplicateMainAdminToExpertCommunication($source, $adressee);
            } elseif (is_a($expertise, Expertise::class)) {
                if ($type == TypesDuplicateCommunication::EXPERT_COMPLETED_EXPERTISE) {
                    return $model->getDescriptionDuplicateExpertCompletedExpertiseCommunication($source, $adressee, $expertise);
                } elseif ($type == TypesDuplicateCommunication::EXPERT_UPDATE_DATA_COMPLETED_EXPERTISE) {
                    return $model->getDescriptionDuplicateExpertUpdateExpertiseCommunication($source, $adressee, $expertise);
                }
            }
        }
        return 'Извините! Произошла ошибка формирования уведомления, пожалуйста, сообщите об этом в техподдержку.';
    }


    /**
     * Получить описание шаблона коммуникации для трекера и проектанта,
     * когда гл.админ назначает или отзывает эксперта с проекта
     *
     * @param ProjectCommunications $source
     * @param User $adressee
     * @return string
     */
    private function getDescriptionDuplicateMainAdminToExpertCommunication($source, $adressee)
    {

        if ($source->type == CommunicationTypes::MAIN_ADMIN_APPOINTS_EXPERT_PROJECT) {

            if (User::isUserSimple($adressee->username)) {

                $this->description = 'На ваш проект «' . $source->project->project_name.'» назначен эксперт ' . $source->expert->second_name
                    . ' ' . $source->expert->first_name . ' ' . $source->expert->middle_name . '. Типы деятельности эксперта, по которым назначены экспертизы проекта: '
                    . ExpertType::getContent($source->findTypesAccessToExpertise()->types) . '. В сообщениях создана беседа с экспертом.';

            } elseif (User::isUserAdmin($adressee->username)) {

                $this->description = 'На проект «' . Html::a($source->project->project_name, ['/projects/index', 'id' => $source->project->user_id])
                    . '» (проектант: ' . Html::a($source->project->user->second_name . ' ' . $source->project->user->first_name . ' ' .
                    $source->project->user->middle_name, ['/profile/index', 'id' => $source->project->user->id]) . ') назначен эксперт ' .
                    $source->expert->second_name . ' ' . $source->expert->first_name . ' ' . $source->expert->middle_name . '. Типы деятельности эксперта, по которым назначены экспертизы проекта: '
                    . ExpertType::getContent($source->findTypesAccessToExpertise()->types) . '.';
            }

        } elseif ($source->type == CommunicationTypes::MAIN_ADMIN_WITHDRAWS_EXPERT_FROM_PROJECT) {

            if (User::isUserSimple($adressee->username)) {

                $this->description = 'С вашего проекта «' . $source->project->project_name .'» отозван эксперт ' . $source->expert->second_name
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


    /**
     * Описание уведомления для трекера и проектанта,
     * когда эксперт завершил экспертизу по этапу проекта
     *
     * @param ProjectCommunications $source
     * @param User $adressee
     * @param Expertise $expertise
     * @return string
     */
    private function getDescriptionDuplicateExpertCompletedExpertiseCommunication($source, $adressee, $expertise)
    {
        if (User::isUserSimple($adressee->username)) {

            if ($expertise->getStage() == StageExpertise::PROJECT) {
                $this->description = 'Эксперт, ' . $source->expert->second_name . ' ' . $source->expert->first_name . ' ' . $source->expert->middle_name . ', завершил экспертизу по этапу «' . $this->getStage($expertise->getStage())
                    . ': ' . Html::a($source->project->project_name, ['/projects/index', 'id' => $source->project->user_id]) . '». Тип деятельности эксперта: ' . ExpertType::getListTypes()[$expertise->getTypeExpert()] . '.';
            }else {
                $this->description = 'Эксперт, ' . $source->expert->second_name . ' ' . $source->expert->first_name . ' ' . $source->expert->middle_name . ', завершил экспертизу по этапу «' . $this->getStage($expertise->getStage())
                    . ': ' . $this->getLinkStage($expertise) . '». Проект: «' . Html::a($source->project->project_name, ['/projects/index', 'id' => $source->project->user_id]) . '». Тип деятельности эксперта: ' . ExpertType::getListTypes()[$expertise->getTypeExpert()] . '.';
            }

        } elseif (User::isUserAdmin($adressee->username)) {

            if ($expertise->getStage() == StageExpertise::PROJECT) {
                $this->description = 'Эксперт, ' . $source->expert->second_name . ' ' . $source->expert->first_name . ' ' . $source->expert->middle_name . ', завершил экспертизу по этапу «' . $this->getStage($expertise->getStage())
                    . ': ' . Html::a($source->project->project_name, ['/projects/index', 'id' => $source->project->user_id]) . '». Тип деятельности эксперта: ' . ExpertType::getListTypes()[$expertise->getTypeExpert()]
                    . '. Проектант: ' . Html::a($source->project->user->second_name . ' ' . $source->project->user->first_name . ' ' . $source->project->user->middle_name, ['/profile/index', 'id' => $source->project->user->id]) . '.';
            }else {
                $this->description = 'Эксперт, ' . $source->expert->second_name . ' ' . $source->expert->first_name . ' ' . $source->expert->middle_name . ', завершил экспертизу по этапу «' . $this->getStage($expertise->getStage())
                    . ': ' . $this->getLinkStage($expertise) . '». Проект: «' . Html::a($source->project->project_name, ['/projects/index', 'id' => $source->project->user_id]) . '». Тип деятельности эксперта: ' . ExpertType::getListTypes()[$expertise->getTypeExpert()]
                    . '. Проектант: ' . Html::a($source->project->user->second_name . ' ' . $source->project->user->first_name . ' ' . $source->project->user->middle_name, ['/profile/index', 'id' => $source->project->user->id]) . '.';
            }
        }
        return $this->description;
    }


    /**
     * Описание уведомления для трекера и проектанта,
     * когда эксперт обновил данные раннее завершенной экспертизы
     *
     * @param ProjectCommunications $source
     * @param User $adressee
     * @param Expertise $expertise
     * @return string
     */
    private function getDescriptionDuplicateExpertUpdateExpertiseCommunication($source, $adressee, $expertise)
    {
        if (User::isUserSimple($adressee->username)) {

            if ($expertise->getStage() == StageExpertise::PROJECT) {
                $this->description = 'Эксперт, ' . $source->expert->second_name . ' ' . $source->expert->first_name . ' ' . $source->expert->middle_name . ', обновил данные ранее завершенной экспертизы по этапу «' . $this->getStage($expertise->getStage())
                    . ': ' . Html::a($source->project->project_name, ['/projects/index', 'id' => $source->project->user_id]) . '». Тип деятельности эксперта: ' . ExpertType::getListTypes()[$expertise->getTypeExpert()] . '.';
            }else {
                $this->description = 'Эксперт, ' . $source->expert->second_name . ' ' . $source->expert->first_name . ' ' . $source->expert->middle_name . ', обновил данные ранее завершенной экспертизы по этапу «' . $this->getStage($expertise->getStage())
                    . ': ' . $this->getLinkStage($expertise) . '». Проект: «' . Html::a($source->project->project_name, ['/projects/index', 'id' => $source->project->user_id]) . '». Тип деятельности эксперта: ' . ExpertType::getListTypes()[$expertise->getTypeExpert()] . '.';
            }

        } elseif (User::isUserAdmin($adressee->username)) {

            if ($expertise->getStage() == StageExpertise::PROJECT) {
                $this->description = 'Эксперт, ' . $source->expert->second_name . ' ' . $source->expert->first_name . ' ' . $source->expert->middle_name . ', обновил данные ранее завершенной экспертизы по этапу «' . $this->getStage($expertise->getStage())
                    . ': ' . Html::a($source->project->project_name, ['/projects/index', 'id' => $source->project->user_id]) . '». Тип деятельности эксперта: ' . ExpertType::getListTypes()[$expertise->getTypeExpert()]
                    . '. Проектант: ' . Html::a($source->project->user->second_name . ' ' . $source->project->user->first_name . ' ' . $source->project->user->middle_name, ['/profile/index', 'id' => $source->project->user->id]) . '.';
            }else {
                $this->description = 'Эксперт, ' . $source->expert->second_name . ' ' . $source->expert->first_name . ' ' . $source->expert->middle_name . ', обновил данные ранее завершенной экспертизы по этапу «' . $this->getStage($expertise->getStage())
                    . ': ' . $this->getLinkStage($expertise) . '». Проект: «' . Html::a($source->project->project_name, ['/projects/index', 'id' => $source->project->user_id]) . '». Тип деятельности эксперта: ' . ExpertType::getListTypes()[$expertise->getTypeExpert()]
                    . '. Проектант: ' . Html::a($source->project->user->second_name . ' ' . $source->project->user->first_name . ' ' . $source->project->user->middle_name, ['/profile/index', 'id' => $source->project->user->id]) . '.';
            }
        }
        return $this->description;
    }


    /**
     * Получить название этапа экспертизы проекта
     *
     * @param int $stage
     * @return string
     */
    private function getStage($stage)
    {
        if ($stage == StageExpertise::PROJECT) {
            return 'описание проекта';
        } elseif ($stage == StageExpertise::SEGMENT) {
            return 'генерация гипотезы целевого сегмента';
        } elseif ($stage == StageExpertise::CONFIRM_SEGMENT) {
            return 'подтверждение гипотезы целевого сегмента';
        } elseif ($stage == StageExpertise::PROBLEM) {
            return 'генерация гипотезы проблемы сегмента';
        } elseif ($stage == StageExpertise::CONFIRM_PROBLEM) {
            return 'подтверждение гипотезы проблемы сегмента';
        } elseif ($stage == StageExpertise::GCP) {
            return 'разработка гипотезы ценностного предложения';
        } elseif ($stage == StageExpertise::CONFIRM_GCP) {
            return 'подтверждение гипотезы ценностного предложения';
        } elseif ($stage == StageExpertise::MVP) {
            return 'разработка MVP';
        } elseif ($stage == StageExpertise::CONFIRM_MVP) {
            return 'подтверждение MVP';
        } elseif ($stage == StageExpertise::BUSINESS_MODEL) {
            return 'генерация бизнес-модели';
        }
        return '';
    }


    /**
     * Получить объект проекта
     *
     * @param Expertise $expertise
     * @return Projects
     */
    private function getProject($expertise)
    {
        $stageClass = StageExpertise::getClassByStage(StageExpertise::getList()[$expertise->getStage()]);
        if (!$stageClass instanceof ConfirmationInterface) {
            $hypothesis = $stageClass::findOne($expertise->getStageId());
        } else {
            $hypothesis = $stageClass::findOne($expertise->getStageId())->getHypothesis();
        }
        $project = $hypothesis->project;
        return $project;
    }


    /**
     * Получить ссылку на этап проекта,
     * по которому была проведена экспертиза
     *
     * @param Expertise $expertise
     * @return string
     */
    public function getLinkStage($expertise)
    {
        $stageClass = StageExpertise::getClassByStage(StageExpertise::getList()[$expertise->getStage()]);
        $stageObj = $stageClass::findOne($expertise->getStageId());

        if ($stageObj instanceof Segments) {
            return Html::a($stageObj->name, ['/segments/index', 'id' => $stageObj->project_id]);
        } elseif ($stageObj instanceof ConfirmSegment) {
            return Html::a($stageObj->segment->name, ['/confirm-segment/view', 'id' => $stageObj->id]);
        } elseif ($stageObj instanceof Problems) {
            return Html::a($stageObj->title, ['/problems/index', 'id' => $stageObj->getConfirmSegmentId()]);
        } elseif ($stageObj instanceof ConfirmProblem) {
            return Html::a($stageObj->problem->title, ['/confirm-problem/view', 'id' => $stageObj->id]);
        } elseif ($stageObj instanceof Gcps) {
            return Html::a($stageObj->title, ['/gcps/index', 'id' => $stageObj->getConfirmProblemId()]);
        } elseif ($stageObj instanceof ConfirmGcp) {
            return Html::a($stageObj->gcp->title, ['/confirm-gcp/view', 'id' => $stageObj->id]);
        } elseif ($stageObj instanceof Mvps) {
            return Html::a($stageObj->title, ['/mvps/index', 'id' => $stageObj->getConfirmGcpId()]);
        } elseif ($stageObj instanceof ConfirmMvp) {
            return Html::a($stageObj->mvp->title, ['/confirm-mvp/view', 'id' => $stageObj->id]);
        } elseif ($stageObj instanceof BusinessModel) {
            return Html::a('бизнес-модель для ' . $stageObj->mvp->title, ['/business-model/index', 'id' => $stageObj->getConfirmMvpId()]);
        }
        return '';
    }
}