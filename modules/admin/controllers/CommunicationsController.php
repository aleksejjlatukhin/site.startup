<?php


namespace app\modules\admin\controllers;


use app\models\CommunicationPatterns;
use app\models\CommunicationTypes;
use app\models\DuplicateCommunications;
use app\models\ProjectCommunications;
use app\models\TypesAccessToExpertise;
use app\models\User;
use app\models\UserAccessToProjects;
use app\modules\admin\models\form\FormExpertTypes;
use app\modules\admin\models\form\FormUpdateCommunicationPattern;
use Throwable;
use Yii;
use yii\data\Pagination;
use yii\db\StaleObjectException;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\Response;


class CommunicationsController extends AppAdminController
{

    /**
     * Количество уведомлений
     * на странице
     */
    const NOTIFICATIONS_PAGE_SIZE = 20;


    public $layout = '@app/modules/admin/views/layouts/users';


    /**
     * @param $action
     * @return bool
     * @throws BadRequestHttpException
     * @throws HttpException
     */
    public function beforeAction($action)
    {

        if ($action->id == 'settings') {

            if (User::isUserDev(Yii::$app->user->identity['username']) || User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif ($action->id == 'notifications') {

            if (User::isUserDev(Yii::$app->user->identity['username']) || User::isUserMainAdmin(Yii::$app->user->identity['username'])
                || (User::isUserAdmin(Yii::$app->user->identity['username']) && (Yii::$app->user->getId() == Yii::$app->request->get('id')))) {

                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        } else{
            return parent::beforeAction($action);
        }

    }


    /**
     * Страница настройки
     * шаблонов коммуникаций
     *
     * @return string
     */
    public function actionSettings()
    {

        // Форма шаблона коммуникации
        $formPattern = new CommunicationPatterns();
        // Список для выбора срока доступа к проекту
        $selection_project_access_period = array_combine(range(1,30), range(1,30));
        foreach ($selection_project_access_period as $k => $item) {
            if (in_array($item, [1, 21])) {
                $selection_project_access_period[$k] = $item . ' день';
            } elseif (in_array($item, [2, 3, 4, 22, 23, 24])) {
                $selection_project_access_period[$k] = $item . ' дня';
            } else {
                $selection_project_access_period[$k] = $item . ' дней';
            }
        }

        // Шаблоны коммуникации о готовности эксперта провести экспертизу
        $patternsCommunicationsAboutReadinessConductExpertise = CommunicationPatterns::find()
            ->where(['communication_type' => CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE])
            ->andWhere(['initiator' => Yii::$app->user->id, 'is_remote' => CommunicationPatterns::NOT_REMOTE])
            ->orderBy('id DESC')
            ->all();

        // Шаблоны коммуникации отмена запроса о готовности эксперта провести экспертизу
        $patternsCommunicationsWithdrawsRequestAboutReadinessConductExpertise = CommunicationPatterns::find()
            ->where(['communication_type' => CommunicationTypes::MAIN_ADMIN_WITHDRAWS_REQUEST_ABOUT_READINESS_CONDUCT_EXPERTISE])
            ->andWhere(['initiator' => Yii::$app->user->id, 'is_remote' => CommunicationPatterns::NOT_REMOTE])
            ->orderBy('id DESC')
            ->all();

        // Шаблоны коммуникации назначение экперта на проект
        $patternsCommunicationsAppointsExpertProject = CommunicationPatterns::find()
            ->where(['communication_type' => CommunicationTypes::MAIN_ADMIN_APPOINTS_EXPERT_PROJECT])
            ->andWhere(['initiator' => Yii::$app->user->id, 'is_remote' => CommunicationPatterns::NOT_REMOTE])
            ->orderBy('id DESC')
            ->all();

        // Шаблоны коммуникации отказ эксперту в назначении на проект
        $patternsCommunicationsDoesNotAppointsExpertProject = CommunicationPatterns::find()
            ->where(['communication_type' => CommunicationTypes::MAIN_ADMIN_DOES_NOT_APPOINTS_EXPERT_PROJECT])
            ->andWhere(['initiator' => Yii::$app->user->id, 'is_remote' => CommunicationPatterns::NOT_REMOTE])
            ->orderBy('id DESC')
            ->all();

        // Шаблоны коммуникации отзыв эксперта с проекта
        $patternsCommunicationsWithdrawsExpertFromProject = CommunicationPatterns::find()
            ->where(['communication_type' => CommunicationTypes::MAIN_ADMIN_WITHDRAWS_EXPERT_FROM_PROJECT])
            ->andWhere(['initiator' => Yii::$app->user->id, 'is_remote' => CommunicationPatterns::NOT_REMOTE])
            ->orderBy('id DESC')
            ->all();


        return $this->render('settings', [
            'formPattern' => $formPattern,
            'selection_project_access_period' => $selection_project_access_period,
            'patternsCARCE' => $patternsCommunicationsAboutReadinessConductExpertise,
            'patternsCWRARCE' => $patternsCommunicationsWithdrawsRequestAboutReadinessConductExpertise,
            'patternsCAEP' => $patternsCommunicationsAppointsExpertProject,
            'patternsCDNAEP' => $patternsCommunicationsDoesNotAppointsExpertProject,
            'patternsCWEFP' => $patternsCommunicationsWithdrawsExpertFromProject,
        ]);
    }


    /**
     * Создание нового шаблона коммуникации
     * @param $communicationType
     * @return array|bool
     */
    public function actionCreatePattern($communicationType)
    {
        $formPattern = new CommunicationPatterns();

        if(Yii::$app->request->isAjax) {
            if ($formPattern->load(Yii::$app->request->post())) {
                $formPattern->setParams($communicationType);

                if ($formPattern->save()) {

                    if ($communicationType == CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE) {

                        $patternsCommunicationsAboutReadinessConductExpertise = CommunicationPatterns::find()
                            ->where(['communication_type' => CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE])
                            ->andWhere(['initiator' => Yii::$app->user->id, 'is_remote' => CommunicationPatterns::NOT_REMOTE])
                            ->orderBy('id DESC')
                            ->all();

                        $response = ['renderAjax' => $this->renderAjax('ajax_patterns_carce', [
                            'patternsCARCE' => $patternsCommunicationsAboutReadinessConductExpertise])];
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        Yii::$app->response->data = $response;
                        return $response;

                    } else {

                        $patterns = CommunicationPatterns::find()
                            ->where(['communication_type' => $communicationType])
                            ->andWhere(['initiator' => Yii::$app->user->id, 'is_remote' => CommunicationPatterns::NOT_REMOTE])
                            ->orderBy('id DESC')
                            ->all();

                        $response = ['renderAjax' => $this->renderAjax('ajax_patterns', [
                            'patterns' => $patterns, 'communicationType' => $communicationType])];
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        Yii::$app->response->data = $response;
                        return $response;
                    }
                }
            }
        }
        return false;
    }


    /**
     * Получить представление
     * одного шалона
     * @param $id
     * @return array|bool
     */
    public function getViewOnePattern($id)
    {
        if (Yii::$app->request->isAjax) {

            // Список для выбора срока доступа к проекту
            $selection_project_access_period = array_combine(range(1,30), range(1,30));
            foreach ($selection_project_access_period as $k => $item) {
                if (in_array($item, [1, 21])) {
                    $selection_project_access_period[$k] = $item . ' день';
                } elseif (in_array($item, [2, 3, 4, 22, 23, 24])) {
                    $selection_project_access_period[$k] = $item . ' дня';
                } else {
                    $selection_project_access_period[$k] = $item . ' дней';
                }
            }

            $pattern = CommunicationPatterns::findOne($id);

            $response = ['renderAjax' => $this->renderAjax('ajax_view_one_pattern', [
                'pattern' => $pattern, 'selection_project_access_period' => $selection_project_access_period])];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * Отмена редактирования
     * шаблона коммуникации
     * @param $id
     * @return array|bool
     */
    public function actionCancelEditPattern($id)
    {
        return $this->getViewOnePattern($id);
    }


    /**
     * Получить форму редактирования
     * шаблона коммуникации
     * @param $id
     * @param $communicationType
     * @return array|bool
     */
    public function actionGetFormUpdateCommunicationPattern($id, $communicationType)
    {
        if (Yii::$app->request->isAjax) {

            $formPattern = new FormUpdateCommunicationPattern($id, $communicationType);
            // Список для выбора срока доступа к проекту
            $selection_project_access_period = array_combine(range(1,30), range(1,30));
            foreach ($selection_project_access_period as $k => $item) {
                if (in_array($item, [1, 21])) {
                    $selection_project_access_period[$k] = $item . ' день';
                } elseif (in_array($item, [2, 3, 4, 22, 23, 24])) {
                    $selection_project_access_period[$k] = $item . ' дня';
                } else {
                    $selection_project_access_period[$k] = $item . ' дней';
                }
            }

            $response = ['renderAjax' => $this->renderAjax('ajax_form_update_pattern', [
                'formPattern' => $formPattern, 'selection_project_access_period' => $selection_project_access_period])];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * Редактирование
     * шаблона коммуникации
     * @param $id
     * @param $communicationType
     * @return array|bool
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionUpdatePattern($id, $communicationType)
    {
        $formPattern = new FormUpdateCommunicationPattern($id, $communicationType);

        if(Yii::$app->request->isAjax) {
            if ($formPattern->load(Yii::$app->request->post())) {
                $formPattern->update();
                return $this->getViewOnePattern($id);
            }
        }
        return false;
    }


    /**
     * Активация шаблона
     * коммуникации
     *
     * @param $id
     * @param $communicationType
     * @return bool|array
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionActivatePattern($id, $communicationType)
    {
        if(Yii::$app->request->isAjax) {

            $patternsActive = CommunicationPatterns::find()
                ->where(['communication_type' => $communicationType, 'is_active' => CommunicationPatterns::ACTIVE])
                ->andWhere(['initiator' => Yii::$app->user->id, 'is_remote' => CommunicationPatterns::NOT_REMOTE])
                ->all();

            foreach ($patternsActive as $item) {
                $item->is_active = CommunicationPatterns::NO_ACTIVE;
                $item->update(true, ['is_active']);
            }

            $patternActivate = CommunicationPatterns::findOne($id);
            $patternActivate->is_active = CommunicationPatterns::ACTIVE;
            $patternActivate->update(true, ['is_active']);

            $patterns = CommunicationPatterns::find()
                ->where(['communication_type' => $communicationType])
                ->andWhere(['initiator' => Yii::$app->user->id, 'is_remote' => CommunicationPatterns::NOT_REMOTE])
                ->orderBy('id DESC')
                ->all();

            if ($communicationType == CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE) {

                $response = ['renderAjax' => $this->renderAjax('ajax_patterns_carce', [
                    'patternsCARCE' => $patterns])];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;

            } else {

                $response = ['renderAjax' => $this->renderAjax('ajax_patterns', [
                    'patterns' => $patterns, 'communicationType' => $communicationType])];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;
            }
        }
        return false;
    }


    /**
     * Деактивация шаблона
     * коммуникации
     *
     * @param $id
     * @return array|bool
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionDeactivatePattern($id)
    {
        $pattern = CommunicationPatterns::findOne($id);

        if(Yii::$app->request->isAjax) {

            $pattern->is_active = CommunicationPatterns::NO_ACTIVE;
            $pattern->update(true, ['is_active']);
            return $this->getViewOnePattern($id);

        }
        return false;
    }


    /**
     * Удаление шаблона
     * коммуникации из списка
     *
     * @param $id
     * @return bool
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionDeletePattern($id)
    {
        $pattern = CommunicationPatterns::findOne($id);

        if(Yii::$app->request->isAjax) {

            $pattern->is_remote = CommunicationPatterns::REMOTE;
            $pattern->update(true, ['is_remote']);
            return true;
        }
        return false;
    }


    /**
     * Метод для отправки
     * коммуникации
     *
     * @param int $adressee_id
     * @param int $project_id
     * @param int $type
     * @param null|int $triggered_communication_id
     * @return array|bool
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionSend($adressee_id, $project_id, $type, $triggered_communication_id = null)
    {
        if(Yii::$app->request->isAjax) {

            // Данные из формы выбора типов деятельности эксперта при назначении на проект
            $postExpertTypes = $_POST['FormExpertTypes']['expert_types'] ?: null;

            $communication = new ProjectCommunications();
            $communication->setParams($adressee_id, $project_id, $type);
            $communication->setTriggeredCommunicationId($triggered_communication_id);
            if ($communication->save()) {
                $accessToProject = new UserAccessToProjects();
                $accessToProject->setParams($adressee_id, $project_id, $communication);
                if ($accessToProject->save()) {

                    $result_ReadCommunication = [];

                    // Отправка письма эксперту на почту
                    $this->sendCommunicationToEmail($communication);

                    if ($type == CommunicationTypes::MAIN_ADMIN_WITHDRAWS_REQUEST_ABOUT_READINESS_CONDUCT_EXPERTISE) {

                        // Тип коммуникации "отмена запроса о готовности провести экспертизу"

                        // Устанавливаем параметр аннулирования предыдущей коммуникации
                        $communicationCanceled = ProjectCommunications::find()
                            ->where([
                                'adressee_id' => $adressee_id,
                                'project_id' => $project_id,
                                'type' => CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE
                            ])
                            ->orderBy('id DESC')
                            ->one();

                        $communicationCanceled->setCancel();
                        $communicationCanceled->update();

                        $communicationCanceledUserAccessToProject = $communicationCanceled->userAccessToProject;
                        $communicationCanceledUserAccessToProject->setCancel();
                        $communicationCanceledUserAccessToProject->update();

                    } elseif ($type == CommunicationTypes::MAIN_ADMIN_APPOINTS_EXPERT_PROJECT) {

                        // Тип коммуникации "назначение эксперта на проект"

                        // Типы доступных экспертиз по проекту
                        $typesAccessToExpertise = new TypesAccessToExpertise();
                        $typesAccessToExpertise->create($adressee_id, $project_id, $communication->getId(), $postExpertTypes);

                        // Прочтение коммуникации на которое поступил ответ
                        $result_ReadCommunication = $this->responseForReadCommunication($triggered_communication_id);

                        // Создание беседы между проектантом и экспертом
                        User::createConversationExpert($communication->project->user, $communication->expert);

                        // Отправка уведомления проектанту и трекеру
                        DuplicateCommunications::create($communication, $communication->project->user);
                        DuplicateCommunications::create($communication, $communication->project->user->admin);

                    } elseif ($type == CommunicationTypes::MAIN_ADMIN_DOES_NOT_APPOINTS_EXPERT_PROJECT) {

                        // Тип коммуникации "отказ в проведении экспертизы"

                        // Прочтение коммуникации на которое поступил ответ
                        $result_ReadCommunication = $this->responseForReadCommunication($triggered_communication_id);

                    } elseif ($type == CommunicationTypes::MAIN_ADMIN_WITHDRAWS_EXPERT_FROM_PROJECT) {

                        // Тип коммуникации "отозвать эксперта с проекта"

                        // Отправка уведомления проектанту и трекеру
                        DuplicateCommunications::create($communication, $communication->project->user);
                        DuplicateCommunications::create($communication, $communication->project->user->admin);
                    }

                    $result_SendCommunication = ['success' => true, 'type' => $type, 'project_id' => $project_id];

                    $response = array_merge($result_ReadCommunication, $result_SendCommunication);
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
        return false;
    }


    /**
     * Получить форму выбора типов
     * эксперта при назначении на проект
     *
     * @param $id
     * @return array|bool
     */
    public function actionGetFormTypesExpert($id)
    {
        if(Yii::$app->request->isAjax) {

            $response = ['renderAjax' => $this->renderAjax('get_form_types_expert', [
                'communicationExpert' => ProjectCommunications::findOne($id),
                'formExpertTypes' => new FormExpertTypes()])];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * Получить коммуникации
     * по проекту
     *
     * @param $id
     * @return bool|array
     */
    public function actionGetCommunications($id)
    {
        if(Yii::$app->request->isAjax) {

            // Допуски экспертов к проекту
            $admittedExperts = UserAccessToProjects::find()
                ->select(['user_id', 'project_id'])
                ->distinct('user_id')
                ->where(['project_id' => $id])
                ->all();

            $response = ['renderAjax' => $this->renderAjax('ajax_get_communications', [
                'admittedExperts' => $admittedExperts, 'project_id' => $id]), 'project_id' => $id];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * Отправка письма с уведомлением
     * на почту эксперта
     *
     * @param ProjectCommunications $communication
     * @return bool
     */
    public function sendCommunicationToEmail($communication)
    {
        /* @var $user User */
        $user = User::findOne($communication->adressee_id);

        if ($user) {
            return Yii::$app->mailer->compose('communications__FromMainAdminToExpert', ['user' => $user, 'communication' => $communication])
                ->setFrom([Yii::$app->params['supportEmail'] => 'Spaccel.ru - Акселератор стартап-проектов'])
                ->setTo($user->email)
                ->setSubject('Вам пришло новое уведомление на сайте Spaccel.ru')
                ->send();
        }

        return false;
    }


    /**
     * Страница
     * уведомлений
     *
     * @param int $id
     * @param int $page
     * @return string
     */
    public function actionNotifications($id, $page = 1)
    {
        $pageSize = self::NOTIFICATIONS_PAGE_SIZE;

        if (User::isUserAdmin(Yii::$app->user->identity['username'])) {

            // Дублирующие коммуникации отправленные трекеру
            $query_communications = DuplicateCommunications::find()
                ->leftJoin('project_communications', '`project_communications`.`id` = `duplicate_communications`.`source_id`')
                ->where(['duplicate_communications.adressee_id' => $id])
                ->orderBy('id DESC');

            $pages = new Pagination(['totalCount' => $query_communications->count(), 'page' => ($page - 1), 'pageSize' => $pageSize]);
            $pages->pageSizeParam = false; //убираем параметр $per-page
            $communications = $query_communications->offset($pages->offset)->limit($pageSize)->all();

            return $this->render('admin_notifications', [
                'communications' => $communications,
                'pages' => $pages,
            ]);

        } elseif (User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

            $query_communications = ProjectCommunications::find()
                ->where(['adressee_id' => $id])
                ->andWhere(['type' => CommunicationTypes::EXPERT_ANSWERS_QUESTION_ABOUT_READINESS_CONDUCT_EXPERTISE])
                ->orderBy('id DESC');

            $pages = new Pagination(['totalCount' => $query_communications->count(), 'page' => ($page - 1), 'pageSize' => $pageSize]);
            $pages->pageSizeParam = false; //убираем параметр $per-page
            $communications = $query_communications->offset($pages->offset)->limit($pageSize)->all();

            return $this->render('notifications', [
                'communications' => $communications,
                'pages' => $pages,
            ]);
        }

        return $this->goBack();
    }


    /**
     * Прочтение уведомлений
     * (коммуникации)
     * по проекту
     *
     * @param int $id
     * @return array|bool
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionReadCommunication($id)
    {
        if(Yii::$app->request->isAjax) {

            $response = $this->responseForReadCommunication($id);
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @param int $id
     * @return array
     * @throws StaleObjectException
     * @throws Throwable
     */
    private function responseForReadCommunication($id)
    {
        $communication = ProjectCommunications::findOne($id);
        $communication->setStatusRead();
        $communication->update();

        $user = User::findOne($communication->getAdresseeId());
        $countUnreadCommunications = $user->getCountUnreadCommunications();
        $countUnreadCommunicationsByProject = $user->getCountUnreadCommunicationsByProject($communication->getProjectId());

        return $response = [
            'project_id' => $communication->getProjectId(),
            'countUnreadCommunications' => $countUnreadCommunications,
            'countUnreadCommunicationsByProject' => $countUnreadCommunicationsByProject
        ];
    }


    /**
     * Прочтение уведомлений трекером
     * (дублирующие коммуникации)
     * по проекту
     *
     * @param int $id
     * @return array|bool
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionReadDuplicateCommunication($id)
    {
        if(Yii::$app->request->isAjax) {

            $communication = DuplicateCommunications::findOne($id);
            $communication->setStatusRead();
            $communication->update();

            $user = User::findOne($communication->getAdresseeId());
            $countUnreadCommunications = $user->getCountUnreadCommunications();
            $countUnreadCommunicationsByProject = $user->getCountUnreadCommunicationsByProject($communication->getSource()->getProjectId());

            $response = [
                'project_id' => $communication->getSource()->getProjectId(),
                'countUnreadCommunications' => $countUnreadCommunications,
                'countUnreadCommunicationsByProject' => $countUnreadCommunicationsByProject
            ];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }
}