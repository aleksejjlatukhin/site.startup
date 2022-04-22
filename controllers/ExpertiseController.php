<?php


namespace app\controllers;


use app\models\Expertise;
use app\models\ExpertType;
use app\models\forms\expertise\FormExpertiseManyAnswer;
use app\models\forms\expertise\FormExpertiseSingleAnswer;
use app\models\interfaces\ConfirmationInterface;
use app\models\ProjectCommunications;
use app\models\Projects;
use app\models\StageExpertise;
use app\models\User;
use yii\web\Response;
use Yii;


/**
 * Контроллер для создания, редактирования
 * и вывода данных экспертиз
 *
 * Class ExpertiseController
 * @package app\controllers
 */
class ExpertiseController extends AppUserPartController
{

    /**
     * Для эксперта вывести список выбора доступных экспертиз
     * по типам деятельности эксперта на проекте
     *
     * Для остальных вывести готовые экспертизы всех экспертов
     * по данному этапу, если такие есть
     *
     * @param $stage string
     * @param $stageId int
     * @return array|bool
     */
    public function actionGetList($stage, $stageId)
    {
        if (Yii::$app->request->isAjax) {

            if (User::isUserExpert(Yii::$app->user->identity['username'])) {

                $expert = User::findOne(Yii::$app->user->id);

                if ($stage == StageExpertise::getList()[StageExpertise::PROJECT]) {
                    $userAccessToProject = $expert->findUserAccessToProject($stageId);
                } else {
                    $stageClass = StageExpertise::getClassByStage($stage);
                    $interfaces = class_implements($stageClass);
                    $hypothesis = !isset($interfaces[ConfirmationInterface::class]) ? $stageClass::findOne($stageId) : $stageClass::findOne($stageId)->hypothesis;
                    $userAccessToProject = $expert->findUserAccessToProject($hypothesis->project->id);
                }

                /** @var ProjectCommunications $communication */
                $communication = $userAccessToProject->communication;
                $typesAccessToExpertise = $communication->findTypesAccessToExpertise();
                $types = ExpertType::getListTypes(null, $typesAccessToExpertise->types);

                $response = [
                    'headerContent' => 'Экспертиза по этапу: ' . StageExpertise::getTitle($stage, $stageId),
                    'renderList' => $this->renderAjax('list_expertise', [
                        'types' => $types, 'stage' => $stage, 'stageId' => $stageId,
                    ]),
                ];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;

            } else {

                // Инициализируем дирректорию с json-файлами содержащие вариантов ответов для разных форм экспертизы
                Yii::setAlias('@dirDataFormExpertise', '../models/forms/expertise/answerOptions');

                $models = Expertise::find()->where([
                    'stage' => array_search($stage, StageExpertise::getList()),
                    'stage_id' => $stageId,
                    'completed' => Expertise::COMPLETED
                ])->orderBy('updated_at DESC')->all();

                $stageClass = StageExpertise::getClassByStage($stage);
                $interfaces = class_implements($stageClass);

                $data = array(); // Массив с данными по экспертизе данного этапа проекта

                foreach ($models as $i => $model) {
                    $data[$i]['id'] = $model->id;
                    $data[$i]['updated_at'] = $model->updated_at;
                    $data[$i]['type'] = $model->getTypeExpert();
                    $data[$i]['fio_expert'] = $model->expert->second_name . ' ' . $model->expert->first_name . ' ' . $model->expert->middle_name;
                    $data[$i]['general_estimation_by_one'] = $model->getGeneralEstimationByOne();
                    $data[$i]['comment'] = $model->getComment();
                    $data[$i]['form'] = !isset($interfaces[ConfirmationInterface::class]) ? new FormExpertiseSingleAnswer($model) : new FormExpertiseManyAnswer($model);
                }

                $response = [
                    'headerContent' => 'Экспертиза по этапу: ' . StageExpertise::getTitle($stage, $stageId),
                    'renderList' => $this->renderAjax('user_list_expertise', [
                        'stage' => $stage, 'stageId' => $stageId, 'data' => $data
                    ]),
                ];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;
            }
        }
        return false;
    }


    /**
     * Вывести форму для проведения
     * экспертизы по этапу проекта
     *
     * @param string $stage
     * @param int $stageId
     * @param int $type
     * @param int|bool $completed
     * @return array|bool
     */
    public function actionGetForm($stage, $stageId, $type, $completed = false)
    {
        if (Yii::$app->request->isAjax && User::isUserExpert(Yii::$app->user->identity['username'])) {

            // Инициализируем дирректорию с json-файлами содержащие вариантов ответов для разных форм экспертизы
            Yii::setAlias('@dirDataFormExpertise', '../models/forms/expertise/answerOptions');

            $expert = User::findOne(Yii::$app->user->id);
            $stageClass = StageExpertise::getClassByStage($stage);
            $interfaces = class_implements($stageClass);

            if ($stage == StageExpertise::getList()[StageExpertise::PROJECT]) {
                $project = Projects::findOne($stageId);
                $userAccessToProject = $expert->findUserAccessToProject($stageId);
            } else {
                $hypothesis = !isset($interfaces[ConfirmationInterface::class]) ? $stageClass::findOne($stageId) : $stageClass::findOne($stageId)->hypothesis;
                $project = $hypothesis->project;
                $userAccessToProject = $expert->findUserAccessToProject($project->id);
            }

            $expertise = Expertise::findOne([
                'stage' => array_search($stage, StageExpertise::getList()),
                'stage_id' => $stageId,
                'expert_id' => $expert->getId(),
                'type_expert' => $type,
                'communication_id' => $userAccessToProject->communication_id
            ]);

            if (!$expertise) {
                $expertise = new Expertise();
                $expertise->setStage(array_search($stage, StageExpertise::getList()));
                $expertise->setStageId($stageId);
                $expertise->setExpertId($expert->getId());
                $expertise->setUserId($project->getUserId());
                $expertise->setTypeExpert($type);
                $expertise->setCommunicationId($userAccessToProject->communication_id);
            }

            $model = !isset($interfaces[ConfirmationInterface::class]) ? new FormExpertiseSingleAnswer($expertise) : new FormExpertiseManyAnswer($expertise);

            // Сохранить форму экспертизы
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                //if ($model instanceof FormExpertiseSingleAnswer) {
                    if ($model->saveRecord($completed)) {
                        // После сохранения вернуться к списку экспертиз по типам деятельности
                        return $this->actionGetList($stage, $stageId);
                    }
                //}
            }

            // Показать форму экспертизы
            if (!isset($interfaces[ConfirmationInterface::class])) {
                $response = [
                    'renderAjax' => $this->renderAjax('hypothesis/form_expertise_single_answer', [
                        'model' => $model, 'stage' => $stage, 'stageId' => $stageId
                    ]),
                ];
            } else {
                $response = [
                    'renderAjax' => $this->renderAjax('confirm/form_expertise_many_answer', [
                        'model' => $model, 'stage' => $stage, 'stageId' => $stageId
                    ]),
                ];
            }

            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }

}