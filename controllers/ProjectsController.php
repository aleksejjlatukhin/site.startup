<?php

namespace app\controllers;

use app\models\Authors;
use app\models\BusinessModel;
use app\models\ConfirmGcp;
use app\models\ConfirmMvp;
use app\models\ConfirmProblem;
use app\models\FeedbackExpert;
use app\models\FeedbackExpertConfirm;
use app\models\FeedbackExpertGcp;
use app\models\FeedbackExpertMvp;
use app\models\Gcp;
use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Model;
use app\models\Mvp;
use app\models\PreFiles;
use app\models\Questions;
use app\models\Respond;
use app\models\RespondsConfirm;
use app\models\RespondsGcp;
use app\models\RespondsMvp;
use app\models\Segment;
use app\models\User;
use Yii;
use app\models\Projects;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * ProjectsController implements the CRUD actions for Projects model.
 */
class ProjectsController extends AppController
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
     * Lists all Projects models.
     * @return mixed
     */
    public function actionIndex()
    {
        $user = Yii::$app->user->identity;

        $dataProvider = new ActiveDataProvider([
            'query' => Projects::find()->where(['user_id' => $user['id']]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionDownload($filename)
    {
        $model = PreFiles::find()->where(['file_name' => $filename])->one();
        $user = Yii::$app->user->identity;
        $project = Projects::find()->where(['id' => $model->project_id])->one();

        $path = \Yii::getAlias('upload/'. mb_strtolower(mb_convert_encoding($user['username'], "windows-1251"),"windows-1251")
            . '/' . mb_strtolower(mb_convert_encoding($project->project_name, "windows-1251"),"windows-1251") . '/present files/');

        $file = $path . $model->file_name;

        if (file_exists($file)) {

            return \Yii::$app->response->sendFile($file);
        }
    }


    public function actionDeleteFile($filename)
    {
        $model = PreFiles::find()->where(['file_name' => $filename])->one();
        $user = Yii::$app->user->identity;
        $project = Projects::find()->where(['id' => $model->project_id])->one();

        $path = \Yii::getAlias('upload/'. mb_strtolower(mb_convert_encoding($user['username'], "windows-1251"),"windows-1251")
            . '/' . mb_strtolower(mb_convert_encoding($project->project_name, "windows-1251"),"windows-1251") . '/present files/');

        unlink($path . $model->file_name);

        $model->delete();

        if (Yii::$app->request->isAjax)
        {
            return 'Delete';
        }else{
            return $this->redirect(['update', 'id' => $project->id]);
        }
    }

    /**
     * Displays a single Projects model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $segments = Segment::find()->where(['project_id' => $model->id])->all();
        $equally = array();
        foreach ($segments as $k => $segment){
            $equally[$segment->name][] = $segment->name;
        }

        $i = 0;
        foreach ($equally as $k => $segment){
            if (count($segment) > 1){
                //echo 'значение-&nbsp'.$k.'&nbsp встречается &nbsp'.count($segment).'&nbsp раз(раза) <br>';
            }
        }



        return $this->render('view', [
            'model' => $model,
        ]);
    }


    public function actionResult($id)
    {
        $model = Projects::findOne($id);
        $segments = Segment::find()->where(['project_id' => $model->id])->all();
        $problems = [];
        $offers = [];
        $mvProducts = [];
        $confirmMvps = [];
        foreach ($segments as $segment){
            $generationProblems = GenerationProblem::find()->where(['interview_id' => $segment->interview->id])->all();
            foreach ($generationProblems as $k => $generationProblem){
                $problems[] = $generationProblem;
                $gcps = Gcp::find()->where(['confirm_problem_id' => $generationProblem->confirm->id])->all();
                foreach ($gcps as $gcp){
                    $offers[] = $gcp;
                    $mvps = Mvp::find()->where(['confirm_gcp_id' => $gcp->confirm->id])->all();
                    foreach ($mvps as $mvp){
                        $mvProducts[] = $mvp;
                        $confMvp = ConfirmMvp::find()->where(['mvp_id' => $mvp->id])->one();
                        $confirmMvps[] = $confMvp;
                    }
                }
            }
        }


        return $this->render('result', [
            'model' => $model,
            'segments' => $segments,
            'generationProblems' => $generationProblems,
            'problems' => $problems,
            'offers' => $offers,
            'mvProducts' => $mvProducts,
            'confirmMvps' => $confirmMvps,

        ]);
    }

    /**
     * Creates a new Projects model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Projects();

        $modelsConcept = [new Segment];
        $modelsAuthors = [new Authors];

        $user = Yii::$app->user->identity;
        $models = Projects::find()->where(['user_id' => $user['id']])->all();

        $model->user_id = $user['id'];
        $model->created_at = date('Y:m:d');
        $model->update_at = date('Y:m:d');

        if ($model->load(Yii::$app->request->post())) {

            $modelsConcept = Model::createMultiple(Segment::class);
            Model::loadMultiple($modelsConcept, Yii::$app->request->post());

            $modelsAuthors = Model::createMultiple(Authors::class);
            Model::loadMultiple($modelsAuthors, Yii::$app->request->post());


            $countMod = 1;
            foreach ($models as $item) {
                if (mb_strtolower(str_replace(' ', '', $model->project_name)) == mb_strtolower(str_replace(' ', '', $item->project_name))) {
                    $countMod++;
                }
            }

            if ($countMod < 2){
                $equally = array();
                foreach ($modelsConcept as $k=>$modelConcept){
                    $equally[mb_strtolower(str_replace(' ', '',$modelConcept->name))][] = $modelConcept->name;
                }

                foreach ($equally as $k=>$modelConcept){
                    if (count($modelConcept) < 2){

                        if ($model->save()){

                            $user_dir = UPLOAD . mb_convert_encoding($user['username'], "windows-1251") . '/';
                            $user_dir = mb_strtolower($user_dir, "windows-1251");
                            if (!file_exists($user_dir)){
                                mkdir($user_dir, 0777);
                            }

                            $project_dir = $user_dir . '/' . mb_convert_encoding($model->project_name , "windows-1251") . '/';
                            $project_dir = mb_strtolower($project_dir, "windows-1251");
                            if (!file_exists($project_dir)){
                                mkdir($project_dir, 0777);
                            }

                            $present_files_dir = $project_dir . '/present files/';
                            if (!file_exists($present_files_dir)){
                                mkdir($present_files_dir, 0777);
                            }


                            $segments_dir = UPLOAD . mb_convert_encoding($user['username'], "windows-1251") . '/' .
                                mb_convert_encoding($model->project_name , "windows-1251") . '/segments/';

                            if (!file_exists($segments_dir)){
                                mkdir($segments_dir, 0777);
                            }

                            foreach ($modelsConcept as $modelConcept){
                                $segment_dir = $segments_dir . '/' . mb_convert_encoding($modelConcept->name , "windows-1251") . '/';
                                $segment_dir = mb_strtolower($segment_dir, "windows-1251");

                                if (!file_exists($segment_dir)){
                                    mkdir($segment_dir, 0777);
                                }
                            }



                            // validate all models
                            $valid = $model->validate();
                            $valid = Model::validateMultiple($modelsConcept) && $valid;
                            $valid = Model::validateMultiple($modelsAuthors) && $valid;


                            if ($valid) {
                                $transaction = \Yii::$app->db->beginTransaction();
                                try {
                                    if ($flag = $model->save(false)) {
                                        foreach ($modelsConcept as $modelsConcept) {
                                            $modelsConcept->project_id = $model->id;
                                            if (! ($flag = $modelsConcept->save(false))) {
                                                $transaction->rollBack();
                                                break;
                                            }
                                        }


                                        foreach ($modelsAuthors as $modelsAuthors) {
                                            $modelsAuthors->project_id = $model->id;
                                            if (! ($flag = $modelsAuthors->save(false))) {
                                                $transaction->rollBack();
                                                break;
                                            }
                                        }
                                    }

                                    if ($flag) {
                                        $transaction->commit();

                                        $model->present_files = UploadedFile::getInstances($model, 'present_files');

                                        if ($model->validate() && $model->upload($present_files_dir)){
                                            foreach ($model->present_files as $file){
                                                $preFiles = new PreFiles();
                                                $preFiles->file_name = $file;
                                                $preFiles->project_id = $model->id;
                                                $preFiles->save(false);
                                            }
                                        }

                                        return $this->redirect(['view', 'id' => $model->id]);
                                    }
                                } catch (Exception $e) {
                                    $transaction->rollBack();
                                }
                            }
                        }
                    }else{
                        Yii::$app->session->setFlash('error', 'Введено ' . count($modelConcept) . ' одинаковых названия сегментов. Это недопустимо, название сегмента должно быть уникальным!');
                    }
                }
            }else{
                Yii::$app->session->setFlash('error', 'Проект с названием "'. $model->project_name .'" уже существует!');
            }
        }
        return $this->render('create', [
            'model' => $model,
            'modelsConcept' => (empty($modelsConcept)) ? [new Segment] : $modelsConcept,
            'modelsAuthors' => (empty($modelsAuthors)) ? [new Authors] : $modelsAuthors,
        ]);
    }

    /**
     * Updates an existing Projects model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $user = Yii::$app->user->identity;
        $models = Projects::find()->where(['user_id' => $user['id']])->all();
        $modelsConcept = Segment::find()->where(['project_id'=>$id])->all();
        $modelsAuthors = Authors::find()->where(['project_id'=>$id])->all();
        $segments = Segment::find()->where(['project_id' => $model->id])->all();

        $user = Yii::$app->user->identity;
        $model->update_at = date('Y:m:d');

        if ($model->load(Yii::$app->request->post())) {

            $oldIDs = ArrayHelper::map($modelsConcept, 'id', 'id');
            $modelsConcept = Model::createMultiple(Segment::class, $modelsConcept);
            Model::loadMultiple($modelsConcept, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsConcept, 'id', 'id')));

            $oldIDs = ArrayHelper::map($modelsAuthors, 'id', 'id');
            $modelsAuthors = Model::createMultiple(Authors::class, $modelsAuthors);
            Model::loadMultiple($modelsAuthors, Yii::$app->request->post());
            $deletedIDs1 = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsAuthors, 'id', 'id')));


            $equally = array();
            foreach ($modelsConcept as $k=>$modelConcept){
                $equally[mb_strtolower(str_replace(' ', '',$modelConcept->name))][] = $modelConcept->name;
            }


            $countConcept = 1;
            foreach ($equally as $k=>$modelConcept){
                if (count($modelConcept) > 1){
                    $countConcept++;
                }
            }

            if ($countConcept < 2){

                $countCon = 1;
                foreach ($models as $item) {
                    if ($model->id !== $item->id && mb_strtolower(str_replace(' ', '', $model->project_name)) == mb_strtolower(str_replace(' ', '', $item->project_name))) {
                        $countCon++;
                    }
                }

                if ($countCon < 2){
                    foreach ($models as $elem){
                        if ($model->id == $elem->id && mb_strtolower(str_replace(' ', '',$model->project_name)) !== mb_strtolower(str_replace(' ', '',$elem->project_name))){

                            $old_dir = 'upload/'. mb_convert_encoding($user['username'], "windows-1251")
                                . '/' . mb_convert_encoding($elem->project_name, "windows-1251") . '/';

                            $old_dir = mb_strtolower($old_dir, "windows-1251");

                            $new_dir = 'upload/'. mb_convert_encoding($user['username'], "windows-1251")
                                . '/' . mb_convert_encoding($model->project_name, "windows-1251") . '/';

                            $new_dir = mb_strtolower($new_dir, "windows-1251");

                            rename($old_dir, $new_dir);
                        }
                    }

                    if ($model->save()){

                        foreach ($segments as $segment){
                            foreach ($modelsConcept as $modelConcept){
                                if ($segment->id == $modelConcept->id && $segment->name !== $modelConcept->name){

                                    $old_dir = 'upload/'. mb_convert_encoding($user['username'], "windows-1251")
                                        . '/' . mb_convert_encoding($model->project_name, "windows-1251")
                                        . '/segments/' . mb_convert_encoding($segment->name, "windows-1251") . '/';

                                    $old_dir = mb_strtolower($old_dir, "windows-1251");

                                    $new_dir = 'upload/'. mb_convert_encoding($user['username'], "windows-1251")
                                        . '/' . mb_convert_encoding($model->project_name, "windows-1251")
                                        . '/segments/' . mb_convert_encoding($modelConcept->name, "windows-1251") . '/';

                                    $new_dir = mb_strtolower($new_dir, "windows-1251");

                                    rename($old_dir, $new_dir);
                                }
                            }
                        }


                        $segments_dir = UPLOAD . mb_convert_encoding($user['username'], "windows-1251") . '/' .
                            mb_convert_encoding($model->project_name , "windows-1251") . '/segments/';

                        if (!file_exists($segments_dir)){
                            mkdir($segments_dir, 0777);
                        }

                        foreach ($modelsConcept as $modelConcept){
                            $segment_dir = $segments_dir . '/' . mb_convert_encoding($modelConcept->name , "windows-1251") . '/';
                            $segment_dir = mb_strtolower($segment_dir, "windows-1251");

                            if (!file_exists($segment_dir)){
                                mkdir($segment_dir, 0777);
                            }
                        }


                        // validate all models
                        $valid = $model->validate();
                        $valid = Model::validateMultiple($modelsConcept) && $valid;
                        $valid = Model::validateMultiple($modelsAuthors) && $valid;


                        if ($valid) {
                            $transaction = \Yii::$app->db->beginTransaction();
                            try {
                                if ($flag = $model->save(false)) {
                                    if (! empty($deletedIDs)) {

                                        $seg = Segment::find()->where(['id' => $deletedIDs])->one();
                                        $segment_dir = $segments_dir . '/' . mb_convert_encoding($seg->name , "windows-1251") . '/';
                                        $segment_dir = mb_strtolower($segment_dir, "windows-1251");
                                        $this->delTree($segment_dir);

                                        Segment::deleteAll(['id' => $deletedIDs]);
                                    }
                                    if (! empty($deletedIDs1)) {
                                        Authors::deleteAll(['id' => $deletedIDs1]);
                                    }
                                    foreach ($modelsConcept as $modelsConcept) {
                                        $modelsConcept->project_id = $model->id;
                                        if (! ($flag = $modelsConcept->save(false))) {
                                            $transaction->rollBack();
                                            break;
                                        }
                                    }

                                    foreach ($modelsAuthors as $modelsAuthors) {
                                        $modelsAuthors->project_id = $model->id;
                                        if (! ($flag = $modelsAuthors->save(false))) {
                                            $transaction->rollBack();
                                            break;
                                        }
                                    }

                                }
                                if ($flag) {
                                    $transaction->commit();

                                    $model->present_files = UploadedFile::getInstances($model, 'present_files');

                                    $present_files_dir = UPLOAD . mb_strtolower(mb_convert_encoding($user['username'], "windows-1251"),"windows-1251")
                                        . '/' . mb_strtolower(mb_convert_encoding($model->project_name, "windows-1251"),"windows-1251") . '/present files/';


                                    if ($model->validate() && $model->upload($present_files_dir)){

                                        foreach ($model->present_files as $file){

                                            $y = 0;
                                            foreach ($model->preFiles as $preFile){
                                                if ($file == $preFile->file_name){
                                                    $y++;
                                                }
                                            }

                                            if ($y == 0){

                                                $preFiles = new PreFiles();
                                                $preFiles->file_name = $file;
                                                $preFiles->project_id = $model->id;
                                                $preFiles->save(false);
                                            }
                                        }


                                        Yii::$app->session->setFlash('success', "Проект * {$model->project_name} * обновлен");
                                        return $this->redirect(['view', 'id' => $model->id]);
                                    }

                                }
                            } catch (Exception $e) {
                                $transaction->rollBack();
                            }
                        }
                    }
                } else{
                    Yii::$app->session->setFlash('error', 'Проект с названием "'. $model->project_name .'" уже существует!');
                }
            }else{
                Yii::$app->session->setFlash('error', 'Введены одинаковые названия сегментов. Это недопустимо, название сегмента должно быть уникальным!');
            }
        }

        return $this->render('update', [
            'model' => $model,
            'modelsConcept' => (empty($modelsConcept)) ? [new Segment] : $modelsConcept,
            'modelsAuthors' => (empty($modelsAuthors)) ? [new Authors] : $modelsAuthors,
        ]);
    }

    /**
     * Deletes an existing Projects model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $segments = Segment::find()->where(['project_id' => $model->id])->all();
        $user = Yii::$app->user->identity;


        if(!empty($segments)){
            foreach ($segments as $segment){

                $interview = Interview::find()->where(['segment_id' => $segment->id])->one();
                $responds = Respond::find()->where(['interview_id' => $interview->id])->all();

                if(!empty($responds)){
                    foreach ($responds as $respond){
                        if (!empty($respond->descInterview)){
                            $respond->descInterview->delete();
                        }
                    }
                }

                $generationProblems = GenerationProblem::find()->where(['interview_id' => $interview->id])->all();

                if (!empty($generationProblems)){
                    foreach ($generationProblems as $generationProblem){
                        if (!empty($generationProblem->confirm)){
                            $confirmProblem = $generationProblem->confirm;

                            if (!empty($confirmProblem->feedbacks)){
                                FeedbackExpertConfirm::deleteAll(['confirm_problem_id' => $confirmProblem->id]);
                            }


                            if (!empty($confirmProblem->responds)){
                                $respondsConfirm = $confirmProblem->responds;

                                foreach ($respondsConfirm as $respondConfirm){
                                    if (!empty($respondConfirm->descInterview)){
                                        $respondConfirm->descInterview->delete();
                                    }
                                }

                                RespondsConfirm::deleteAll(['confirm_problem_id' => $confirmProblem->id]);
                            }


                            if (!empty($confirmProblem->gcps)){
                                $gcps = $confirmProblem->gcps;

                                foreach ($gcps as $gcp){
                                    if(!empty($gcp->confirm)){
                                        $confirmGcp = $gcp->confirm;

                                        if (!empty($confirmGcp->feedbacks)){
                                            FeedbackExpertGcp::deleteAll(['confirm_gcp_id' => $confirmGcp->id]);
                                        }

                                        if (!empty($confirmGcp->responds)){
                                            $respondsGcp = $confirmGcp->responds;

                                            foreach ($respondsGcp as $respondGcp){
                                                if (!empty($respondGcp->descInterview)){
                                                    $respondGcp->descInterview->delete();
                                                }
                                            }

                                            RespondsGcp::deleteAll(['confirm_gcp_id' => $confirmGcp->id]);
                                        }

                                        if (!empty($confirmGcp->mvps)){
                                            $mvps = $confirmGcp->mvps;

                                            foreach ($mvps as $mvp){
                                                if (!empty($mvp->confirm)){
                                                    $confirmMvp = $mvp->confirm;

                                                    if (!empty($confirmMvp->feedbacks)){
                                                        FeedbackExpertMvp::deleteAll(['confirm_mvp_id' => $confirmMvp->id]);
                                                    }

                                                    if (!empty($confirmMvp->responds)){
                                                        $respondsMvp = $confirmMvp->responds;

                                                        foreach ($respondsMvp as $respondMvp){
                                                            if (!empty($respondMvp->descInterview)){
                                                                $respondMvp->descInterview->delete();
                                                            }
                                                        }

                                                        RespondsMvp::deleteAll(['confirm_mvp_id' => $confirmMvp->id]);
                                                    }

                                                    if (!empty($confirmMvp->business)){
                                                        $confirmMvp->business->delete();
                                                    }


                                                    $confirmMvp->delete();
                                                }
                                            }

                                            Mvp::deleteAll(['confirm_gcp_id' => $confirmGcp->id]);
                                        }

                                        $confirmGcp->delete();
                                    }
                                }

                                Gcp::deleteAll(['confirm_problem_id' => $confirmProblem->id]);
                            }

                            $confirmProblem->delete();
                        }
                    }
                }



                Questions::deleteAll(['interview_id' => $interview->id]);
                FeedbackExpert::deleteAll(['interview_id' => $interview->id]);
                Respond::deleteAll(['interview_id' => $interview->id]);
                GenerationProblem::deleteAll(['interview_id' => $interview->id]);
                Interview::deleteAll(['segment_id' => $segment->id]);
            }
        }

        /*Удаление загруженных папок и файлов пользователя*/
        $pathDelete = \Yii::getAlias('upload/'. mb_strtolower(mb_convert_encoding($user['username'], "windows-1251"),"windows-1251")
            . '/' . mb_strtolower(mb_convert_encoding($model->project_name, "windows-1251"),"windows-1251"));
        if (file_exists($pathDelete)){
            $this->delTree($pathDelete);
        }
        /*-----------------------------------------------*/


        PreFiles::deleteAll(['project_id' => $model->id]);
        Authors::deleteAll(['project_id' => $model->id]);
        Segment::deleteAll(['project_id' => $model->id]);


        Yii::$app->session->setFlash('error', 'Проект "' . $this->findModel($id)->project_name . '" удален');

        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Projects model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Projects the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Projects::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
