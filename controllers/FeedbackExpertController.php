<?php

namespace app\controllers;

use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Projects;
use app\models\Segment;
use Yii;
use app\models\FeedbackExpert;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * FeedbackExpertController implements the CRUD actions for FeedbackExpert model.
 */
class FeedbackExpertController extends AppController
{

    public function beforeAction($action)
    {

        if (in_array($action->id, ['view']) || in_array($action->id, ['update']) || in_array($action->id, ['delete'])){

            $model = FeedbackExpert::findOne(Yii::$app->request->get());
            $interview = Interview::find()->where(['id' => $model->interview_id])->one();
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if ($project->user_id == Yii::$app->user->id){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){

            $interview = Interview::findOne(Yii::$app->request->get());
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
     * Lists all FeedbackExpert models.
     * @return mixed
     */
    /*public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => FeedbackExpert::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }*/


    public function actionDownload($id)
    {
        $user = Yii::$app->user->identity;
        $model = FeedbackExpert::findOne($id);
        $interview = Interview::find()->where(['id' => $model->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $path = \Yii::getAlias(UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
            mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
            mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/feedbacks/' .
            mb_convert_encoding($this->translit($model->name) , "windows-1251") . '/');

        $file = $path . $model->server_file;

        if (file_exists($file)) {

            return \Yii::$app->response->sendFile($file, $model->feedback_file);
        }

    }


    public function actionDeleteFile($id)
    {
        $user = Yii::$app->user->identity;
        $model = FeedbackExpert::findOne($id);
        $interview = Interview::find()->where(['id' => $model->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $path = \Yii::getAlias(UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
            mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
            mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/feedbacks/' .
            mb_convert_encoding($this->translit($model->name) , "windows-1251") . '/');


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
     * Displays a single FeedbackExpert model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $interview = Interview::find()->where(['id' => $model->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        return $this->render('view', [
            'model' => $model,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * Creates a new FeedbackExpert model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $user = Yii::$app->user->identity;
        $generationProblems = GenerationProblem::find()->where(['interview_id' => $id])->all();
        if (count($generationProblems) < 1){
            Yii::$app->session->setFlash('error', "Необходима хотя бы одна ГПС!");
            return $this->redirect(['interview/view', 'id' => $id]);
        }

        $model = new FeedbackExpert();
        $model->interview_id = $id;
        $model->date_feedback = date('Y:m:d');
        $models = FeedbackExpert::find()->where(['interview_id' => $id])->all();
        $model->title = 'Отзыв ' . (count($models)+1);

        $interview = Interview::findOne($id);
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
                    mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/feedbacks/' .
                    mb_convert_encoding($this->translit($model->name) , "windows-1251") . '/';
                if (!file_exists($expert_dir)){
                    mkdir($expert_dir, 0777);
                }

                if ($model->validate() && $model->save()){

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
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * Updates an existing FeedbackExpert model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $user = Yii::$app->user->identity;
        $model = $this->findModel($id);
        $interview = Interview::find()->where(['id' => $model->interview_id])->one();
        $models = FeedbackExpert::find()->where(['interview_id' => $interview->id])->all();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

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
                            mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/feedbacks/' .
                            mb_convert_encoding($this->translit($elem->name) , "windows-1251") . '/';

                        $new_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                            mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
                            mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/feedbacks/' .
                            mb_convert_encoding($this->translit($model->name) , "windows-1251") . '/';

                        if (file_exists($old_dir)){
                            rename($old_dir, $new_dir);
                        }
                    }
                }

                $expert_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                    mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
                    mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/feedbacks/' .
                    mb_convert_encoding($this->translit($model->name) , "windows-1251") . '/';
                if (!file_exists($expert_dir)){
                    mkdir($expert_dir, 0777);
                }

                if ($model->validate() && $model->save()){

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
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * Deletes an existing FeedbackExpert model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $user = Yii::$app->user->identity;
        $model = $this->findModel($id);
        $interview = Interview::find()->where(['id' => $model->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $project->update_at = date('Y:m:d');

        $del_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
            mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
            mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/feedbacks/' .
            mb_convert_encoding($this->translit($model->name) , "windows-1251") . '/';

        if (file_exists($del_dir)){
            $this->delTree($del_dir);
        }

        if ($project->save()) {

            Yii::$app->session->setFlash('error', '"' . $model->title . '" удален!');

            $model->delete();

            $j = 0;
            foreach ($interview->feedbacks as $item){
                $j++;
                $item->title = 'Отзыв ' . $j;
                $item->save();
            }

            return $this->redirect(['interview/view', 'id' => $interview->id]);
        }
    }

    /**
     * Finds the FeedbackExpert model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return FeedbackExpert the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FeedbackExpert::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
