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
use app\models\FeedbackExpertGcp;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * FeedbackExpertGcpController implements the CRUD actions for FeedbackExpertGcp model.
 */
class FeedbackExpertGcpController extends AppController
{

    public function beforeAction($action)
    {

        if (in_array($action->id, ['view']) || in_array($action->id, ['update']) || in_array($action->id, ['delete'])){

            $model = FeedbackExpertGcp::findOne(Yii::$app->request->get());
            $confirmGcp = ConfirmGcp::find()->where(['id' => $model->confirm_gcp_id])->one();
            $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
            $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
            $problem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
            $interview = Interview::find()->where(['id' => $problem->interview_id])->one();
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if ($project->user_id == Yii::$app->user->id){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){

            $confirmGcp = ConfirmGcp::findOne(Yii::$app->request->get());
            $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
            $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
            $problem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
            $interview = Interview::find()->where(['id' => $problem->interview_id])->one();
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if ($project->user_id == Yii::$app->user->id){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }else{
            return parent::beforeAction($action);
        }

    }

    /**
     * Lists all FeedbackExpertGcp models.
     * @return mixed
     */
    /*public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => FeedbackExpertGcp::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }*/


    public function actionDownload($id)
    {
        $user = Yii::$app->user->identity;
        $model = FeedbackExpertGcp::findOne($id);
        $confirmGcp = ConfirmGcp::find()->where(['id' => $model->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $path = \Yii::getAlias(UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
            mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
            mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/generation problems/'
            . mb_convert_encoding($this->translit($generationProblem->title) , "windows-1251") . '/gcps/'
            . mb_convert_encoding($this->translit($gcp->title) , "windows-1251") . '/feedbacks-confirm/'
            . mb_convert_encoding($this->translit($model->name) , "windows-1251") . '/');

        $file = $path . $model->server_file;

        if (file_exists($file)) {

            return \Yii::$app->response->sendFile($file, $model->feedback_file);
        }
    }


    public function actionDeleteFile($id)
    {
        $user = Yii::$app->user->identity;
        $model = FeedbackExpertGcp::findOne($id);
        $confirmGcp = ConfirmGcp::find()->where(['id' => $model->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $path = \Yii::getAlias(UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
            mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
            mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/generation problems/'
            . mb_convert_encoding($this->translit($generationProblem->title) , "windows-1251") . '/gcps/'
            . mb_convert_encoding($this->translit($gcp->title) , "windows-1251") . '/feedbacks-confirm/'
            . mb_convert_encoding($this->translit($model->name) , "windows-1251") . '/');

        unlink($path . $model->server_file);

        $model->feedback_file = null;
        $model->server_file = null;

        $model->update();

        if (Yii::$app->request->isAjax)
        {
            return 'Delete';
        }else{
            return $this->redirect(['update', 'id' => $model->id]);
        }
    }

    /**
     * Displays a single FeedbackExpertGcp model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $user = Yii::$app->user->identity;
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

    /**
     * Creates a new FeedbackExpertGcp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $user = Yii::$app->user->identity;
        $model = new FeedbackExpertGcp();
        $model->confirm_gcp_id = $id;
        $model->date_feedback = date('Y:m:d');
        $models = FeedbackExpertGcp::find()->where(['confirm_gcp_id' => $id])->all();
        $model->title = 'Отзыв ' . (count($models)+1);

        $confirmGcp = ConfirmGcp::findOne($id);
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

                $expert_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                    mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
                    mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/generation problems/'
                    . mb_convert_encoding($this->translit($generationProblem->title) , "windows-1251") . '/gcps/'
                    . mb_convert_encoding($this->translit($gcp->title) , "windows-1251") . '/feedbacks-confirm/'
                    . mb_convert_encoding($this->translit($model->name) , "windows-1251") . '/';

                if (!file_exists($expert_dir)){
                    mkdir($expert_dir, 0777);
                }

                if ($model->validate() && $model->save()) {

                    $model->loadFile = UploadedFile::getInstance($model, 'loadFile');

                    if ($model->loadFile !== null){
                        if ($model->upload($expert_dir)){
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
     * Updates an existing FeedbackExpertGcp model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $user = Yii::$app->user->identity;
        $model = $this->findModel($id);

        $confirmGcp = ConfirmGcp::find()->where(['id' => $model->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $models = FeedbackExpertGcp::find()->where(['confirm_gcp_id' => $confirmGcp->id])->all();

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

                        $old_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                            mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
                            mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/generation problems/'
                            . mb_convert_encoding($this->translit($generationProblem->title) , "windows-1251") . '/gcps/'
                            . mb_convert_encoding($this->translit($gcp->title) , "windows-1251") . '/feedbacks-confirm/' .
                            mb_convert_encoding($this->translit($elem->name) , "windows-1251") . '/';

                        $new_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                            mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
                            mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/generation problems/'
                            . mb_convert_encoding($this->translit($generationProblem->title) , "windows-1251") . '/gcps/'
                            . mb_convert_encoding($this->translit($gcp->title) , "windows-1251") . '/feedbacks-confirm/'
                            . mb_convert_encoding($this->translit($model->name) , "windows-1251") . '/';

                        if (file_exists($old_dir)){
                            rename($old_dir, $new_dir);
                        }
                    }
                }

                $expert_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                    mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
                    mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/generation problems/'
                    . mb_convert_encoding($this->translit($generationProblem->title) , "windows-1251") . '/gcps/'
                    . mb_convert_encoding($this->translit($gcp->title) , "windows-1251") . '/feedbacks-confirm/'
                    . mb_convert_encoding($this->translit($model->name) , "windows-1251") . '/';
                if (!file_exists($expert_dir)){
                    mkdir($expert_dir, 0777);
                }

                if ($model->validate() && $model->save()) {

                    $model->loadFile = UploadedFile::getInstance($model, 'loadFile');

                    if ($model->loadFile !== null){
                        if ($model->upload($expert_dir)){
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
     * Deletes an existing FeedbackExpertGcp model.
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
     * Finds the FeedbackExpertGcp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return FeedbackExpertGcp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FeedbackExpertGcp::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
