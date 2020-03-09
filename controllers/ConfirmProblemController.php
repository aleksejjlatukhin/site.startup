<?php

namespace app\controllers;

use app\models\FeedbackExpertConfirm;
use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Projects;
use app\models\Respond;
use app\models\RespondsConfirm;
use app\models\Segment;
use Yii;
use app\models\ConfirmProblem;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ConfirmProblemController implements the CRUD actions for ConfirmProblem model.
 */
class ConfirmProblemController extends AppController
{

    public function beforeAction($action)
    {

        if (in_array($action->id, ['view']) || in_array($action->id, ['update']) || in_array($action->id, ['delete'])){

            $model = ConfirmProblem::findOne(Yii::$app->request->get());
            $problem = GenerationProblem::find()->where(['id' => $model->gps_id])->one();
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

            $problem = GenerationProblem::findOne(Yii::$app->request->get());
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
     * Lists all ConfirmProblem models.
     * @return mixed
     */
    /*public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ConfirmProblem::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }*/

    /**
     * Displays a single ConfirmProblem model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = ConfirmProblem::find()->where(['id' => $id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $model->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $responds = RespondsConfirm::find()->where(['confirm_problem_id' => $id])->all();

        foreach ($responds as $respond){
            if (!empty($respond->name) && !empty($respond->info_respond)){
                $respond->exist_respond = 1;
            }else{
                $respond->exist_respond = 0;
            }
        }

        foreach ($responds as $respond){
            if (!empty($respond->descInterview->date_fact) && !empty($respond->descInterview->description)){
                $respond->descInterview->exist_desc = 1;
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => RespondsConfirm::find()->where(['confirm_problem_id' => $id]),
        ]);

        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
            'responds' => $responds,
        ]);
    }


    public function actionNotExistConfirm($id)
    {
        $model = ConfirmProblem::findOne($id);
        $generationProblem = GenerationProblem::find()->where(['id' => $model->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $generationProblem->exist_confirm = 0;
        $generationProblem->date_confirm = null;

        if ($generationProblem->save()){

            $project->update_at = date('Y:m:d');
            if ($project->save()){
                return $this->redirect(['interview/view', 'id' => $interview->id]);
            }
        }
    }


    public function actionExistConfirm($id)
    {
        $model = ConfirmProblem::findOne($id);
        $generationProblem = GenerationProblem::find()->where(['id' => $model->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $generationProblem->exist_confirm = 1;
        $generationProblem->date_confirm = date('Y:m:d');
        $generationProblem->date_time_confirm = date('Y-m-d H:i:s');

        if ($generationProblem->save()){

            $project->update_at = date('Y:m:d');
            if ($project->save()){
                return $this->redirect(['gcp/create', 'id' => $model->id]);
            }
        }
    }

    /**
     * Creates a new ConfirmProblem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $user = Yii::$app->user->identity;
        $model = new ConfirmProblem();
        $model->gps_id = $id;

        $generationProblem = GenerationProblem::findOne($id);
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $responds = Respond::find()->where(['interview_id' => $interview->id])->all();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();


        if (!empty($generationProblem->confirm)){
            return $this->redirect(['view', 'id' => $generationProblem->confirm->id]);
        }


        $countPositive = 0;
        foreach ($responds as $respond){
            if ($respond->descInterview->status == 1){
                $respondsPre[] = $respond;
                $countPositive++;
            }
        }


        if ($countPositive < $interview->count_positive){
            Yii::$app->session->setFlash('error', "Не набрано необходимое количество представителей сегмента!");
            return $this->redirect(['generation-problem/view', 'id' => $generationProblem->id]);
        }


        $model->count_respond = count($respondsPre);

        if ($model->load(Yii::$app->request->post())) {

            if ($model->count_respond >= $model->count_positive){

                if ($model->save()){

                    foreach ($responds as $respond) {
                        if ($respond->descInterview->status == 1){

                            $respondConfirm = new RespondsConfirm();
                            $respondConfirm->confirm_problem_id = $model->id;
                            $respondConfirm->name = $respond->name;
                            $respondConfirm->info_respond = $respond->info_respond;
                            $respondConfirm->email = $respond->email;
                            $respondConfirm->save();
                        }
                    }


                    $gps_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                        mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
                        mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/generation problems/'
                        . mb_convert_encoding($this->translit($generationProblem->title) , "windows-1251");

                    $gps_dir = mb_strtolower($gps_dir, "windows-1251");

                    if (!file_exists($gps_dir)){
                        mkdir($gps_dir, 0777);
                    }


                    $feedbacks_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                        mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
                        mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/generation problems/'
                        . mb_convert_encoding($this->translit($generationProblem->title) , "windows-1251") . '/feedbacks-confirm/';

                    $feedbacks_dir = mb_strtolower($feedbacks_dir, "windows-1251");

                    if (!file_exists($feedbacks_dir)){
                        mkdir($feedbacks_dir, 0777);
                    }



                    /*for ($i = 1; $i <= $model->count_respond; $i++ )
                    {
                        $newRespond[$i] = new RespondsConfirm();
                        $newRespond[$i]->confirm_problem_id = $model->id;
                        $newRespond[$i]->name = 'Респондент ' . $i;
                        $newRespond[$i]->save();
                    }*/


                    $project->update_at = date('Y:m:d');

                    if ($project->save()){

                        Yii::$app->session->setFlash('success', "Данные для подтверждения загружены");
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }
            }else{
                Yii::$app->session->setFlash('error', "Количество позитивных ответов не может быть больше количества респондентов");
            }
        }

        return $this->render('create', [
            'model' => $model,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * Updates an existing ConfirmProblem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = ConfirmProblem::find()->where(['id' => $id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $model->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $responds = RespondsConfirm::find()->where(['confirm_problem_id' => $id])->all();

        if ($model->load(Yii::$app->request->post())) {

            if ($model->count_respond >= $model->count_positive){

                if ($model->save()){

                    /*if ((count($responds)+1) <= $model->count_respond){
                        for ($count = count($responds) + 1; $count <= $model->count_respond; $count++ )
                        {
                            $newRespond[$count] = new RespondsConfirm();
                            $newRespond[$count]->confirm_problem_id = $model->id;
                            $newRespond[$count]->name = 'Респондент ' . $count;
                            $newRespond[$count]->save();
                        }
                    }else{
                        $minus = count($responds) - $model->count_respond;
                        $respond = RespondsConfirm::find()->orderBy(['id' => SORT_DESC])->limit($minus)->all();
                        foreach ($respond as $item)
                        {
                            $item->delete();
                        }
                    }*/


                    $project->update_at = date('Y:m:d');

                    if ($project->save()){

                        Yii::$app->session->setFlash('success', "Данные для подтверждения обновлены!");
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }
            }else{
                Yii::$app->session->setFlash('error', "Количество позитивных ответов не может быть больше количества респондентов");
            }
        }

        return $this->render('update', [
            'model' => $model,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * Deletes an existing ConfirmProblem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $user = Yii::$app->user->identity;
        $model = $this->findModel($id);
        $generationProblem = GenerationProblem::find()->where(['id' => $model->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $responds = RespondsConfirm::find()->where(['confirm_problem_id' => $model->id])->all();
        $project->update_at = date('Y:m:d');

        $gps_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
            mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
            mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/generation problems/'
            . mb_convert_encoding($this->translit($generationProblem->title) , "windows-1251");

        $gps_dir = mb_strtolower($gps_dir, "windows-1251");

        if (file_exists($gps_dir)){
            $this->delTree($gps_dir);
        }

        if ($project->save()){

            foreach ($responds as $respond){

                $descInterview = $respond->descInterview;
                if (!empty($descInterview)){
                    $descInterview->delete();
                }
            }


            RespondsConfirm::deleteAll(['confirm_problem_id' => $id]);
            FeedbackExpertConfirm::deleteAll(['confirm_problem_id' => $id]);

            Yii::$app->session->setFlash('error', "Ваше интервью удалено, создайте новое интервью!");

            $model->delete();

            return $this->redirect(['create', 'id' => $model->gps_id]);
        }
    }

    /**
     * Finds the ConfirmProblem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ConfirmProblem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ConfirmProblem::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
