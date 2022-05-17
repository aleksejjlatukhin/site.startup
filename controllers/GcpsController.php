<?php

namespace app\controllers;

use app\models\ClientSettings;
use app\models\ClientUser;
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
    public function beforeAction($action)
    {
        $currentUser = User::findOne(Yii::$app->user->getId());
        /** @var ClientUser $currentClientUser */
        $currentClientUser = $currentUser->clientUser;

        if (in_array($action->id, ['update']) || in_array($action->id, ['delete'])){

            $model = Gcps::findOne(Yii::$app->request->get('id'));
            $project = Projects::findOne($model->getProjectId());

            /*Ограничение доступа к проэктам пользователя*/
            if ($project->getUserId() == $currentUser->getId()){
                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;
                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){

            $confirmProblem = ConfirmProblem::findOne(Yii::$app->request->get('id'));
            $problem = Problems::findOne($confirmProblem->getProblemId());
            $project = Projects::findOne($problem->getProjectId());

            /*Ограничение доступа к проэктам пользователя*/
            if ($project->getUserId() == $currentUser->getId()){
                return parent::beforeAction($action);
            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['index']) || in_array($action->id, ['mpdf-table-gcps'])){

            $confirmProblem = ConfirmProblem::findOne(Yii::$app->request->get('id'));
            $problem = Problems::findOne($confirmProblem->getProblemId());
            $project = Projects::findOne($problem->getProjectId());

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
    public function actionIndex($id)
    {
        $models = Gcps::find()->where(['basic_confirm_id' => $id])->all();
        if (!$models) return $this->redirect(['/gcps/instruction', 'id' => $id]);

        $confirmProblem = ConfirmProblem::findOne($id);
        $problem = Problems::findOne($confirmProblem->problemId);
        $confirmSegment = ConfirmSegment::findOne($problem->basic_confirm_id);
        $segment = Segments::findOne($confirmSegment->segmentId);
        $project = Projects::findOne($segment->projectId);

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
     * @param $id
     * @return string
     */
    public function actionInstruction ($id)
    {
        $models = Gcps::find()->where(['basic_confirm_id' => $id])->all();
        if ($models) return $this->redirect(['/gcps/index', 'id' => $id]);

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
     * @param $id
     */
    public function actionSaveCacheCreationForm($id)
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
     * @param $id
     * @return array|bool
     * @throws NotFoundHttpException
     * @throws ErrorException
     */
    public function actionCreate($id)
    {
        $confirmProblem = ConfirmProblem::findOne($id);
        $model = new FormCreateGcp($confirmProblem->hypothesis);
        $model->basic_confirm_id = $id;

        if ($model->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax) {

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
     * @param $id
     * @return array|bool
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $confirmProblem = ConfirmProblem::findOne($model->getConfirmProblemId());

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model->save()){

                    $response = [
                        'renderAjax' => $this->renderAjax('_index_ajax', [
                            'models' => Gcps::find()->where(['basic_confirm_id' => $confirmProblem->id])->all(),
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
     */
    public function actionEnableExpertise($id)
    {
        if (Yii::$app->request->isAjax) {

            $gcp = Gcps::findOne($id);
            $gcp->setEnableExpertise();
            $confirmProblem = ConfirmProblem::findOne($gcp->getConfirmProblemId());

            if ($gcp->save()) {

                $response = [
                    'renderAjax' => $this->renderAjax('_index_ajax', [
                        'models' => Gcps::find()->where(['basic_confirm_id' => $confirmProblem->id])->all(),
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
     */
    public function actionGetHypothesisToUpdate ($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->isAjax) {

            $response = [
                'model' => $model,
                'renderAjax' => $this->renderAjax('update', ['model' => $model,]),
            ];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
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
    public function actionMpdfTableGcps ($id) {

        $confirm_problem = ConfirmProblem::findOne($id);
        $problem_description = mb_substr($confirm_problem->problem->description, 0, 50).'...';
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

            if ($model->deleteStage()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Finds the Gcp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Gcps the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Gcps::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
