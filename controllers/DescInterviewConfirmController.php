<?php

namespace app\controllers;

use app\models\ConfirmProblem;
use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Projects;
use app\models\RespondsConfirm;
use app\models\Segment;
use app\models\User;
use Yii;
use app\models\DescInterviewConfirm;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * DescInterviewConfirmController implements the CRUD actions for DescInterviewConfirm model.
 */
class DescInterviewConfirmController extends Controller
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
     * Lists all DescInterviewConfirm models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => DescInterviewConfirm::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDownload($filename)
    {
        $user = Yii::$app->user->identity;
        $model = DescInterviewConfirm::find()->where(['interview_file' => $filename])->one();
        $respond = RespondsConfirm::find()->where(['id' => $model->responds_confirm_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $respond->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $path = \Yii::getAlias(UPLOAD . mb_convert_encoding($user['username'], "windows-1251") . '/' .
            mb_convert_encoding($project->project_name , "windows-1251") . '/segments/'.
            mb_convert_encoding($segment->name , "windows-1251") . '/generation problems/' .
            mb_convert_encoding($generationProblem->title , "windows-1251") .'/interviews-confirm/' .
            mb_convert_encoding($respond->name , "windows-1251") . '/');

        $file = $path . $model->interview_file;

        if (file_exists($file)) {

            return \Yii::$app->response->sendFile($file);
        }

    }


    public function actionDeleteFile($filename)
    {
        $user = Yii::$app->user->identity;
        $model = DescInterviewConfirm::find()->where(['interview_file' => $filename])->one();
        $respond = RespondsConfirm::find()->where(['id' => $model->responds_confirm_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $respond->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $path = \Yii::getAlias(UPLOAD . mb_convert_encoding($user['username'], "windows-1251") . '/' .
            mb_convert_encoding($project->project_name , "windows-1251") . '/segments/'.
            mb_convert_encoding($segment->name , "windows-1251") . '/generation problems/' .
            mb_convert_encoding($generationProblem->title , "windows-1251") .'/interviews-confirm/' .
            mb_convert_encoding($respond->name , "windows-1251") . '/');

        unlink($path . $model->interview_file);

        $model->interview_file = null;

        $model->update();

        if (Yii::$app->request->isAjax)
        {
            return 'Delete';
        }else{
            return $this->redirect(['update', 'id' => $model->id]);
        }
    }

    /**
     * Displays a single DescInterviewConfirm model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $respond = RespondsConfirm::find()->where(['id' => $model->responds_confirm_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $respond->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        return $this->render('view', [
            'model' => $model,
            'respond' => $respond,
            'confirmProblem' => $confirmProblem,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * Creates a new DescInterviewConfirm model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $user = Yii::$app->user->identity;
        $model = new DescInterviewConfirm();
        $model->responds_confirm_id = $id;
        $model->date_fact = date('Y:m:d');

        $respond = RespondsConfirm::find()->where(['id' => $id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $respond->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();


        if ($respond->name == null || $respond->info_respond == null || $respond->place_interview == null ||
            $respond->date_plan == null){
            Yii::$app->session->setFlash('error', "Необходимо заполнить все данные о респонденте!");
            return $this->redirect(['responds-confirm/view', 'id' => $id]);
        }

        if ($model->load(Yii::$app->request->post())) {

            $respond_dir = UPLOAD . mb_convert_encoding($user['username'], "windows-1251") . '/' .
                mb_convert_encoding($project->project_name , "windows-1251") . '/segments/'.
                mb_convert_encoding($segment->name , "windows-1251") . '/generation problems/' .
                mb_convert_encoding($generationProblem->title , "windows-1251") .'/interviews-confirm/' .
                mb_convert_encoding($respond->name , "windows-1251") . '/';
            if (!file_exists($respond_dir)){
                mkdir($respond_dir, 0777);
            }

            if ($model->save()) {

                $model->loadFile = UploadedFile::getInstance($model, 'loadFile');

                if ($model->loadFile !== null){
                    if ($model->validate() && $model->upload($respond_dir)){
                        $model->interview_file = $model->loadFile;
                        $model->save(false);
                    }
                }

                $project->update_at = date('Y:m:d');
                if ($project->save()){
                    Yii::$app->session->setFlash('success', "Материалы полученные во время интервью добавлены!");
                    return $this->redirect(['responds-confirm/view', 'id' => $model->responds_confirm_id]);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'respond' => $respond,
            'confirmProblem' => $confirmProblem,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * Updates an existing DescInterviewConfirm model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $user = Yii::$app->user->identity;
        $model = $this->findModel($id);

        $respond = RespondsConfirm::find()->where(['id' => $model->responds_confirm_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $respond->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        if ($model->interview_file !== null){
            $model->loadFile = $model->interview_file;
        }

        if ($model->load(Yii::$app->request->post())) {

            $respond_dir = UPLOAD . mb_convert_encoding($user['username'], "windows-1251") . '/' .
                mb_convert_encoding($project->project_name , "windows-1251") . '/segments/'.
                mb_convert_encoding($segment->name , "windows-1251") . '/generation problems/' .
                mb_convert_encoding($generationProblem->title , "windows-1251") .'/interviews-confirm/' .
                mb_convert_encoding($respond->name , "windows-1251") . '/';
            if (!file_exists($respond_dir)){
                mkdir($respond_dir, 0777);
            }

            if ($model->save()) {

                $model->loadFile = UploadedFile::getInstance($model, 'loadFile');

                if ($model->loadFile !== null){
                    if ($model->validate() && $model->upload($respond_dir)){
                        $model->interview_file = $model->loadFile;
                        $model->save(false);
                    }
                }

                $project->update_at = date('Y:m:d');
                if ($project->save()){
                    Yii::$app->session->setFlash('success', "Материалы полученные во время интервью обновлены");
                    return $this->redirect(['responds-confirm/view', 'id' => $model->responds_confirm_id]);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'respond' => $respond,
            'confirmProblem' => $confirmProblem,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * Deletes an existing DescInterviewConfirm model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = DescInterviewConfirm::find()->where(['responds_confirm_id' => $id])->one();
        if ($model->interview_file !== null){
            unlink('upload/interviews-confirm/' . $model->interview_file);
        }

        $respond = RespondsConfirm::findOne($id);
        $confirmProblem = ConfirmProblem::find()->where(['id' => $respond->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        if ($project->save()) {

            Yii::$app->session->setFlash('error', 'Материалы полученные во время интервью ' . date("d.m.Y", strtotime($model->date_fact)) . ' удалены!');

            $model->delete();

            return $this->redirect(['responds-confirm/view', 'id' => $respond->id]);
        }
    }

    /**
     * Finds the DescInterviewConfirm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return DescInterviewConfirm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DescInterviewConfirm::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
