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
use Yii;
use app\models\FeedbackExpertMvp;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * FeedbackExpertMvpController implements the CRUD actions for FeedbackExpertMvp model.
 */
class FeedbackExpertMvpController extends AppController
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
     * Lists all FeedbackExpertMvp models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => FeedbackExpertMvp::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDownload($filename)
    {
        $user = Yii::$app->user->identity;
        $model = FeedbackExpertMvp::find()->where(['feedback_file' => $filename])->one();
        $confirmMvp = ConfirmMvp::find()->where(['id' => $model->confirm_mvp_id])->one();
        $mvp = Mvp::find()->where(['id' => $confirmMvp->mvp_id])->one();
        $confirmGcp = ConfirmGcp::find()->where(['id' => $mvp->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $path = \Yii::getAlias(UPLOAD . mb_convert_encoding($user['username'], "windows-1251") . '/' .
            mb_convert_encoding($project->project_name , "windows-1251") . '/segments/'.
            mb_convert_encoding($segment->name , "windows-1251") .'/generation problems/'
            . mb_convert_encoding($generationProblem->title , "windows-1251") . '/gcps/'
            . mb_convert_encoding($gcp->title , "windows-1251") . '/mvps/'
            . mb_convert_encoding($mvp->title , "windows-1251") . '/feedbacks-confirm/'
            . mb_convert_encoding($model->name , "windows-1251") . '/');

        $file = $path . $model->feedback_file;

        if (file_exists($file)) {

            return \Yii::$app->response->sendFile($file);
        }
    }

    public function actionDeleteFile($filename)
    {
        $user = Yii::$app->user->identity;
        $model = FeedbackExpertMvp::find()->where(['feedback_file' => $filename])->one();
        $confirmMvp = ConfirmMvp::find()->where(['id' => $model->confirm_mvp_id])->one();
        $mvp = Mvp::find()->where(['id' => $confirmMvp->mvp_id])->one();
        $confirmGcp = ConfirmGcp::find()->where(['id' => $mvp->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $path = \Yii::getAlias(UPLOAD . mb_convert_encoding($user['username'], "windows-1251") . '/' .
            mb_convert_encoding($project->project_name , "windows-1251") . '/segments/'.
            mb_convert_encoding($segment->name , "windows-1251") .'/generation problems/'
            . mb_convert_encoding($generationProblem->title , "windows-1251") . '/gcps/'
            . mb_convert_encoding($gcp->title , "windows-1251") . '/mvps/'
            . mb_convert_encoding($mvp->title , "windows-1251") . '/feedbacks-confirm/'
            . mb_convert_encoding($model->name , "windows-1251") . '/');

        unlink($path . $model->feedback_file);

        $model->feedback_file = null;

        $model->update();

        if (Yii::$app->request->isAjax)
        {
            return 'Delete';
        }else{
            return $this->redirect(['update', 'id' => $model->id]);
        }
    }

    /**
     * Displays a single FeedbackExpertMvp model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $user = Yii::$app->user->identity;
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


        return $this->render('view', [
            'model' => $model,
            'confirmMvp' => $confirmMvp,
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
     * Creates a new FeedbackExpertMvp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $user = Yii::$app->user->identity;
        $model = new FeedbackExpertMvp();
        $model->confirm_mvp_id = $id;
        $model->date_feedback = date('Y:m:d');
        $models = FeedbackExpertMvp::find()->where(['confirm_mvp_id' => $id])->all();
        $model->title = 'Отзыв ' . (count($models)+1);

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

            $kol = 0;
            foreach ($models as $item){
                if ($model->id !== $item->id && mb_strtolower(str_replace(' ', '',$model->name)) == mb_strtolower(str_replace(' ', '',$item->name))){
                    $kol++;
                }
            }

            if ($kol == 0){

                $expert_dir = UPLOAD . mb_convert_encoding($user['username'], "windows-1251") . '/' .
                    mb_convert_encoding($project->project_name , "windows-1251") . '/segments/'.
                    mb_convert_encoding($segment->name , "windows-1251") .'/generation problems/'
                    . mb_convert_encoding($generationProblem->title , "windows-1251") . '/gcps/'
                    . mb_convert_encoding($gcp->title , "windows-1251") . '/mvps/'
                    . mb_convert_encoding($mvp->title , "windows-1251") . '/feedbacks-confirm/'
                    . mb_convert_encoding($model->name , "windows-1251") . '/';

                if (!file_exists($expert_dir)){
                    mkdir($expert_dir, 0777);
                }

                if ($model->save()) {

                    $model->loadFile = UploadedFile::getInstance($model, 'loadFile');

                    if ($model->loadFile !== null){
                        if ($model->validate() && $model->upload($expert_dir)){
                            $model->feedback_file = $model->loadFile;
                            $model->save(false);
                        }
                    }

                    $project->update_at = date('Y:m:d');

                    if ($project->save()){

                        Yii::$app->session->setFlash('success', $model->title ." добавлен");
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }
            }else{
                Yii::$app->session->setFlash('error', "Эксперт с таким именем уже добавил отзыв!");
            }
        }

        return $this->render('create', [
            'model' => $model,
            'confirmMvp' => $confirmMvp,
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
     * Updates an existing FeedbackExpertMvp model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $user = Yii::$app->user->identity;
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
        $models = FeedbackExpertMvp::find()->where(['confirm_mvp_id' => $confirmMvp->id])->all();

        if ($model->feedback_file !== null){
            $model->loadFile = $model->feedback_file;
        }

        if ($model->load(Yii::$app->request->post())) {

            $kol = 0;
            foreach ($models as $item){
                if ($model->id !== $item->id && mb_strtolower(str_replace(' ', '',$model->name)) == mb_strtolower(str_replace(' ', '',$item->name))){
                    $kol++;
                }
            }

            if ($kol == 0){

                foreach ($models as $elem){
                    if ($model->id == $elem->id && mb_strtolower(str_replace(' ', '',$model->name)) !== mb_strtolower(str_replace(' ', '',$elem->name))){

                        $old_dir = UPLOAD . mb_convert_encoding($user['username'], "windows-1251") . '/' .
                            mb_convert_encoding($project->project_name , "windows-1251") . '/segments/'.
                            mb_convert_encoding($segment->name , "windows-1251") .'/generation problems/'
                            . mb_convert_encoding($generationProblem->title , "windows-1251") . '/gcps/'
                            . mb_convert_encoding($gcp->title , "windows-1251") . '/mvps/'
                            . mb_convert_encoding($mvp->title , "windows-1251") . '/feedbacks-confirm/'
                            . mb_convert_encoding($elem->name , "windows-1251") . '/';

                        $new_dir = UPLOAD . mb_convert_encoding($user['username'], "windows-1251") . '/' .
                            mb_convert_encoding($project->project_name , "windows-1251") . '/segments/'.
                            mb_convert_encoding($segment->name , "windows-1251") .'/generation problems/'
                            . mb_convert_encoding($generationProblem->title , "windows-1251") . '/gcps/'
                            . mb_convert_encoding($gcp->title , "windows-1251") . '/mvps/'
                            . mb_convert_encoding($mvp->title , "windows-1251") . '/feedbacks-confirm/'
                            . mb_convert_encoding($model->name , "windows-1251") . '/';

                        if (file_exists($old_dir)){
                            rename($old_dir, $new_dir);
                        }
                    }
                }

                $expert_dir = UPLOAD . mb_convert_encoding($user['username'], "windows-1251") . '/' .
                    mb_convert_encoding($project->project_name , "windows-1251") . '/segments/'.
                    mb_convert_encoding($segment->name , "windows-1251") .'/generation problems/'
                    . mb_convert_encoding($generationProblem->title , "windows-1251") . '/gcps/'
                    . mb_convert_encoding($gcp->title , "windows-1251") . '/mvps/'
                    . mb_convert_encoding($mvp->title , "windows-1251") . '/feedbacks-confirm/'
                    . mb_convert_encoding($model->name , "windows-1251") . '/';

                if (!file_exists($expert_dir)){
                    mkdir($expert_dir, 0777);
                }

                if ($model->save()) {

                    $model->loadFile = UploadedFile::getInstance($model, 'loadFile');

                    if ($model->loadFile !== null){
                        if ($model->validate() && $model->upload($expert_dir)){
                            $model->feedback_file = $model->loadFile;
                            $model->save(false);
                        }
                    }

                    $project->update_at = date('Y:m:d');
                    if ($project->save()) {
                        Yii::$app->session->setFlash('success', "Данные по " . $model->title . " обновлены!");
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }
            }else{
                Yii::$app->session->setFlash('error', "Эксперт с таким именем уже добавил отзыв!");
            }
        }

        return $this->render('update', [
            'model' => $model,
            'confirmMvp' => $confirmMvp,
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
     * Deletes an existing FeedbackExpertMvp model.
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
     * Finds the FeedbackExpertMvp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return FeedbackExpertMvp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FeedbackExpertMvp::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}