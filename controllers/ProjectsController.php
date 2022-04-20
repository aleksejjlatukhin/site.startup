<?php


namespace app\controllers;


use app\models\Authors;
use app\models\BusinessModel;
use app\models\ClientUser;
use app\models\CommunicationResponse;
use app\models\CommunicationTypes;
use app\models\forms\CacheForm;
use app\models\Gcps;
use app\models\Problems;
use app\models\Mvps;
use app\models\PreFiles;
use app\models\ProjectSort;
use app\models\Roadmap;
use app\models\Segments;
use app\models\SortForm;
use app\models\User;
use kartik\mpdf\Pdf;
use Mpdf\MpdfException;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;
use Throwable;
use Yii;
use app\models\Projects;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\data\ArrayDataProvider;
use yii\db\StaleObjectException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;


/**
 * Class ProjectsController
 * @package app\controllers
 */
class ProjectsController extends AppUserPartController
{

    /**
     * @param $action
     * @return bool
     * @throws HttpException
     */
    public function beforeAction($action)
    {
        $currentUser = User::findOne(Yii::$app->user->getId());
        /** @var ClientUser $currentClientUser */
        $currentClientUser = $currentUser->clientUser;

        if (in_array($action->id, ['result']) || in_array($action->id, ['result-export']) || in_array($action->id, ['report']) || in_array($action->id, ['upshot']) || in_array($action->id, ['mpdf-project'])){

            $model = Projects::findOne(Yii::$app->request->get('id'));

            /*Ограничение доступа к проэктам пользователя*/

            if (($model->getUserId() == $currentUser->getId())){

                return parent::beforeAction($action);

            } elseif (User::isUserAdmin($currentUser->getUsername()) && $model->user->getIdAdmin() == $currentUser->getId()) {

                return parent::beforeAction($action);

            } elseif (User::isUserMainAdmin($currentUser->getUsername()) || User::isUserDev($currentUser->getUsername()) || User::isUserAdminCompany($currentUser->getUsername())) {

                /** @var ClientUser $modelClientUser */
                $modelClientUser = $model->user->clientUser;

                if ($currentClientUser->getClientId() == $modelClientUser->getClientId()) {
                    return parent::beforeAction($action);
                } else {
                    throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
                }

            } elseif (User::isUserExpert($currentUser->getUsername())) {

                $expert = User::findOne(Yii::$app->user->getId());

                $userAccessToProject = $expert->findUserAccessToProject($model->id);

                if ($userAccessToProject) {

                    if ($userAccessToProject->communication_type == CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE) {

                        $responsiveCommunication = $userAccessToProject->communication->responsiveCommunication;

                        if ($responsiveCommunication) {

                            if ($responsiveCommunication->communicationResponse->answer == CommunicationResponse::POSITIVE_RESPONSE) {

                                return parent::beforeAction($action);

                            } else {
                                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
                            }

                        } else {

                            if (time() < $userAccessToProject->date_stop) {

                                return parent::beforeAction($action);

                            } else {
                                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
                            }
                        }

                    } elseif ($userAccessToProject->communication_type == CommunicationTypes::MAIN_ADMIN_APPOINTS_EXPERT_PROJECT) {

                        return parent::beforeAction($action);

                    } else {
                        throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
                    }
                } else{
                    throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
                }

            } else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['mpdf-business-model'])){

            $businessModel = BusinessModel::findOne(Yii::$app->request->get());
            /** @var Projects $model */
            $model = $businessModel->project;

            /*Ограничение доступа к проэктам пользователя*/

            if (($model->getUserId() == $currentUser->getId())){

                return parent::beforeAction($action);

            } elseif (User::isUserAdmin($currentUser->getUsername()) && $model->user->getIdAdmin() == $currentUser->getId()) {

                return parent::beforeAction($action);

            } elseif (User::isUserMainAdmin($currentUser->getUsername()) || User::isUserDev($currentUser->getUsername()) || User::isUserAdminCompany($currentUser->getUsername())) {

                /** @var ClientUser $modelClientUser */
                $modelClientUser = $model->user->clientUser;

                if ($currentClientUser->getClientId() == $modelClientUser->getClientId()) {
                    return parent::beforeAction($action);
                } else {
                    throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
                }

            } else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){

            /*Ограничение доступа к проэктам пользователя*/

            if (User::isUserSimple($currentUser->getUsername()) && $currentUser->getId() == Yii::$app->request->get('id')) {
                return parent::beforeAction($action);
            } else {
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['index'])){

            $model = User::findOne(Yii::$app->request->get('id'));

            /*Ограничение доступа к проэктам пользователя*/

            if (($model->getId() == $currentUser->getId())){

                return parent::beforeAction($action);

            } elseif (User::isUserAdmin($currentUser->getUsername()) && $model->getIdAdmin() == $currentUser->getId()) {

                return parent::beforeAction($action);

            } elseif (User::isUserMainAdmin($currentUser->getUsername()) || User::isUserDev($currentUser->getUsername()) || User::isUserAdminCompany($currentUser->getUsername())) {

                /** @var ClientUser $modelClientUser */
                $modelClientUser = $model->clientUser;

                if ($currentClientUser->getClientId() == $modelClientUser->getClientId()) {
                    return parent::beforeAction($action);
                } else {
                    throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
                }

            } elseif (User::isUserExpert($currentUser->getUsername())) {

                $expert = User::findOne(Yii::$app->user->id);

                if (Yii::$app->request->get('project_id')) {

                    $userAccessToProject = $expert->findUserAccessToProject(Yii::$app->request->get('project_id'));

                    if ($userAccessToProject) {

                        if ($userAccessToProject->communication_type == CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE) {

                            $responsiveCommunication = $userAccessToProject->communication->responsiveCommunication;

                            if ($responsiveCommunication) {

                                if ($responsiveCommunication->communicationResponse->answer == CommunicationResponse::POSITIVE_RESPONSE) {

                                    return parent::beforeAction($action);

                                } else {
                                    throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
                                }

                            } else {

                                if (time() < $userAccessToProject->date_stop) {

                                    return parent::beforeAction($action);

                                } else {
                                    throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
                                }
                            }

                        } elseif ($userAccessToProject->communication_type == CommunicationTypes::MAIN_ADMIN_APPOINTS_EXPERT_PROJECT) {

                            return parent::beforeAction($action);

                        } else {
                            throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
                        }
                    } else{
                        throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
                    }
                } else{
                    throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
                }
            } else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['update']) || in_array($action->id, ['delete'])){

            $project = Projects::findOne(Yii::$app->request->get('id'));
            $user = User::findOne($project->getUserId());

            /*Ограничение доступа к проэктам пользователя*/

            if (($user->getId() == $currentUser->getId())){

                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }else{
            return parent::beforeAction($action);
        }
    }


    /**
     * @param int $id
     * @param null|int $project_id
     * @return string
     */
    public function actionIndex($id, $project_id = null)
    {
        $user = User::findOne($id);
        $condition = $project_id ? ['user_id' => $id, 'id' => $project_id] : ['user_id' => $id];
        $models = Projects::findAll($condition);

        if (!$models) return $this->redirect(['/projects/instruction', 'id' => $id]);

        return $this->render('index', [
            'user' => $user,
            'models' => $models,
            'new_author' => new Authors(),
            'sortModel' => new SortForm(),
        ]);
    }

    /**
     * @param $id
     * @return string
     */
    public function actionInstruction ($id)
    {
        $models = Projects::findAll(['user_id' => $id]);
        if ($models) return $this->redirect(['/projects/index', 'id' => $id]);

        return $this->render('index_first', [
            'user' => User::findOne($id),
            'new_author' => new Authors(),
        ]);
    }

    /**
     * @return bool|string
     */
    public function actionGetInstruction ()
    {
        if(Yii::$app->request->isAjax) {
            $response = $this->renderAjax('instruction');
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @param $id
     */
    public function actionSaveCacheCreationForm($id)
    {
        $user = User::findOne($id);
        $cachePath = Projects::getCachePath($user);
        $cacheName = 'formCreateProjectCache';

        if(Yii::$app->request->isAjax) {

            $cache = new CacheForm();
            $cache->setCache($cachePath, $cacheName);
        }
    }


    /**
     * @param $id
     * @return array|bool
     */
    public function actionGetHypothesisToCreate ($id)
    {
        $user = User::findOne($id);
        $model = new Projects();
        $author = new Authors();

        if(Yii::$app->request->isAjax) {

            $cachePath = $model::getCachePath($user);
            $cacheName = 'formCreateProjectCache';

            if ($cache = $model->_cacheManager->getCache($cachePath, $cacheName)) {

                //Заполнение полей модели Projects данными из кэша
                foreach ($cache['Projects'] as $key => $value) {
                    $model[$key] = $value;
                }

                $response = [
                    'renderAjax' => $this->renderAjax('create', [
                        'user' => $user,
                        'model' => $model,
                        'author' => $author
                    ]),
                    'cache' => $cache,
                ];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;

            } else {

                $response = [
                    'renderAjax' => $this->renderAjax('create', [
                        'user' => $user,
                        'model' => $model,
                        'author' => $author
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
     * @param $id
     * @return array|bool
     * @throws NotFoundHttpException
     * @throws ErrorException
     * @throws Exception
     */
    public function actionCreate($id)
    {
        $model = new Projects();
        $model->user_id = $id;
        $user = User::findOne($id);

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                //Проверка на совпадение по названию проекта у данного пользователя
                if ($model->validate(['project_name'])) {

                    if ($model->create()){

                        // Удаление кэша формы создания
                        $cachePath = $model::getCachePath($user);
                        $model->_cacheManager->deleteCache(mb_substr($cachePath, 0, -1));

                        //Проверка наличия сортировки
                        $type_sort_id = $_POST['type_sort_id'];

                        if ($type_sort_id != '') {

                            $sort = new ProjectSort();

                            $response =  [
                                'success' => true, 'count' => Projects::find()->where(['user_id' => $user->id])->count(),
                                'renderAjax' => $this->renderAjax('_index_ajax', [
                                    'models' => $sort->fetchModels($user->id, $type_sort_id),
                                ]),
                            ];
                            Yii::$app->response->format = Response::FORMAT_JSON;
                            Yii::$app->response->data = $response;
                            return $response;

                        } else {

                            $response =  [
                                'success' => true,
                                'renderAjax' => $this->renderAjax('_index_ajax', [
                                    'models' => Projects::findAll(['user_id' => $user->id]),
                                ]),
                            ];
                            Yii::$app->response->format = Response::FORMAT_JSON;
                            Yii::$app->response->data = $response;
                            return $response;
                        }
                    }
                }else{

                    //Проект с таким именем уже существует
                    $response =  ['project_already_exists' => true];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
        return false;
    }


    /**
     * @param $id
     * @return array|bool
     */
    public function actionGetHypothesisToUpdate ($id)
    {
        $model = Projects::findOne($id);
        $workers = Authors::findAll(['project_id' => $id]);

        if(Yii::$app->request->isAjax) {

            $response = [
                'renderAjax' => $this->renderAjax('update', [
                    'model' => $model,
                    'workers' => $workers
                ]),
            ];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @param $current_id
     * @param $type_sort_id
     * @return array|bool
     */
    public function actionSortingModels($current_id, $type_sort_id)
    {
        $sort = new ProjectSort();

        if (Yii::$app->request->isAjax) {

            $response =  ['renderAjax' => $this->renderAjax('_index_ajax', [
                'models' => $sort->fetchModels($current_id, $type_sort_id)
                ])
            ];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @param $id
     * @return \yii\console\Response|Response
     * @throws NotFoundHttpException
     */
    public function actionDownload($id)
    {
        $model = PreFiles::findOne($id);
        $project = Projects::findOne(['id' => $model->project_id]);
        $user = User::findOne(['id' => $project->user_id]);

        $path = UPLOAD.'/user-'.$user->id.'/project-'.$project->id.'/present_files/';
        $file = $path . $model->server_file;

        if (file_exists($file)) {
            return Yii::$app->response->sendFile($file, $model->file_name);
        }
        throw new NotFoundHttpException('Данный файл не найден');
    }


    /**
     * @param $id
     * @return array|Response
     * @throws NotFoundHttpException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDeleteFile($id)
    {
        $model = PreFiles::findOne($id);
        $project = Projects::find()->where(['id' => $model->project_id])->one();
        $user = User::find()->where(['id' => $project->user_id])->one();
        $path = UPLOAD.'/user-'.$user->id.'/project-'.$project->id.'/present_files/';

        if(unlink($path . $model->server_file) && $model->delete()) {

            $models = PreFiles::findAll(['project_id' => $project->id]);

            if (Yii::$app->request->isAjax)
            {
                $response =  [
                    'success' => true,
                    'count_files' => count($models),
                    'project_id' => $project->id,
                ];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;

            }else{
                return $this->redirect(['/projects/index', 'id' => $user->id]);
            }
        }
        throw new NotFoundHttpException('Данный файл не найден');
    }


    /**
     * @return array
     */
    public function actionListTypeSort()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (isset($_POST['depdrop_parents'])) {

            $parents = $_POST['depdrop_parents'];

            if ($parents != null && $parents[0] != 0) {

                $cat_id = $parents[0];
                $out = ProjectSort::getListTypes($cat_id);
                return ['output' => $out, 'selected' => ''];
            }
        }
        return ['output' => '', 'selected' => ''];
    }


    /**
     * @param $id
     * @return array|bool
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $user = User::findOne($model->userId);

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                //Проверка на совпадение по названию проекта у данного пользователя
                if ($model->validate(['project_name'])) {

                    if ($model->updateProject()){

                        //Проверка наличия сортировки
                        $type_sort_id = $_POST['type_sort_id'];

                        if ($type_sort_id != '') {

                            $sort = new ProjectSort();

                            $response =  [
                                'success' => true,
                                'renderAjax' => $this->renderAjax('_index_ajax', [
                                    'models' => $sort->fetchModels($user->id, $type_sort_id),
                                ]),
                            ];
                            Yii::$app->response->format = Response::FORMAT_JSON;
                            Yii::$app->response->data = $response;
                            return $response;

                        } else {

                            $response =  [
                                'success' => true,
                                'renderAjax' => $this->renderAjax('_index_ajax', [
                                    'models' => Projects::findAll(['user_id' => $user->id]),
                                ]),
                            ];
                            Yii::$app->response->format = Response::FORMAT_JSON;
                            Yii::$app->response->data = $response;
                            return $response;
                        }
                    }
                } else{

                    //Проект с таким именем уже существует
                    $response =  ['project_already_exists' => true];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
        return false;
    }


    /**
     * Включить разрешение на экспертизу
     * @param $id
     * @return array|bool
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionEnableExpertise($id)
    {
        if(Yii::$app->request->isAjax) {

            $project = Projects::findOne($id);
            $project->setEnableExpertise();
            if ($project->update()) {

                // ToDo: Если на проект назначены эксперты отправить им уведомление о том,
                // ToDo: что проектант разрешил экспертизу по этапу проекта, а так же уведомление трекеру

                //Проверка наличия сортировки
                $type_sort_id = $_POST['type_sort_id'];

                if ($type_sort_id != '') {

                    $sort = new ProjectSort();

                    $response = [
                        'renderAjax' => $this->renderAjax('_index_ajax', [
                            'models' => $sort->fetchModels($project->getUserId(), $type_sort_id),
                        ]),
                    ];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;

                } else {

                    $response = [
                        'renderAjax' => $this->renderAjax('_index_ajax', [
                            'models' => Projects::findAll(['user_id' => $project->getUserId()]),
                        ]),
                    ];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
        return false;
    }


    /**
     * @param $id
     * @return array|bool
     */
    public function actionShowAllInformation ($id)
    {
        $project = Projects::findOne($id);

        if(Yii::$app->request->isAjax) {

            $response = [
                'renderAjax' => $this->renderAjax('all-information', ['project' => $project]),
                'project' => $project,
            ];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @param $id
     * @return array|bool
     */
    public function actionShowRoadmap ($id)
    {
        $project = Projects::findOne($id);
        $roadmaps = [];

        foreach ($project->segments as $i => $segment){
            $roadmaps[$i] = new Roadmap($segment->id);
        }

        if(Yii::$app->request->isAjax) {

            $response = [
                'renderAjax' => $this->renderAjax('roadmap', ['roadmaps' => $roadmaps]),
                'project' => $project,
            ];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @param $id
     * @return array|bool
     */
    public function actionResult ($id)
    {
        $project = Projects::findOne($id);
        $segments = Segments::find()->where(['project_id' => $id])->all();

        if(Yii::$app->request->isAjax) {

            $response = [
                'renderAjax' => $this->renderAjax('result', ['project' => $project, 'segments' => $segments]),
                'project' => $project,
            ];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @param $id
     * @return array|bool
     */
    public function actionReport ($id)
    {
        $project = Projects::findOne($id);
        $segments = Segments::findAll(['project_id' => $id]);

        foreach ($segments as $s => $segment) {

            $segment->propertyContainer->addProperty('title', 'Сегмент ' . ($s+1));

            foreach ($segment->problems as $p => $problem) {

                $problem->propertyContainer->addProperty('title', 'ГПС ' . ($s+1) . '.' . ($p+1));

                foreach ($problem->gcps as $g => $gcp) {

                    $gcp->propertyContainer->addProperty('title', 'ГЦП ' . ($s+1) . '.' . ($p+1) . '.' . ($g+1));

                    foreach ($gcp->mvps as $m => $mvp) {

                        $mvp->propertyContainer->addProperty('title', 'MVP ' . ($s+1) . '.' . ($p+1) . '.' . ($g+1) . '.' . ($m+1));
                    }
                }
            }
        }

        if(Yii::$app->request->isAjax) {

            $response = [
                'renderAjax' => $this->renderAjax('report', ['segments' => $segments]),
                'project' => $project,
            ];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @param $id
     * @return string
     */
    public function actionResultExport($id)
    {

        $segments = Segments::find()->where(['project_id' => $id])->with(['confirm', 'problems'])->all();

        $businessModels = [];

        foreach ($segments as $i => $segment) {

            if ($segment->confirm && $segment->problems) {

                $problems = Problems::find()->where(['segment_id' => $segment->id])->with(['gcps'])->all();

                foreach ($problems as $problem) {

                    if ($segment->confirm && $segment->problems && $problem->gcps) {

                        $gcps = Gcps::find()->where(['problem_id' => $problem->id])->with(['mvps'])->all();

                        foreach ($gcps as $gcp) {

                            if ($segment->confirm && $segment->problems && $problem->gcps && $gcp->mvps) {

                                $mvps = Mvps::find()->where(['gcp_id' => $gcp->id])->with(['businessModel'])->all();

                                foreach ($mvps as $mvp) {

                                    if ($segment->confirm && $segment->problems && $problem->gcps && $gcp->mvps && $mvp->businessModel) {

                                        $businessModels[] = $mvp->businessModel;
                                    }

                                    if ((empty($mvp->confirm) && empty($mvp->businessModel))
                                        || (empty($mvp->confirm) || empty($mvp->businessModel))) {

                                        $businessModel = new BusinessModel();
                                        $businessModel->mvp_id = $mvp->id;
                                        $businessModel->gcp_id = $gcp->id;
                                        $businessModel->problem_id = $problem->id;
                                        $businessModel->segment_id = $segment->id;
                                        $businessModels[] = $businessModel;
                                    }
                                }
                            }
                            if ((empty($gcp->confirm) && empty($gcp->mvps))
                                || (empty($gcp->confirm) || empty($gcp->mvps))) {

                                $businessModel = new BusinessModel();
                                $businessModel->gcp_id = $gcp->id;
                                $businessModel->problem_id = $problem->id;
                                $businessModel->segment_id = $segment->id;
                                $businessModels[] = $businessModel;
                            }
                        }
                    }
                    if ((empty($problem->confirm) && empty($problem->gcps))
                        || (empty($problem->confirm) || empty($problem->gcps))) {

                        $businessModel = new BusinessModel();
                        $businessModel->problem_id = $problem->id;
                        $businessModel->segment_id = $segment->id;
                        $businessModels[] = $businessModel;
                    }
                }

            }
            if ((empty($segment->confirm) || empty($segment->problems))
                || (empty($segment->confirm) && empty($segment->problems))) {

                $businessModel = new BusinessModel();
                $businessModel->segment_id = $segment->id;
                $businessModels[] = $businessModel;
            }
        }


        //Добавление нумерации
        $numberSegment = 0;
        foreach ($businessModels as $k => $businessModel) {

            if ($businessModels[$k]->segment->id !== $businessModels[$k - 1]->segment->id) {
                //Добавление номера сегмента
                $numberSegment++;
                $businessModels[$k]->segment->name = 'Сегмент ' . $numberSegment . ': ' . $businessModels[$k]->segment->name;

                if ($businessModels[$k]->problem->title !== '' && $businessModels[$k]->problem) {
                    //Добавление номера ГПС
                    $numberProblem = explode('ГПС ', $businessModels[$k]->problem->title)[1];
                    $businessModels[$k]->problem->title = 'ГПС ' . $numberSegment . '.' . $numberProblem;

                    if ($businessModels[$k]->problem->gcps) {
                        //Добавление номера ГПС
                        $numberGcp = explode('ГЦП ', $businessModels[$k]->gcp->title)[1];
                        $businessModels[$k]->gcp->title = 'ГЦП ' . $numberSegment . '.' . $numberProblem . '.' . $numberGcp;

                        if ($businessModels[$k]->gcp->mvps) {
                            //Добавление номера MVP
                            $numberMvp = explode('MVP ', $businessModels[$k]->mvp->title)[1];
                            $businessModels[$k]->mvp->title = 'MVP ' . $numberSegment . '.' . $numberProblem . '.' . $numberGcp . '.' . $numberMvp;
                        }
                    }
                }

            } else {
                //Добавление номера сегмента
                $businessModels[$k]->segment->name = $businessModels[$k - 1]->segment->name;

                if ($businessModels[$k]->problem->title !== '' && $businessModels[$k]->problem) {
                    //Добавление номера ГПС
                    $numberProblem = explode('ГПС ', $businessModels[$k]->problem->title)[1];
                    $businessModels[$k]->problem->title = 'ГПС ' . $numberSegment . '.' . $numberProblem;

                    if ($businessModels[$k]->problem->gcps) {
                        //Добавление номера ГПС
                        $numberGcp = explode('ГЦП ', $businessModels[$k]->gcp->title)[1];
                        $businessModels[$k]->gcp->title = 'ГЦП ' . $numberSegment . '.' . $numberProblem . '.' . $numberGcp;

                        if ($businessModels[$k]->gcp->mvps) {
                            //Добавление номера MVP
                            $numberMvp = explode('MVP ', $businessModels[$k]->mvp->title)[1];
                            $businessModels[$k]->mvp->title = 'MVP ' . $numberSegment . '.' . $numberProblem . '.' . $numberGcp . '.' . $numberMvp;
                        }
                    }
                }
            }
        }

        // Отслеживаем совпадения в столбцах
        foreach ($businessModels as $k => $businessModel) {

            if ($businessModels[$k]->problem->gcps) {

                foreach ($businessModels[$k]->problem->gcps as $gcp) {
                    //Если id следующего ГЦП равно id предыдущего, то выполняем следующее
                    if ($businessModels[$k + 1]->gcp->id === $businessModels[$k]->gcp->id) {

                        $businessModels[$k + 1]->gcp->title = '';
                        $businessModels[$k + 1]->gcp->created_at = null;
                        $businessModels[$k + 1]->gcp->time_confirm = null;
                    }
                }
            }

            if ($businessModels[$k]->segment->problems) {

                foreach ($businessModels[$k]->segment->problems as $problem) {
                    //Если id следующего ГПС равно id предыдущего, то выполняем следующее
                    if ($businessModels[$k + 1]->problem->id === $businessModels[$k]->problem->id) {

                        $businessModels[$k + 1]->problem->title = '';
                        $businessModels[$k + 1]->problem->created_at = null;
                        $businessModels[$k + 1]->problem->time_confirm = null;
                    }
                }
            }
        }


        $project = Projects::findOne($id);
        $project_filename = str_replace(' ', '_', $project->project_name);
        $dataProvider = new ArrayDataProvider(['allModels' => $businessModels, 'pagination' => false, 'sort' => false]);


        return $this->render('result-export',[
            'dataProvider' => $dataProvider,
            'project' => $project,
            'project_filename' => $project_filename,
        ]);

    }


    /**
     * @param $id
     * @return string
     */
    /*public function actionReportTest ($id) {

        $segments = Segments::find()->where(['project_id' => $id])->with(['confirm', 'problems'])->all();

        $statModels = [];

        foreach ($segments as $s => $segment) {

            if (empty($segment->problems)) {

                $newProblem = new Problems();
                $newProblem->segment_id = $segment->id;
                $newProblem->project_id = $id;
                $newProblem->description = 'У данного сегмента отсутствуют дальнейшие этапы';
                $statModels[] = $newProblem;
            }

            if ($segment->confirm && $segment->problems) {

                $problems = Problems::find()->where(['segment_id' => $segment->id])->with(['gcps'])->all();

                foreach ($problems as $p => $problem) {

                    $problem->description = 'ГПС ' . ($s+1) . '.' . ($p+1) . ': ' . $problem->description;

                    $statModels[] = $problem;

                    if ($segment->confirm && $segment->problems && $problem->gcps) {

                        $gcps = Gcps::find()->where(['problem_id' => $problem->id])->with(['mvps'])->all();

                        foreach ($gcps as $g => $gcp) {

                            $gcp->description = 'ГЦП ' . ($s+1) . '.' . ($p+1) . '.' . ($g+1) . ': ' . $gcp->description;

                            $statModels[] = $gcp;

                            if ($segment->confirm && $segment->problems && $problem->gcps && $gcp->mvps){

                                $mvps = Mvps::find()->where(['gcp_id' => $gcp->id])->with(['businessModel'])->all();

                                foreach ($mvps as $m => $mvp) {

                                    $mvp->description = 'ГMVP ' . ($s+1) . '.' . ($p+1) . '.' . ($g+1) . '.' . ($m+1) . ': ' . $mvp->description;

                                    $statModels[] = $mvp;
                                }
                            }
                        }
                    }
                }
            }
        }


        $dataProvider = new ArrayDataProvider([
            'allModels' => $statModels,
            //'pagination' => [
                //'pageSize' => 100,
            //],
            'pagination' => false,
            'sort' => false,
        ]);

        $project = Projects::findOne($id);
        $project_filename = str_replace(' ', '_', $project->project_name);

        return $this->render('report-test', [
                'dataProvider' => $dataProvider,
                'project' => $project,
                'project_filename' => $project_filename,
            ]
        );
    }*/


    /**
     * @param $id
     * @return mixed
     * @throws MpdfException
     * @throws CrossReferenceException
     * @throws PdfParserException
     * @throws PdfTypeException
     * @throws InvalidConfigException
     */
    public function actionMpdfProject($id) {

        $model = Projects::findOne($id);

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('mpdf_project', ['project' => $model]);

        $destination = Pdf::DEST_BROWSER;
        //$destination = Pdf::DEST_DOWNLOAD;

        $filename = 'Презентация проекта «'.$model->project_name .'».pdf';

        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            //'format' => Pdf::FORMAT_TABLOID,
            // portrait orientation
            //'orientation' => Pdf::ORIENT_LANDSCAPE,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => $destination,
            'filename' => $filename,
            'content' => $content,
            'cssFile' => '@app/web/css/mpdf-hypothesis-style.css',
            'marginFooter' => 5,
            // call mPDF methods on the fly
            'methods' => [
                'SetTitle' => [$model->project_name],
                'SetHeader' => ['<div style="color: #3c3c3c;">Проект «'.$model->project_name.'»</div>||<div style="color: #3c3c3c;">Сгенерировано: ' . date("H:i d.m.Y") . '</div>'],
                'SetFooter' => ['<div style="color: #3c3c3c;">Страница {PAGENO}</div>'],
                //'SetSubject' => 'Generating PDF files via yii2-mpdf extension has never been easy',
                //'SetAuthor' => 'Kartik Visweswaran',
                //'SetCreator' => 'Kartik Visweswaran',
                //'SetKeywords' => 'Krajee, Yii2, Export, PDF, MPDF, Output, Privacy, Policy, yii2-mpdf',
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }


    /**
     * @param $id
     * @return mixed
     * @throws MpdfException
     * @throws CrossReferenceException
     * @throws PdfParserException
     * @throws PdfTypeException
     * @throws InvalidConfigException
     */
    public function actionMpdfBusinessModel($id) {

        $model = BusinessModel::findOne($id);

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('/business-model/viewpdf', ['model' => $model]);

        $destination = Pdf::DEST_BROWSER;
        //$destination = Pdf::DEST_DOWNLOAD;

        $filename = 'business-model-'. $model->id .'.pdf';

        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            //'format' => Pdf::FORMAT_TABLOID,
            // portrait orientation
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            //'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => $destination,
            'filename' => $filename,
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            //'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssFile' => '@app/web/css/style.css',
            // any css to be embedded if required
            'cssInline' => '.business-model-view-export {color: #3c3c3c;};',
            'marginFooter' => 5,
            // call mPDF methods on the fly
            'methods' => [
                'SetTitle' => ['Бизнес-модель PDF'],
                'SetHeader' => ['<div style="color: #3c3c3c;">Бизнес-модель для проекта «'.$model->project->project_name.'»</div>||<div style="color: #3c3c3c;">Сгенерировано: ' . date("H:i d.m.Y") . '</div>'],
                'SetFooter' => ['<div style="color: #3c3c3c;">Страница {PAGENO}</div>'],
                //'SetSubject' => 'Generating PDF files via yii2-mpdf extension has never been easy',
                //'SetAuthor' => 'Kartik Visweswaran',
                //'SetCreator' => 'Kartik Visweswaran',
                //'SetKeywords' => 'Krajee, Yii2, Export, PDF, MPDF, Output, Privacy, Policy, yii2-mpdf',
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }


    /**
     * @param $id
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDeleteAuthor($id)
    {
        $model = Authors::findOne($id);
        $project = Projects::find()->where(['id' => $model->project_id])->one();

        if ($model){
            $project->updated_at = time();
            $model->delete();
        }
    }


    /**
     * @param $id
     * @return bool
     * @throws NotFoundHttpException
     * @throws Throwable
     * @throws ErrorException
     * @throws StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->isAjax) {

            if ($model->deleteStage())
                return true;
        }
        return false;
    }


    /**
     * Finds the Projects model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Projects the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Projects::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
