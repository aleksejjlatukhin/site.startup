<?php

namespace app\controllers;

use app\models\ConfirmGcp;
use app\models\ConfirmMvp;
use app\models\ConfirmProblem;
use app\models\Gcp;
use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Mvp;
use app\models\Projects;
use app\models\Segment;
use app\models\User;
use Yii;
use app\models\BusinessModel;
use yii\web\NotFoundHttpException;
use kartik\mpdf\Pdf;


class BusinessModelController extends AppController
{

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\HttpException
     */
    public function beforeAction($action)
    {

        if (in_array($action->id, ['index'])){

            $confirmMvp = ConfirmMvp::findOne(Yii::$app->request->get());
            $mvp = Mvp::findOne(['id' => $confirmMvp->mvp_id]);
            $project = $mvp->project;

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['update'])){

            $model = BusinessModel::findOne(Yii::$app->request->get());
            $project = $model->project;

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){

            $confirmMvp = ConfirmMvp::findOne(Yii::$app->request->get());
            $project = $confirmMvp->mvp->project;

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['mpdf-business-model'])){

            $model = BusinessModel::findOne(Yii::$app->request->get());
            $project = $model->project;

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
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
        $model = BusinessModel::findOne(['confirm_mvp_id' => $id]);
        $confirmMvp = ConfirmMvp::findOne($id);
        $newModel = new BusinessModel();

        $mvp = Mvp::find()->where(['id' => $confirmMvp->mvp_id])->one();
        $confirmGcp = ConfirmGcp::find()->where(['id' => $mvp->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        return $this->render('index', [
            'model' => $model,
            'confirmMvp' => $confirmMvp,
            'newModel' => $newModel,
            'mvp' => $mvp,
            'confirmGcp' => $confirmGcp,
            'gcp' => $gcp,
            'confirmProblem' => $confirmProblem,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }


    /**
     * @param $id
     * @return array
     */
    public function actionCreate($id)
    {
        $model = new BusinessModel();

        $confirmMvp = ConfirmMvp::findOne($id);
        $mvp = Mvp::find()->where(['id' => $confirmMvp->mvp_id])->one();
        $confirmGcp = ConfirmGcp::find()->where(['id' => $mvp->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($businessModel = $model->create($id, $mvp->id, $gcp->id, $generationProblem->id, $segment->id, $project->id)) {

                    $project->updated_at = time();

                    if ($project->save()) {

                        $response = ['model' => $businessModel];
                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;
                    }
                } else {
                    $response = ['error' => true];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
    }


    /**
     * @param $id
     * @return array|bool
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $confirmMvp = ConfirmMvp::find()->where(['id' => $model->confirm_mvp_id])->one();
        $mvp = Mvp::find()->where(['id' => $confirmMvp->mvp_id])->one();
        $confirmGcp = ConfirmGcp::find()->where(['id' => $mvp->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model->save()) {

                    $project->updated_at = time();

                    if ($project->save()) {

                        $response = ['model' => $model];
                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;
                    }
                } else {

                    $response = ['error' => true];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
        return false;
    }


    /**
     * export in pdf
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Mpdf\MpdfException
     * @throws \setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException
     * @throws \setasign\Fpdi\PdfParser\PdfParserException
     * @throws \setasign\Fpdi\PdfParser\Type\PdfTypeException
     * @throws \yii\base\InvalidConfigException
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
