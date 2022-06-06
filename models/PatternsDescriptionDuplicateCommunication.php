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

        if ($source->getType() == CommunicationTypes::MAIN_ADMIN_APPOINTS_EXPERT_PROJECT) {

            if (User::isUserSimple($adressee->getUsername())) {

                $this->description = 'На ваш проект «' . $source->findProject()->getProjectName().'» назначен эксперт ' . $source->getExpert()->getUsername()
                    . '. Типы деятельности эксперта, по которым назначены экспертизы проекта: '
                    . ExpertType::getContent($source->findTypesAccessToExpertise()->getTypes()) . '. В сообщениях создана беседа с экспертом.';

            } elseif (User::isUserAdmin($adressee->getUsername())) {

                $this->description = 'На проект «' . Html::a($source->findProject()->getProjectName(), ['/projects/index', 'id' => $source->findProject()->getUserId()])
                    . '» (проектант: ' . Html::a($source->findProject()->findUser()->getUsername(), ['/profile/index', 'id' => $source->findProject()->getUserId()]) . ') назначен эксперт ' .
                    $source->getExpert()->getUsername() . '. Типы деятельности эксперта, по которым назначены экспертизы проекта: '
                    . ExpertType::getContent($source->findTypesAccessToExpertise()->getTypes()) . '.';
            }

        } elseif ($source->getType() == CommunicationTypes::MAIN_ADMIN_WITHDRAWS_EXPERT_FROM_PROJECT) {

            if (User::isUserSimple($adressee->getUsername())) {

                $this->description = 'С вашего проекта «' . $source->findProject()->getProjectName() .'» отозван эксперт ' . $source->getExpert()->getUsername() . '.';

            } elseif (User::isUserAdmin($adressee->getUsername())) {

                $this->description = 'С проекта «' . Html::a($source->findProject()->getProjectName(), ['/projects/index', 'id' => $source->findProject()->getUserId()])
                    . '» (проектант: ' . Html::a($source->findProject()->findUser()->getUsername(), ['/profile/index', 'id' => $source->findProject()->getUserId()])
                    . ') отозван эксперт ' . $source->getExpert()->getUsername() . '.';
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
        if (User::isUserSimple($adressee->getUsername())) {

            if ($expertise->getStage() == StageExpertise::PROJECT) {
                $this->description = 'Эксперт, ' . $source->getExpert()->getUsername() . ', завершил экспертизу по этапу «' . $this->getStage($expertise->getStage())
                    . ': ' . Html::a($source->findProject()->getProjectName(), ['/projects/index', 'id' => $source->findProject()->getUserId()]) . '». Тип деятельности эксперта: ' . ExpertType::getListTypes()[$expertise->getTypeExpert()] . '.';
            }else {
                $this->description = 'Эксперт, ' . $source->getExpert()->getUsername() . ', завершил экспертизу по этапу «' . $this->getStage($expertise->getStage())
                    . ': ' . $this->getLinkStage($expertise) . '». Проект: «' . Html::a($source->findProject()->getProjectName(), ['/projects/index', 'id' => $source->findProject()->getUserId()]) . '». Тип деятельности эксперта: ' . ExpertType::getListTypes()[$expertise->getTypeExpert()] . '.';
            }

        } elseif (User::isUserAdmin($adressee->getUsername())) {

            if ($expertise->getStage() == StageExpertise::PROJECT) {
                $this->description = 'Эксперт, ' . $source->getExpert()->getUsername() . ', завершил экспертизу по этапу «' . $this->getStage($expertise->getStage())
                    . ': ' . Html::a($source->findProject()->getProjectName(), ['/projects/index', 'id' => $source->findProject()->getUserId()]) . '». Тип деятельности эксперта: ' . ExpertType::getListTypes()[$expertise->getTypeExpert()]
                    . '. Проектант: ' . Html::a($source->findProject()->findUser()->getUsername(), ['/profile/index', 'id' => $source->findProject()->getUserId()]) . '.';
            }else {
                $this->description = 'Эксперт, ' . $source->getExpert()->getUsername() . ', завершил экспертизу по этапу «' . $this->getStage($expertise->getStage())
                    . ': ' . $this->getLinkStage($expertise) . '». Проект: «' . Html::a($source->findProject()->getProjectName(), ['/projects/index', 'id' => $source->findProject()->getUserId()]) . '». Тип деятельности эксперта: ' . ExpertType::getListTypes()[$expertise->getTypeExpert()]
                    . '. Проектант: ' . Html::a($source->findProject()->findUser()->getUsername(), ['/profile/index', 'id' => $source->findProject()->getUserId()]) . '.';
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
        if (User::isUserSimple($adressee->getUsername())) {

            if ($expertise->getStage() == StageExpertise::PROJECT) {
                $this->description = 'Эксперт, ' . $source->getExpert()->getUsername() . ', обновил данные ранее завершенной экспертизы по этапу «' . $this->getStage($expertise->getStage())
                    . ': ' . Html::a($source->findProject()->getProjectName(), ['/projects/index', 'id' => $source->findProject()->getUserId()]) . '». Тип деятельности эксперта: ' . ExpertType::getListTypes()[$expertise->getTypeExpert()] . '.';
            }else {
                $this->description = 'Эксперт, ' . $source->getExpert()->getUsername() . ', обновил данные ранее завершенной экспертизы по этапу «' . $this->getStage($expertise->getStage())
                    . ': ' . $this->getLinkStage($expertise) . '». Проект: «' . Html::a($source->findProject()->getProjectName(), ['/projects/index', 'id' => $source->findProject()->getUserId()]) . '». Тип деятельности эксперта: ' . ExpertType::getListTypes()[$expertise->getTypeExpert()] . '.';
            }

        } elseif (User::isUserAdmin($adressee->getUsername())) {

            if ($expertise->getStage() == StageExpertise::PROJECT) {
                $this->description = 'Эксперт, ' . $source->getExpert()->getUsername() . ', обновил данные ранее завершенной экспертизы по этапу «' . $this->getStage($expertise->getStage())
                    . ': ' . Html::a($source->findProject()->getProjectName(), ['/projects/index', 'id' => $source->findProject()->getUserId()]) . '». Тип деятельности эксперта: ' . ExpertType::getListTypes()[$expertise->getTypeExpert()]
                    . '. Проектант: ' . Html::a($source->findProject()->findUser()->getUsername(), ['/profile/index', 'id' => $source->findProject()->getUserId()]) . '.';
            }else {
                $this->description = 'Эксперт, ' . $source->getExpert()->getUsername() . ', обновил данные ранее завершенной экспертизы по этапу «' . $this->getStage($expertise->getStage())
                    . ': ' . $this->getLinkStage($expertise) . '». Проект: «' . Html::a($source->findProject()->getProjectName(), ['/projects/index', 'id' => $source->findProject()->getUserId()]) . '». Тип деятельности эксперта: ' . ExpertType::getListTypes()[$expertise->getTypeExpert()]
                    . '. Проектант: ' . Html::a($source->findProject()->findUser()->getUsername(), ['/profile/index', 'id' => $source->findProject()->getUserId()]) . '.';
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
        switch ($stage) {
            case StageExpertise::PROJECT:
                return 'описание проекта';
                break;
            case StageExpertise::SEGMENT:
                return 'генерация гипотезы целевого сегмента';
                break;
            case StageExpertise::CONFIRM_SEGMENT:
                return 'подтверждение гипотезы целевого сегмента';
                break;
            case StageExpertise::PROBLEM:
                return 'генерация гипотезы проблемы сегмента';
                break;
            case StageExpertise::CONFIRM_PROBLEM:
                return 'подтверждение гипотезы проблемы сегмента';
                break;
            case StageExpertise::GCP:
                return 'разработка гипотезы ценностного предложения';
                break;
            case StageExpertise::CONFIRM_GCP:
                return 'подтверждение гипотезы ценностного предложения';
                break;
            case StageExpertise::MVP:
                return 'разработка MVP';
                break;
            case StageExpertise::CONFIRM_MVP:
                return 'подтверждение MVP';
                break;
            case StageExpertise::BUSINESS_MODEL:
                return 'генерация бизнес-модели';
                break;
            default:
                return '';
        }
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
            return Html::a($stageObj->getName(), ['/segments/index', 'id' => $stageObj->getProjectId()]);
        } elseif ($stageObj instanceof ConfirmSegment) {
            return Html::a($stageObj->findSegment()->getName(), ['/confirm-segment/view', 'id' => $stageObj->getId()]);
        } elseif ($stageObj instanceof Problems) {
            return Html::a($stageObj->getTitle(), ['/problems/index', 'id' => $stageObj->getConfirmSegmentId()]);
        } elseif ($stageObj instanceof ConfirmProblem) {
            return Html::a($stageObj->findProblem()->getTitle(), ['/confirm-problem/view', 'id' => $stageObj->getId()]);
        } elseif ($stageObj instanceof Gcps) {
            return Html::a($stageObj->getTitle(), ['/gcps/index', 'id' => $stageObj->getConfirmProblemId()]);
        } elseif ($stageObj instanceof ConfirmGcp) {
            return Html::a($stageObj->findGcp()->getTitle(), ['/confirm-gcp/view', 'id' => $stageObj->getId()]);
        } elseif ($stageObj instanceof Mvps) {
            return Html::a($stageObj->getTitle(), ['/mvps/index', 'id' => $stageObj->getConfirmGcpId()]);
        } elseif ($stageObj instanceof ConfirmMvp) {
            return Html::a($stageObj->findMvp()->getTitle(), ['/confirm-mvp/view', 'id' => $stageObj->getId()]);
        } elseif ($stageObj instanceof BusinessModel) {
            return Html::a('бизнес-модель для ' . $stageObj->findMvp()->getTitle(), ['/business-model/index', 'id' => $stageObj->getConfirmMvpId()]);
        }
        return '';
    }
}