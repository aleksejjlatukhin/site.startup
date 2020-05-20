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
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use yii\data\ArrayDataProvider;


/**
 * ProjectsController implements the CRUD actions for Projects model.
 */
class ProjectsController extends AppController
{

    public function beforeAction($action)
    {

        if (in_array($action->id, ['view']) || in_array($action->id, ['result']) || in_array($action->id, ['upshot'])){

            $model = Projects::findOne(Yii::$app->request->get());

            /*Ограничение доступа к проэктам пользователя*/
            if (($model->user_id == Yii::$app->user->id) || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){


            if (User::isUserAdmin(Yii::$app->user->identity['username']) || User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');

            }else {

                $user = User::findOne(Yii::$app->request->get());

                /*Ограничение доступа к проэктам пользователя*/
                if ($user->id == Yii::$app->user->id){

                    return parent::beforeAction($action);

                }else{

                    if (User::isUserDev(Yii::$app->user->identity['username'])){

                        return parent::beforeAction($action);

                    }else {

                        throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
                    }
                }
            }

        }elseif (in_array($action->id, ['index'])){

            $user = User::findOne(Yii::$app->request->get());

            /*Ограничение доступа к проэктам пользователя*/
            if (($user->id == Yii::$app->user->id) || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['update'])){

            $project = Projects::findOne(Yii::$app->request->get());
            $user = User::findOne(['id' => $project->user_id]);

            /*Ограничение доступа к проэктам пользователя*/
            if (($user->id == Yii::$app->user->id)  || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }else{
            return parent::beforeAction($action);
        }
    }


    /**
     * Lists all Projects models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $user = User::findOne($id);

        $dataProvider = new ActiveDataProvider([
            'query' => Projects::find()->where(['user_id' => $id]),
            'sort' => false,
        ]);

        $projects = Projects::find()->where(['user_id' => $id])->all();
        if (count($projects) == 0){
            return $this->redirect(['create', 'id' => $id]);
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'user' => $user,
        ]);
    }


    public function actionDownload($id)
    {
        $model = PreFiles::findOne($id);
        $project = Projects::find()->where(['id' => $model->project_id])->one();
        $user = User::find()->where(['id' => $project->user_id])->one();

        $path = \Yii::getAlias('upload/'. mb_strtolower(mb_convert_encoding($user['username'], "windows-1251"),"windows-1251")
            . '/' . mb_strtolower(mb_convert_encoding($this->translit($project->project_name), "windows-1251"),"windows-1251") . '/present files/');

        $file = $path . $model->server_file;

        if (file_exists($file)) {

            return \Yii::$app->response->sendFile($file, $model->file_name);
        }
    }


    public function actionDeleteFile($id)
    {
        $model = PreFiles::findOne($id);
        $project = Projects::find()->where(['id' => $model->project_id])->one();
        $user = User::find()->where(['id' => $project->user_id])->one();

        $path = \Yii::getAlias('upload/'. mb_strtolower(mb_convert_encoding($user['username'], "windows-1251"),"windows-1251")
            . '/' . mb_strtolower(mb_convert_encoding($this->translit($project->project_name), "windows-1251"),"windows-1251") . '/present files/');

        unlink($path . $model->server_file);

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

        return $this->render('view', [
            'model' => $model,
        ]);
    }


    public function actionUpshot($id)
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


        return $this->render('upshot', [
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
    public function actionCreate($id)
    {
        $model = new Projects();

        $modelsConcept = [new Segment];
        $modelsAuthors = [new Authors];

        $user = User::findOne(['id' => $id]);

        if ($user->status === User::STATUS_ACTIVE){
            //В зависимости от статуса пользователя
            // создаем папку на сервере, если она ещё не создана
            $user->createDirName();
        }

        $models = Projects::find()->where(['user_id' => $id])->all();

        $model->user_id = $id;
        $model->created_at = date('Y:m:d');
        $model->update_at = date('Y:m:d');

        if ($model->load(Yii::$app->request->post())) {

            /*Преобразование даты в число*/
            if ($model->patent_date){
                $model->patent_date = strtotime($model->patent_date);
            }
            if ($model->register_date){
                $model->register_date = strtotime($model->register_date);
            }
            if ($model->invest_date){
                $model->invest_date = strtotime($model->invest_date);
            }
            if ($model->date_of_announcement){
                $model->date_of_announcement = strtotime($model->date_of_announcement);
            }

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

                            $project_dir = $user_dir . '/' . mb_convert_encoding($this->translit($model->project_name) , "windows-1251") . '/';
                            $project_dir = mb_strtolower($project_dir, "windows-1251");
                            if (!file_exists($project_dir)){
                                mkdir($project_dir, 0777);
                            }

                            $present_files_dir = $project_dir . '/present files/';
                            if (!file_exists($present_files_dir)){
                                mkdir($present_files_dir, 0777);
                            }


                            $segments_dir = $project_dir . '/segments/';
                            if (!file_exists($segments_dir)){
                                mkdir($segments_dir, 0777);
                            }

                            foreach ($modelsConcept as $modelConcept){
                                $segment_dir = $segments_dir . '/' . mb_convert_encoding($this->translit($modelConcept->name) , "windows-1251") . '/';
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

                                        $model->upload($present_files_dir);

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
        $user = User::find()->where(['id' => $model->user_id])->one();
        $_user = Yii::$app->user->identity;
        $models = Projects::find()->where(['user_id' => $user['id']])->all();
        $modelsConcept = Segment::find()->where(['project_id'=>$id])->all();
        $modelsAuthors = Authors::find()->where(['project_id'=>$id])->all();
        $segments = Segment::find()->where(['project_id' => $model->id])->all();

        $model->update_at = date('Y:m:d');

        if ($model->load(Yii::$app->request->post())) {

            /*Преобразование даты в число*/
            if ($model->patent_date){
                $model->patent_date = strtotime($model->patent_date);
            }
            if ($model->register_date){
                $model->register_date = strtotime($model->register_date);
            }
            if ($model->invest_date){
                $model->invest_date = strtotime($model->invest_date);
            }
            if ($model->date_of_announcement){
                $model->date_of_announcement = strtotime($model->date_of_announcement);
            }


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

                            $old_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251")
                                . '/' . mb_convert_encoding($this->translit($elem->project_name), "windows-1251") . '/';

                            $old_dir = mb_strtolower($old_dir, "windows-1251");

                            $new_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251")
                                . '/' . mb_convert_encoding($this->translit($model->project_name), "windows-1251") . '/';

                            $new_dir = mb_strtolower($new_dir, "windows-1251");

                            rename($old_dir, $new_dir);
                        }
                    }

                    if ($model->save()){

                        foreach ($segments as $segment){
                            foreach ($modelsConcept as $modelConcept){
                                if ($segment->id == $modelConcept->id && $segment->name !== $modelConcept->name){

                                    $old_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251")
                                        . '/' . mb_convert_encoding($this->translit($model->project_name), "windows-1251")
                                        . '/segments/' . mb_convert_encoding($this->translit($segment->name), "windows-1251") . '/';

                                    $old_dir = mb_strtolower($old_dir, "windows-1251");

                                    $new_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251")
                                        . '/' . mb_convert_encoding($this->translit($model->project_name), "windows-1251")
                                        . '/segments/' . mb_convert_encoding($this->translit($modelConcept->name), "windows-1251") . '/';

                                    $new_dir = mb_strtolower($new_dir, "windows-1251");

                                    rename($old_dir, $new_dir);
                                }
                            }
                        }


                        $segments_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                            mb_convert_encoding($this->translit($model->project_name) , "windows-1251") . '/segments/';

                        if (!file_exists($segments_dir)){
                            mkdir($segments_dir, 0777);
                        }

                        foreach ($modelsConcept as $modelConcept){
                            $segment_dir = $segments_dir . '/' . mb_convert_encoding($this->translit($modelConcept->name) , "windows-1251") . '/';
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
                                        $segment_dir = $segments_dir . '/' . mb_convert_encoding($this->translit($seg->name) , "windows-1251") . '/';
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
                                        . '/' . mb_strtolower(mb_convert_encoding($this->translit($model->project_name), "windows-1251"),"windows-1251") . '/present files/';


                                    $model->upload($present_files_dir);

                                     Yii::$app->session->setFlash('success', "Проект * {$model->project_name} * обновлен");
                                     return $this->redirect(['view', 'id' => $model->id]);


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
        $user = User::find()->where(['id' => $model->user_id])->one();
        $_user = Yii::$app->user->identity;


        if (!User::isUserDev(Yii::$app->user->identity['username'])) {

            //Удаление доступно только проектанту, который создал данную модель
            if ($user->id != $_user['id']){
                Yii::$app->session->setFlash('error', 'У Вас нет прав на данное действие!');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

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
        $pathDelete = \Yii::getAlias(UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251")
            . '/' . mb_strtolower(mb_convert_encoding($this->translit($model->project_name), "windows-1251"),"windows-1251"));
        if (file_exists($pathDelete)){
            $this->delTree($pathDelete);
        }
        /*-----------------------------------------------*/


        PreFiles::deleteAll(['project_id' => $model->id]);
        Authors::deleteAll(['project_id' => $model->id]);
        Segment::deleteAll(['project_id' => $model->id]);


        Yii::$app->session->setFlash('error', 'Проект "' . $this->findModel($id)->project_name . '" удален');

        $model->delete();

        return $this->redirect(['index', 'id' => $user->id]);
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


    public function actionResult($id)
    {

        $segments = Segment::find()->where(['project_id' => $id])->with(['interview', 'problems'])->all();

        $businessModels = [];

        foreach ($segments as $i => $segment) {

            if ($segment->interview && $segment->problems) {

                $problems = GenerationProblem::find()->where(['segment_id' => $segment->id])->with(['gcps'])->all();

                foreach ($problems as $problem) {

                    if ($segment->interview && $segment->problems && $problem->gcps) {

                        $gcps = Gcp::find()->where(['problem_id' => $problem->id])->with(['mvps'])->all();

                        foreach ($gcps as $gcp) {

                            if ($segment->interview && $segment->problems && $problem->gcps && $gcp->mvps){

                                $mvps = Mvp::find()->where(['gcp_id' => $gcp->id])->with(['businessModel'])->all();

                                foreach ($mvps as $mvp) {

                                    if ($segment->interview && $segment->problems && $problem->gcps && $gcp->mvps && $mvp->businessModel) {

                                        $businessModels[] = $mvp->businessModel;
                                    }

                                    if ((empty($mvp->confirm) && empty($mvp->businessModel))
                                        || (empty($mvp->confirm) || empty($mvp->businessModel))) {

                                        $businessModel = new BusinessModel();
                                        $businessModel->mvp_id = $mvp->id;
                                        $businessModel->gcp_id = $gcp->id;
                                        $businessModel->problem_id = $problem->id;
                                        $businessModel->segment_id = $segment->id;
                                        $businessModels[] = $businessModel;
                                    }
                                }
                            }
                            if ((empty($gcp->confirm) && empty($gcp->mvps))
                                || (empty($gcp->confirm) || empty($gcp->mvps))) {

                                $businessModel = new BusinessModel();
                                $businessModel->gcp_id = $gcp->id;
                                $businessModel->problem_id = $problem->id;
                                $businessModel->segment_id = $segment->id;
                                $businessModels[] = $businessModel;
                            }
                        }
                    }
                    if ((empty($problem->confirm) && empty($problem->gcps))
                        || (empty($problem->confirm) || empty($problem->gcps))) {

                        $businessModel = new BusinessModel();
                        $businessModel->problem_id = $problem->id;
                        $businessModel->segment_id = $segment->id;
                        $businessModels[] = $businessModel;
                    }
                }

            }if ((empty($segment->interview) || empty($segment->problems))
                || (empty($segment->interview) && empty($segment->problems))){

                $businessModel = new BusinessModel();
                $businessModel->segment_id = $segment->id;
                $businessModels[] = $businessModel;
            }
        }




        //Добавление номера сегмента
        $j = 0;
        foreach ($businessModels as $k => $businessModel) {
            if ($businessModels[$k]->segment->id !== $businessModels[$k-1]->segment->id) {
                $j++;
                $businessModels[$k]->segment->name = $j . '. ' . $businessModels[$k]->segment->name;
            }else {
                $businessModels[$k]->segment->name = $businessModels[$k-1]->segment->name;
            }
        }


        //Добавление номера ГПС
        foreach ($businessModels as $k => $businessModel) {

            if ($businessModels[$k]->problem->title !== '' && $businessModels[$k]->problem) {

                $arrS = explode('. ' . $businessModels[$k]->problem->segment->name, $businessModels[$k]->segment->name);
                $numberSegment = $arrS[0];

                $arrP = explode('ГПС ', $businessModels[$k]->problem->title);
                $numberProblem = $arrP[1];

                $businessModels[$k]->problem->title = 'ГПС ' . $numberSegment . '.' . $numberProblem;
            }
        }


        //Добавление номера ГЦП
        foreach ($businessModels as $k => $businessModel) {

            if ($businessModels[$k]->problem->gcps) {

                $arrP = explode('ГПС ', $businessModels[$k]->problem->title);
                $numberProblem = $arrP[1];

                $arrG = explode('ГЦП ', $businessModels[$k]->gcp->title);
                $numberGcp = $arrG[1];

                $businessModels[$k]->gcp->title = 'ГЦП ' . $numberProblem . '.' . $numberGcp;
            }
        }


        //Добавление номера сегмента ГMVP
        foreach ($businessModels as $k => $businessModel) {

            if ($businessModels[$k]->gcp->mvps) {

                $arrP = explode('ГЦП ', $businessModels[$k]->gcp->title);
                $numberGcp = $arrP[1];

                $arrG = explode('ГMVP ', $businessModels[$k]->gmvp->title);
                $numberMvp = $arrG[1];

                $businessModels[$k]->gmvp->title = 'ГMVP ' . $numberGcp . '.' . $numberMvp;
            }
        }




        foreach ($businessModels as $k => $businessModel) {

            if ($businessModels[$k]->problem->gcps) {
                $i = 0;
                foreach ($businessModels[$k]->problem->gcps as $gcp) {
                    //Если id следующего ГЦП равно id предыдущего, то выполняем следующее
                    if ($businessModels[$k+1]->gcp->id === $businessModels[$k]->gcp->id) {

                        $i++;
                        if ($i > 1) {
                            $businessModels[$k+1]->gcp->title = '';
                            $businessModels[$k+1]->gcp->date_create = null;
                            $businessModels[$k+1]->gcp->date_confirm = null;
                        }
                    }
                }
            }

            if ($businessModels[$k]->segment->problems) {
                $i = 0;
                foreach ($businessModels[$k]->segment->problems as $problem) {
                    //Если id следующего ГПС равно id предыдущего, то выполняем следующее
                    if ($businessModels[$k+1]->problem->id === $businessModels[$k]->problem->id) {

                        $i++;
                        if ($i > 1) {
                            $businessModels[$k+1]->problem->title = '';
                            $businessModels[$k+1]->problem->date_gps = null;
                            $businessModels[$k+1]->problem->date_confirm = null;
                        }
                    }
                }
            }
        }



        //debug($businessModels);

        /*foreach ($mvps as $mvp) {
            //debug($mvp->valueProposition->exist_confirm);
        }*/

        $dataProvider = new ArrayDataProvider([
            'allModels' => $businessModels,
            /*'pagination' => [
                'pageSize' => 100,
            ],*/
            'pagination' => false,
            'sort' => false,
        ]);



        return $this->render('result', [
            'dataProvider' => $dataProvider,
            'project' => Projects::findOne($id),
            ]
        );

    }
}
