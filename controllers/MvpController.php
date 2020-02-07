<?php

namespace app\controllers;

use app\models\ConfirmGcp;
use app\models\ConfirmProblem;
use app\models\Gcp;
use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Projects;
use app\models\Segment;
use Yii;
use app\models\Mvp;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MvpController implements the CRUD actions for mvp model.
 */
class MvpController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all mvp models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $user = Yii::$app->user->identity;
        $confirmGcp = ConfirmGcp::findOne($id);
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        if ($gcp->exist_confirm !== 1){
            Yii::$app->session->setFlash('error', "Отсутствует подтверждение ГЦП с данным ID, поэтому вы не можете перейти к созданию MVP.");
            return $this->redirect(['gcp/view', 'id' => $gcp->id]);
        }

        $mvps_dir = UPLOAD . mb_convert_encoding($user['username'], "windows-1251") . '/' .
            mb_convert_encoding($project->project_name , "windows-1251") . '/segments/'.
            mb_convert_encoding($segment->name , "windows-1251") .'/generation problems/'
            . mb_convert_encoding($generationProblem->title , "windows-1251") . '/gcps/'
            . mb_convert_encoding($gcp->title , "windows-1251") . '/mvps/';

        $mvps_dir = mb_strtolower($mvps_dir, "windows-1251");

        if (!file_exists($mvps_dir)){
            mkdir($mvps_dir, 0777);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Mvp::find()->where(['confirm_gcp_id' => $id]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
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
     * Displays a single mvp model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $confirmGcp = ConfirmGcp::find()->where(['id' => $model->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        return $this->render('view', [
            'model' => $model,
            'confirmGcp' => $confirmGcp,
            'gcp' => $gcp,
            'confirmProblem' => $confirmProblem,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,

        ]);
    }


    // ОТКЛЮЧАЕМ CSRF
    public function beforeAction($action)
    {
        if ($action->id == 'create') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    /**
     * Creates a new mvp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new Mvp();
        $model->confirm_gcp_id = $id;
        $model->date_create = date('Y:m:d');
        $model->date_time_create = date('Y-m-d H:i:s');
        $models = Mvp::find()->where(['confirm_gcp_id' => $id])->all();
        $model->title = 'MVP ' . (count($models)+1);

        $confirmGcp = ConfirmGcp::findOne($id);
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        if (Yii::$app->request->isAjax){

            if ($model->load(Yii::$app->request->post())) {
                $model->description = $_POST['Mvp']['description'];

                if ($model->save()){

                    $project->update_at = date('Y:m:d');
                    $project->save();

                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $model;
                    return $model;

                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'models' => $models,
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
     * Updates an existing mvp model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $confirmGcp = ConfirmGcp::find()->where(['id' => $model->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
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
     * Deletes an existing mvp model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the mvp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Mvp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mvp::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
