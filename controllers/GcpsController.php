<?php

namespace app\controllers;

use app\models\ClientSettings;
use app\models\CommunicationResponse;
use app\models\CommunicationTypes;
use app\models\ConfirmProblem;
use app\models\ConfirmSegment;
use app\models\forms\CacheForm;
use app\models\forms\FormCreateGcp;
use app\models\Gcps;
use app\models\Problems;
use app\models\Projects;
use app\models\Segments;
use app\models\User;
use app\models\UserAccessToProjects;
use kartik\mpdf\Pdf;
use Mpdf\MpdfException;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;
use Throwable;
use Yii;
use yii\base\ErrorException;
use yii\base\InvalidConfigException;
use yii\db\StaleObjectException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Контроллер с методами для создания, редактирования
 * и получения информации по ценностным предложениям
 *
 * Class GcpsController
 * @package app\controllers
 */
class GcpsController extends AppUserPartController
{

    /**
     * @param $action
     * @return bool
     * @throws HttpException
     */
    public function beforeAction($action): bool
    {
        $currentUser = User::findOne(Yii::$app->user->getId());
        $currentClientUser = $currentUser->clientUser;

        if (in_array($action->id, ['update', 'delete'])){

            $model = Gcps::findOne((int)Yii::$app->request->get('id'));
            $project = Projects::findOne($model->getProjectId());

            /*Ограничение доступа к проэктам пользователя*/
            if ($project->getUserId() === $currentUser->getId()){
                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;
                return parent::beforeAction($action);
            }

            throw new HttpException(200, 'У Вас нет доступа по данному адресу.');

        }elseif ($action->id === 'create'){

            $confirmProblem = ConfirmProblem::findOne((int)Yii::$app->request->get('id'));
            $problem = Problems::findOne($confirmProblem->getProblemId());
            $project = Projects::findOne($problem->getProjectId());

            /*Ограничение доступа к проэктам пользователя*/
            if ($project->getUserId() === $currentUser->getId()){
                return parent::beforeAction($action);
            }

            throw new HttpException(200, 'У Вас нет доступа по данному адресу.');

        }elseif (in_array($action->id, ['index', 'mpdf-table-gcps'])){

            $confirmProblem = ConfirmProblem::findOne((int)Yii::$app->request->get('id'));
            $problem = Problems::findOne($confirmProblem->getProblemId());
            $project = Projects::findOne($problem->getProjectId());

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->getUserId() === $currentUser->getId())){

                return parent::beforeAction($action);

            } elseif (User::isUserAdmin($currentUser->getUsername()) && $project->user->getIdAdmin() === $currentUser->getId()) {

                return parent::beforeAction($action);

            } elseif (User::isUserMainAdmin($currentUser->getUsername()) || User::isUserDev($currentUser->getUsername()) || User::isUserAdminCompany($currentUser->getUsername())) {

                $modelClientUser = $project->user->clientUser;

                if ($currentClientUser->getClientId() === $modelClientUser->getClientId()) {
                    return parent::beforeAction($action);
                }

                if ($modelClientUser->client->settings->getAccessAdmin() === ClientSettings::ACCESS_ADMIN_TRUE) {
                    return parent::beforeAction($action);
                }

                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');

            } elseif (User::isUserExpert($currentUser->getUsername())) {

                $expert = User::findOne(Yii::$app->user->getId());

                /** @var UserAccessToProjects $userAccessToProject */
                $userAccessToProject = $expert->findUserAccessToProject($project->getId());

                if ($userAccessToProject) {

                    if ($userAccessToProject->getCommunicationType() === CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE) {

                        $responsiveCommunication = $userAccessToProject->communication->responsiveCommunication;

                        if ($responsiveCommunication) {

                            if ($responsiveCommunication->communicationResponse->getAnswer() === CommunicationResponse::POSITIVE_RESPONSE) {

                                return parent::beforeAction($action);
                            }

                        } elseif (time() < $userAccessToProject->getDateStop()) {

                            return parent::beforeAction($action);
                        }
                        throw new HttpException(200, 'У Вас нет доступа по данному адресу.');

                    } elseif ($userAccessToProject->getCommunicationType() === CommunicationTypes::MAIN_ADMIN_APPOINTS_EXPERT_PROJECT) {

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

        }else{
            return parent::beforeAction($action);
        }

    }


    /**
     * @param int $id
     * @return string|Response
     */
    public function actionIndex(int $id)
    {
        $models = Gcps::findAll(['basic_confirm_id' => $id]);
        if (!$models) {
            return $this->redirect(['/gcps/instruction', 'id' => $id]);
        }

        $confirmProblem = ConfirmProblem::findOne($id);
        $problem = Problems::findOne($confirmProblem->getProblemId());
        $confirmSegment = ConfirmSegment::findOne($problem->getBasicConfirmId());
        $segment = Segments::findOne($confirmSegment->getSegmentId());
        $project = Projects::findOne($segment->getProjectId());

        return $this->render('index', [
            'models' => $models,
            'confirmProblem' => $confirmProblem,
            'problem' => $problem,
            'confirmSegment' => $confirmSegment,
            'segment' => $segment,
            'project' => $project,
        ]);
    }


    /**
     * @param int $id
     * @return string|Response
     */
    public function actionInstruction (int $id)
    {
        $models = Gcps::find()->where(['basic_confirm_id' => $id])->all();
        if ($models) {
            return $this->redirect(['/gcps/index', 'id' => $id]);
        }

        return $this->render('index_first', [
            'confirmProblem' => ConfirmProblem::findOne($id),
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
     * @param int $id
     * @return void
     */
    public function actionSaveCacheCreationForm(int $id): void
    {
        $confirmProblem = ConfirmProblem::findOne($id);
        $cachePath = FormCreateGcp::getCachePath($confirmProblem->hypothesis);
        $cacheName = 'formCreateHypothesisCache';

        if(Yii::$app->request->isAjax) {

            $cache = new CacheForm();
            $cache->setCache($cachePath, $cacheName);
        }
    }


    /**
     * @param int $id
     * @return array|bool
     * @throws NotFoundHttpException
     * @throws ErrorException
     */
    public function actionCreate(int $id)
    {
        if (Yii::$app->request->isAjax) {

            $confirmProblem = ConfirmProblem::findOne($id);
            $model = new FormCreateGcp($confirmProblem->hypothesis);
            $model->basic_confirm_id = $id;

            if ($model->load(Yii::$app->request->post())) {
                if ($model->create()) {

                    $response = [
                        'count' => Gcps::find()->where(['basic_confirm_id' => $id])->count(),
                        'renderAjax' => $this->renderAjax('_index_ajax', [
                            'models' => Gcps::find()->where(['basic_confirm_id' => $id])->all(),
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
     * @param int $id
     * @return array|bool
     * @throws NotFoundHttpException
     */
    public function actionUpdate(int $id)
    {
        if(Yii::$app->request->isAjax) {

            $model = $this->findModel($id);
            $confirmProblem = ConfirmProblem::findOne($model->getConfirmProblemId());

            if ($model->load(Yii::$app->request->post())) {
                if ($model->save()){

                    $response = [
                        'renderAjax' => $this->renderAjax('_index_ajax', [
                            'models' => Gcps::findAll(['basic_confirm_id' => $confirmProblem->getId()]),
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
     * Включить разрешение на экспертизу
     *
     * @param int $id
     * @return array|bool
     */
    public function actionEnableExpertise(int $id)
    {
        if (Yii::$app->request->isAjax) {

            $gcp = Gcps::findOne($id);
            $gcp->setEnableExpertise();
            $confirmProblem = ConfirmProblem::findOne($gcp->getConfirmProblemId());

            if ($gcp->save()) {

                $response = [
                    'renderAjax' => $this->renderAjax('_index_ajax', [
                        'models' => Gcps::findAll(['basic_confirm_id' => $confirmProblem->getId()]),
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
     * @param int $id
     * @return array|bool
     * @throws NotFoundHttpException
     */
    public function actionGetHypothesisToUpdate(int $id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->isAjax) {

            $response = [
                'model' => $model,
                'renderAjax' => $this->renderAjax('update', ['model' => $model]),
            ];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @param int $id
     * @return mixed
     * @throws MpdfException
     * @throws CrossReferenceException
     * @throws PdfParserException
     * @throws PdfTypeException
     * @throws InvalidConfigException
     */
    public function actionMpdfTableGcps(int $id) {

        $confirm_problem = ConfirmProblem::findOne($id);
        $problem_description = mb_substr($confirm_problem->problem->getDescription(), 0, 50).'...';
        $models = $confirm_problem->gcps;

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('mpdf_table_gcps', ['models' => $models]);

        $destination = Pdf::DEST_BROWSER;
        //$destination = Pdf::DEST_DOWNLOAD;

        $filename = 'ЦП для проблемы «'.$problem_description.'».pdf';

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
            'cssFile' => '@app/web/css/mpdf-index-table-hypothesis-style.css',
            'marginFooter' => 5,
            // call mPDF methods on the fly
            'methods' => [
                'SetTitle' => ['ЦП для проблемы «'.$problem_description.'»'],
                'SetHeader' => ['<div style="color: #3c3c3c;">ЦП для проблемы «'.$problem_description.'»</div>||<div style="color: #3c3c3c;">Сгенерировано: ' . date("H:i d.m.Y") . '</div>'],
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
     * @param int $id
     * @return bool
     * @throws ErrorException
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionDelete(int $id): bool
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->isAjax && $model->deleteStage()) {
            return true;
        }
        return false;
    }

    /**
     * @param int $id
     * @return Gcps|null
     * @throws NotFoundHttpException
     */
    protected function findModel(int $id): ?Gcps
    {
        if (($model = Gcps::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
