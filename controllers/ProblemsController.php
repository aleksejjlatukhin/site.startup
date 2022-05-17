<?php

namespace app\controllers;

use app\models\ClientSettings;
use app\models\ClientUser;
use app\models\CommunicationResponse;
use app\models\CommunicationTypes;
use app\models\ConfirmSegment;
use app\models\ExpectedResultsInterviewConfirmProblem;
use app\models\forms\CacheForm;
use app\models\forms\FormUpdateProblem;
use app\models\InterviewConfirmSegment;
use app\models\forms\FormCreateProblem;
use app\models\Projects;
use app\models\RespondsSegment;
use app\models\Segments;
use app\models\User;
use kartik\mpdf\Pdf;
use Mpdf\MpdfException;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;
use Throwable;
use Yii;
use app\models\Problems;
use yii\base\ErrorException;
use yii\base\InvalidConfigException;
use yii\db\StaleObjectException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Контроллер с методами для создания,
 * редактирования и получения информации по проблемам сегмента
 *
 * Class ProblemsController
 * @package app\controllers
 */
class ProblemsController extends AppUserPartController
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

            $model = Problems::findOne(Yii::$app->request->get('id'));
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

            $confirmSegment = ConfirmSegment::findOne(Yii::$app->request->get('id'));
            $segment = Segments::findOne($confirmSegment->getSegmentId());
            $project = Projects::findOne($segment->getProjectId());

            /*Ограничение доступа к проэктам пользователя*/

            if ($project->getUserId() == $currentUser->getId()){

                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['index']) || in_array($action->id, ['mpdf-table-problems'])){

            $confirmSegment = ConfirmSegment::findOne(Yii::$app->request->get('id'));
            $segment = Segments::findOne($confirmSegment->getSegmentId());
            $project = Projects::findOne($segment->getProjectId());

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

        $confirmSegment = ConfirmSegment::findOne($id);
        $segment = Segments::findOne($confirmSegment->segmentId);
        $project = Projects::findOne($segment->projectId);
        $models = Problems::findAll(['basic_confirm_id' => $id]);
        $formModel = new FormCreateProblem($segment);

        if (!$models) return $this->redirect(['/problems/instruction', 'id' => $id]);

        return $this->render('index', [
            'models' => $models,
            'confirmSegment' => $confirmSegment,
            'segment' => $segment,
            'project' => $project,
            'formModel' => $formModel
        ]);
    }


    /**
     * @param $id
     * @return string
     */
    public function actionInstruction ($id)
    {
        $models = Problems::findAll(['basic_confirm_id' => $id]);
        if ($models) return $this->redirect(['/problems/index', 'id' => $id]);

        $confirmSegment = ConfirmSegment::findOne($id);
        $formModel = new FormCreateProblem($confirmSegment->hypothesis);

        return $this->render('index_first', [
            'confirmSegment' => $confirmSegment,
            'formModel' => $formModel
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
        $confirmSegment = ConfirmSegment::findOne($id);
        $cachePath = FormCreateProblem::getCachePath($confirmSegment->hypothesis);
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
        $confirmSegment = ConfirmSegment::findOne($id);
        $model = new FormCreateProblem($confirmSegment->hypothesis);
        $model->basic_confirm_id = $id;

        if ($model->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax) {

                if ($model->create()){

                    $response = [
                        'count' => Problems::find()->where(['basic_confirm_id' => $id])->count(),
                        'renderAjax' => $this->renderAjax('_index_ajax', [
                            'models' => Problems::findAll(['basic_confirm_id' => $id]),
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
        $formUpdate = new FormUpdateProblem($model);

        //Выбор респондентов, которые являются представителями сегмента
        $responds = RespondsSegment::find()->with('interview')
            ->leftJoin('interview_confirm_segment', '`interview_confirm_segment`.`respond_id` = `responds_segment`.`id`')
            ->where(['confirm_id' => $model->confirmSegmentId, 'interview_confirm_segment.status' => '1'])->all();

        if(Yii::$app->request->isAjax) {

            $response = [
                'model' => $model,
                'renderAjax' => $this->renderAjax('update', [
                    'model' => $model,
                    'responds' => $responds,
                    'formUpdate' => $formUpdate
                ]),
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
        $confirmSegment = ConfirmSegment::findOne($model->getConfirmSegmentId());
        $form = new FormUpdateProblem($model);

        if ($form->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax) {

                if ($form->update()) {

                    $response = [
                        'renderAjax' => $this->renderAjax('_index_ajax', [
                            'models' => Problems::findAll(['basic_confirm_id' => $confirmSegment->id]),
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

            $problem = Problems::findOne($id);
            $problem->setEnableExpertise();
            $confirmSegment = ConfirmSegment::findOne($problem->getConfirmSegmentId());

            if ($problem->save()) {

                $response = [
                    'renderAjax' => $this->renderAjax('_index_ajax', [
                        'models' => Problems::findAll(['basic_confirm_id' => $confirmSegment->id]),
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
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionDeleteExpectedResultsInterview($id)
    {
        $model = ExpectedResultsInterviewConfirmProblem::findOne($id);
        if ($model){
            $model->delete();
        }
    }


    /**
     * @param $id
     * @return array|bool
     */
    public function actionGetInterviewRespond ($id)
    {
        $respond = RespondsSegment::findOne($id);
        $interview = InterviewConfirmSegment::findOne(['respond_id' => $id]);

        if(Yii::$app->request->isAjax) {

            $response = [
                'respond' => $respond,
                'renderAjax' => $this->renderAjax('data_respond', [
                    'respond' => $respond,
                    'interview' => $interview
                ]),
            ];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return  false;
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
    public function actionMpdfTableProblems ($id) {

        $confirmSegment = ConfirmSegment::findOne($id);
        $segment = $confirmSegment->segment;
        $models = $confirmSegment->problems;

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('mpdf_table_problems', ['models' => $models]);

        $destination = Pdf::DEST_BROWSER;
        //$destination = Pdf::DEST_DOWNLOAD;

        $filename = 'Проблемы сегмента «'.$segment->name .'».pdf';

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
                'SetTitle' => ['Проблемы сегмента «'.$segment->name .'»'],
                'SetHeader' => ['<div style="color: #3c3c3c;">Проблемы сегмента «'.$segment->name.'»</div>||<div style="color: #3c3c3c;">Сгенерировано: ' . date("H:i d.m.Y") . '</div>'],
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
     * Finds the Problems model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Problems the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Problems::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
