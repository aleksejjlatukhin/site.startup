<?php

namespace app\controllers;

use app\models\ClientSettings;
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
use app\models\UserAccessToProjects;
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
    public function beforeAction($action): bool
    {
        $currentUser = User::findOne(Yii::$app->user->getId());
        $currentClientUser = $currentUser->clientUser;

        if (in_array($action->id, ['update', 'delete'])){

            $model = Problems::findOne((int)Yii::$app->request->get('id'));
            $project = Projects::findOne($model->getProjectId());

            /*Ограничение доступа к проэктам пользователя*/

            if ($project->getUserId() === $currentUser->getId()){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }

            throw new HttpException(200, 'У Вас нет доступа по данному адресу.');

        }elseif ($action->id === 'create'){

            $confirmSegment = ConfirmSegment::findOne((int)Yii::$app->request->get('id'));
            $segment = Segments::findOne($confirmSegment->getSegmentId());
            $project = Projects::findOne($segment->getProjectId());

            /*Ограничение доступа к проэктам пользователя*/

            if ($project->getUserId() === $currentUser->getId()){

                return parent::beforeAction($action);

            }

            throw new HttpException(200, 'У Вас нет доступа по данному адресу.');

        }elseif (in_array($action->id, ['index', 'mpdf-table-problems'])){

            $confirmSegment = ConfirmSegment::findOne((int)Yii::$app->request->get('id'));
            $segment = Segments::findOne($confirmSegment->getSegmentId());
            $project = Projects::findOne($segment->getProjectId());

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

                            throw new HttpException(200, 'У Вас нет доступа по данному адресу.');

                        } else {

                            if (time() < $userAccessToProject->getDateStop()) {

                                return parent::beforeAction($action);

                            }

                            throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
                        }

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

        $confirmSegment = ConfirmSegment::findOne($id);
        $segment = Segments::findOne($confirmSegment->getSegmentId());
        $project = Projects::findOne($segment->getProjectId());
        $models = Problems::findAll(['basic_confirm_id' => $id]);
        $formModel = new FormCreateProblem($segment);

        if (!$models) {
            return $this->redirect(['/problems/instruction', 'id' => $id]);
        }

        return $this->render('index', [
            'models' => $models,
            'confirmSegment' => $confirmSegment,
            'segment' => $segment,
            'project' => $project,
            'formModel' => $formModel
        ]);
    }


    /**
     * @param int $id
     * @return string|Response
     */
    public function actionInstruction (int $id)
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
     * @param int $id
     */
    public function actionSaveCacheCreationForm(int $id): void
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
     * @param int $id
     * @return array|bool
     * @throws NotFoundHttpException
     * @throws ErrorException
     */
    public function actionCreate(int $id)
    {
        if (Yii::$app->request->isAjax) {

            $confirmSegment = ConfirmSegment::findOne($id);
            $model = new FormCreateProblem($confirmSegment->hypothesis);
            $model->basic_confirm_id = $id;

            if ($model->load(Yii::$app->request->post())) {
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
     * @param int $id
     * @return array|bool
     * @throws NotFoundHttpException
     */
    public function actionGetHypothesisToUpdate (int $id)
    {
        $model = $this->findModel($id);
        $formUpdate = new FormUpdateProblem($model);

        //Выбор респондентов, которые являются представителями сегмента
        $responds = RespondsSegment::find()->with('interview')
            ->leftJoin('interview_confirm_segment', '`interview_confirm_segment`.`respond_id` = `responds_segment`.`id`')
            ->where(['confirm_id' => $model->getConfirmSegmentId(), 'interview_confirm_segment.status' => '1'])->all();

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
     * @param int $id
     * @return array|bool
     * @throws NotFoundHttpException
     */
    public function actionUpdate(int $id)
    {
        if (Yii::$app->request->isAjax) {
            $model = $this->findModel($id);
            $confirmSegment = ConfirmSegment::findOne($model->getConfirmSegmentId());
            $form = new FormUpdateProblem($model);

            if ($form->load(Yii::$app->request->post())) {
                if ($form->update()) {
                    $response = [
                        'renderAjax' => $this->renderAjax('_index_ajax', [
                            'models' => Problems::findAll(['basic_confirm_id' => $confirmSegment->getId()]),
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
     * @param int $id
     * @return array|bool
     */
    public function actionEnableExpertise(int $id)
    {
        if (Yii::$app->request->isAjax) {

            $problem = Problems::findOne($id);
            $problem->setEnableExpertise();
            $confirmSegment = ConfirmSegment::findOne($problem->getConfirmSegmentId());

            if ($problem->save()) {

                $response = [
                    'renderAjax' => $this->renderAjax('_index_ajax', [
                        'models' => Problems::findAll(['basic_confirm_id' => $confirmSegment->getId()]),
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
     * @return false|int
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionDeleteExpectedResultsInterview(int $id)
    {
        if(Yii::$app->request->isAjax && $model = ExpectedResultsInterviewConfirmProblem::findOne($id)) {
            return $model->delete();
        }
        return false;
    }


    /**
     * @param int $id
     * @return array|bool
     */
    public function actionGetInterviewRespond (int $id)
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
     * @param int $id
     * @return mixed
     * @throws MpdfException
     * @throws CrossReferenceException
     * @throws PdfParserException
     * @throws PdfTypeException
     * @throws InvalidConfigException
     */
    public function actionMpdfTableProblems (int $id) {

        $confirmSegment = ConfirmSegment::findOne($id);
        $segment = $confirmSegment->segment;
        $models = $confirmSegment->problems;

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('mpdf_table_problems', ['models' => $models]);

        $destination = Pdf::DEST_BROWSER;
        //$destination = Pdf::DEST_DOWNLOAD;

        $filename = 'Проблемы сегмента «'.$segment->getName() .'».pdf';

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
                'SetTitle' => ['Проблемы сегмента «'.$segment->getName() .'»'],
                'SetHeader' => ['<div style="color: #3c3c3c;">Проблемы сегмента «'.$segment->getName().'»</div>||<div style="color: #3c3c3c;">Сгенерировано: ' . date("H:i d.m.Y") . '</div>'],
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
     * @return Problems|null
     * @throws NotFoundHttpException
     */
    protected function findModel(int $id): ?Problems
    {
        if (($model = Problems::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
