<?php

namespace app\controllers;

use app\models\ClientSettings;
use app\models\ClientUser;
use app\models\CommunicationResponse;
use app\models\CommunicationTypes;
use app\models\ConfirmGcp;
use app\models\ConfirmMvp;
use app\models\ConfirmProblem;
use app\models\ConfirmSegment;
use app\models\forms\CacheForm;
use app\models\forms\FormCreateBusinessModel;
use app\models\Gcps;
use app\models\Problems;
use app\models\Mvps;
use app\models\Projects;
use app\models\Segments;
use app\models\User;
use Mpdf\MpdfException;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;
use Yii;
use app\models\BusinessModel;
use yii\base\ErrorException;
use yii\base\InvalidConfigException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use kartik\mpdf\Pdf;
use yii\web\Response;

/**
 * Контроллер с методами для создания, редактирования
 * и получения информации по бизнес-модели
 *
 * Class BusinessModelController
 * @package app\controllers
 */
class BusinessModelController extends AppUserPartController
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

        if (in_array($action->id, ['index'])){

            $confirmMvp = ConfirmMvp::findOne(Yii::$app->request->get('id'));
            $mvp = Mvps::findOne($confirmMvp->getMvpId());
            /** @var Projects $project*/
            $project = $mvp->project;

            /*Ограничение доступа к проэктам пользователя*/

            if (($project->getUserId() == $currentUser->getId())){

                return parent::beforeAction($action);

            } elseif (User::isUserAdmin($currentUser->getUsername()) && $project->user->getIdAdmin() == $currentUser->getId()) {

                return parent::beforeAction($action);

            } elseif (User::isUserMainAdmin($currentUser->getUsername()) || User::isUserDev($currentUser->getUsername()) || User::isUserAdminCompany($currentUser->getUsername())) {

                /** @var ClientUser $modelClientUser */
                $modelClientUser = $project->user->clientUser;

                if ($currentClientUser->getClientId() == $modelClientUser->getClientId()) {
                    return parent::beforeAction($action);
                } elseif ($modelClientUser->findClient()->findSettings()->getAccessAdmin() == ClientSettings::ACCESS_ADMIN_TRUE) {
                    return parent::beforeAction($action);
                } else {
                    throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
                }

            } elseif (User::isUserExpert(Yii::$app->user->identity['username'])) {

                $expert = User::findOne(Yii::$app->user->id);

                $userAccessToProject = $expert->findUserAccessToProject($project->id);

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

        }elseif (in_array($action->id, ['update'])){

            $model = BusinessModel::findOne(Yii::$app->request->get('id'));
            /** @var Projects $project */
            $project = $model->project;

            /*Ограничение доступа к проэктам пользователя*/

            if ($project->getUserId() == $currentUser->getId()){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){

            $confirmMvp = ConfirmMvp::findOne(Yii::$app->request->get('id'));
            /** @var Projects $project */
            $project = $confirmMvp->mvp->project;

            /*Ограничение доступа к проэктам пользователя*/

            if ($project->getUserId() == $currentUser->getId()){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['mpdf-business-model'])){

            $model = BusinessModel::findOne(Yii::$app->request->get('id'));
            /** @var Projects $project */
            $project = $model->project;

            /*Ограничение доступа к проэктам пользователя*/

            if (($project->getUserId() == $currentUser->getId())){

                return parent::beforeAction($action);

            } elseif (User::isUserAdmin($currentUser->getUsername()) && $project->user->getIdAdmin() == $currentUser->getId()) {

                return parent::beforeAction($action);

            } elseif (User::isUserMainAdmin($currentUser->getUsername()) || User::isUserDev($currentUser->getUsername()) || User::isUserAdminCompany($currentUser->getUsername())) {

                /** @var ClientUser $modelClientUser */
                $modelClientUser = $project->user->clientUser;

                if ($currentClientUser->getClientId() == $modelClientUser->getClientId()) {
                    return parent::beforeAction($action);
                } elseif ($modelClientUser->findClient()->findSettings()->getAccessAdmin() == ClientSettings::ACCESS_ADMIN_TRUE) {
                    return parent::beforeAction($action);
                } else {
                    throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
                }

            } elseif (User::isUserExpert($currentUser->getUsername())) {

                $expert = User::findOne(Yii::$app->user->id);

                $userAccessToProject = $expert->findUserAccessToProject($project->id);

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

        }else{
            return parent::beforeAction($action);
        }

    }


    /**
     * @param $id
     * @return string
     */
    public function actionIndex ($id)
    {
        $model = BusinessModel::findOne(['basic_confirm_id' => $id]);
        if (!$model) return $this->redirect(['/business-model/instruction', 'id' => $id]);

        $confirmMvp = ConfirmMvp::findOne($id);
        $mvp = Mvps::findOne($confirmMvp->mvpId);
        $confirmGcp = ConfirmGcp::findOne($mvp->confirmGcpId);
        $gcp = Gcps::findOne($confirmGcp->gcpId);
        $confirmProblem = ConfirmProblem::findOne($gcp->confirmProblemId);
        $problem = Problems::findOne($confirmProblem->problemId);
        $confirmSegment = ConfirmSegment::findOne($problem->confirmSegmentId);
        $segment = Segments::findOne($confirmSegment->segmentId);
        $project = Projects::findOne($segment->projectId);

        return $this->render('index', [
            'model' => $model,
            'confirmMvp' => $confirmMvp,
            'mvp' => $mvp,
            'confirmGcp' => $confirmGcp,
            'gcp' => $gcp,
            'confirmProblem' => $confirmProblem,
            'problem' => $problem,
            'confirmSegment' => $confirmSegment,
            'segment' => $segment,
            'project' => $project,
        ]);
    }


    /**
     * @param $id
     * @return string
     */
    public function actionInstruction ($id)
    {
        $model = BusinessModel::findOne(['basic_confirm_id' => $id]);
        if ($model) return $this->redirect(['/business-model/index', 'id' => $id]);

        return $this->render('index_first', [
            'confirmMvp' => ConfirmMvp::findOne($id),
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
     * @return bool
     */
    public function actionSaveCacheCreationForm($id)
    {
        $confirmMvp = ConfirmMvp::findOne($id);
        $cachePath = FormCreateBusinessModel::getCachePath($confirmMvp->hypothesis);
        $cacheName = 'formCreateHypothesisCache';

        if(Yii::$app->request->isAjax) {

            $cache = new CacheForm();
            $cache->setCache($cachePath, $cacheName);
        }
        return false;
    }


    /**
     * @param $id
     * @return array|bool
     * @throws NotFoundHttpException
     * @throws ErrorException
     */
    public function actionCreate($id)
    {
        $confirmMvp = ConfirmMvp::findOne($id);
        $model = new FormCreateBusinessModel($confirmMvp->hypothesis);
        $model->basic_confirm_id = $id;

        $mvp = Mvps::findOne($confirmMvp->mvpId);
        $gcp = Gcps::findOne($mvp->gcpId);
        $segment = Segments::findOne($mvp->segmentId);

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($businessModel = $model->create()) {

                    $response = [
                        'success' => true,
                        'renderAjax' => $this->renderAjax('_index_ajax', [
                            'model' => $businessModel, 'segment' => $segment, 'gcp' => $gcp,
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
     * @throws NotFoundHttpException
     */
    public function actionGetHypothesisToUpdate ($id)
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
     * @param $id
     * @return array|bool
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $confirmMvp = ConfirmMvp::findOne($model->getConfirmMvpId());
        $gcp = $model->gcp;
        $segment = $model->segment;

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model->save()) {

                    $response = [
                        'renderAjax' => $this->renderAjax('_index_ajax', [
                            'model' => BusinessModel::findOne(['basic_confirm_id' => $confirmMvp->id]),
                            'segment' => $segment,
                            'gcp' => $gcp,
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
     * @param $id
     * @return array|bool
     * @throws NotFoundHttpException
     */
    public function actionEnableExpertise($id)
    {
        if (Yii::$app->request->isAjax) {

            $model = $this->findModel($id);
            $model->setEnableExpertise();
            $confirmMvp = ConfirmMvp::findOne($model->getConfirmMvpId());
            $gcp = $model->gcp;
            $segment = $model->segment;

            if ($model->save()) {

                $response = [
                    'renderAjax' => $this->renderAjax('_index_ajax', [
                        'model' => BusinessModel::findOne(['basic_confirm_id' => $confirmMvp->id]),
                        'segment' => $segment,
                        'gcp' => $gcp,
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
     * export in pdf
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws MpdfException
     * @throws CrossReferenceException
     * @throws PdfParserException
     * @throws PdfTypeException
     * @throws InvalidConfigException
     */
    public function actionMpdfBusinessModel($id) {

        $model = $this->findModel($id);

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
     * Finds the BusinessModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return BusinessModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BusinessModel::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }



}
