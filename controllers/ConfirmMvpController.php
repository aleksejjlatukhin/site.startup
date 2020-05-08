<?php

namespace app\controllers;

use app\models\ConfirmGcp;
use app\models\ConfirmProblem;
use app\models\Gcp;
use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Mvp;
use app\models\Projects;
use app\models\RespondsGcp;
use app\models\RespondsMvp;
use app\models\Segment;
use app\models\User;
use Yii;
use app\models\ConfirmMvp;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ConfirmMvpController implements the CRUD actions for ConfirmMvp model.
 */
class ConfirmMvpController extends AppController
{

    public function beforeAction($action)
    {

        if (in_array($action->id, ['view'])){

            $model = ConfirmMvp::findOne(Yii::$app->request->get());
            $mvp = Mvp::find()->where(['id' => $model->mvp_id])->one();
            $confirmGcp = ConfirmGcp::find()->where(['id' => $mvp->confirm_gcp_id])->one();
            $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
            $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
            $problem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
            $interview = Interview::find()->where(['id' => $problem->interview_id])->one();
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['update'])){

            $model = ConfirmMvp::findOne(Yii::$app->request->get());
            $mvp = Mvp::find()->where(['id' => $model->mvp_id])->one();
            $confirmGcp = ConfirmGcp::find()->where(['id' => $mvp->confirm_gcp_id])->one();
            $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
            $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
            $problem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
            $interview = Interview::find()->where(['id' => $problem->interview_id])->one();
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){

            $mvp = Mvp::findOne(Yii::$app->request->get());
            $confirmGcp = ConfirmGcp::find()->where(['id' => $mvp->confirm_gcp_id])->one();
            $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
            $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
            $problem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
            $interview = Interview::find()->where(['id' => $problem->interview_id])->one();
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

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
     * Lists all ConfirmMvp models.
     * @return mixed
     */
    /*public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ConfirmMvp::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }*/

    /**
     * Displays a single ConfirmMvp model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $mvp = Mvp::find()->where(['id' => $model->mvp_id])->one();
        $confirmGcp = ConfirmGcp::find()->where(['id' => $mvp->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $responds = RespondsMvp::find()->where(['confirm_mvp_id' => $id])->all();

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


        $data_responds = 0;
        $data_desc = 0;
        foreach ($responds as $respond){
            if (!empty($respond->name) && !empty($respond->info_respond)){
                $respond->exist_respond = 1;
                $data_responds++;
                if (!empty($respond->descInterview)){
                    $data_desc++;
                }
            }else{
                $respond->exist_respond = 0;
            }
        }


        return $this->render('view', [
            'model' => $model,
            'mvp' => $mvp,
            'confirmGcp' => $confirmGcp,
            'gcp' => $gcp,
            'confirmProblem' => $confirmProblem,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
            'responds' => $responds,
            'data_responds' => $data_responds,
            'data_desc' => $data_desc,
        ]);
    }


    public function actionNotExistConfirm($id)
    {
        $model = ConfirmMvp::findOne($id);
        $mvp = Mvp::find()->where(['id' => $model->mvp_id])->one();
        $confirmGcp = ConfirmGcp::find()->where(['id' => $mvp->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $mvp->exist_confirm = 0;
        $mvp->date_confirm = date('Y:m:d');

        if ($mvp->save()){

            $project->update_at = date('Y:m:d');
            if ($project->save()){
                return $this->redirect(['mvp/index', 'id' => $confirmGcp->id]);
            }
        }
    }


    public function actionExistConfirm($id)
    {
        $model = ConfirmMvp::findOne($id);
        $mvp = Mvp::find()->where(['id' => $model->mvp_id])->one();
        $confirmGcp = ConfirmGcp::find()->where(['id' => $mvp->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $mvp->exist_confirm = 1;
        $mvp->date_confirm = date('Y:m:d');
        $mvp->date_time_confirm = date('Y-m-d H:i:s');

        if ($mvp->save()){

            $project->update_at = date('Y:m:d');
            if ($project->save()){
                return $this->redirect(['business-model/create', 'id' => $model->id]);
            }
        }
    }

    /**
     * Creates a new ConfirmMvp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new ConfirmMvp();
        $model->mvp_id = $id;
        $mvp = Mvp::findOne($id);
        $confirmGcp = ConfirmGcp::find()->where(['id' => $mvp->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $user = User::find()->where(['id' => $project->user_id])->one();
        $_user = Yii::$app->user->identity;

        if (!User::isUserDev(Yii::$app->user->identity['username'])) {

            //Действие доступно только проектанту, который создал данную модель
            if ($user->id != $_user['id']){
                Yii::$app->session->setFlash('error', 'У Вас нет прав на данное действие!');
                return $this->redirect(['mvp/view', 'id' => $mvp->id]);
            }
        }


        if (!empty($mvp->confirm)){
            return $this->redirect(['view', 'id' => $mvp->confirm->id]);
        }

        $responds = RespondsGcp::find()->where(['confirm_gcp_id' => $confirmGcp->id])->all();
        foreach ($responds as $respond){
            if ($respond->descInterview->status == 1){
                $respondsPre[] = $respond;
            }
        }

        $model->count_respond = count($respondsPre);

        if ($model->load(Yii::$app->request->post())) {

            if ($model->count_respond >= $model->count_positive){

                if ($model->save()) {

                    foreach ($responds as $respond) {
                        if ($respond->descInterview->status == 1){

                            $respondConfirm = new RespondsMvp();
                            $respondConfirm->confirm_mvp_id = $model->id;
                            $respondConfirm->name = $respond->name;
                            $respondConfirm->info_respond = $respond->info_respond;
                            $respondConfirm->email = $respond->email;
                            $respondConfirm->save();
                        }
                    }

                    $mvp_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                        mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
                        mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/generation problems/'
                        . mb_convert_encoding($this->translit($generationProblem->title) , "windows-1251") . '/gcps/'
                        . mb_convert_encoding($this->translit($gcp->title) , "windows-1251") . '/mvps/'
                        . mb_convert_encoding($this->translit($mvp->title) , "windows-1251");

                    $mvp_dir = mb_strtolower($mvp_dir, "windows-1251");

                    if (!file_exists($mvp_dir)){
                        mkdir($mvp_dir, 0777);
                    }

                    $feedbacks_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                        mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
                        mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/generation problems/'
                        . mb_convert_encoding($this->translit($generationProblem->title) , "windows-1251") . '/gcps/'
                        . mb_convert_encoding($this->translit($gcp->title) , "windows-1251") . '/mvps/'
                        . mb_convert_encoding($this->translit($mvp->title) , "windows-1251") . '/feedbacks-confirm/';

                    $feedbacks_dir = mb_strtolower($feedbacks_dir, "windows-1251");

                    if (!file_exists($feedbacks_dir)){
                        mkdir($feedbacks_dir, 0777);
                    }

                    /*for ($i = 1; $i <= $model->count_respond; $i++ )
                    {
                        $newRespond[$i] = new RespondsGcp();
                        $newRespond[$i]->confirm_gcp_id = $model->id;
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
     * Updates an existing ConfirmMvp model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $mvp = Mvp::find()->where(['id' => $model->mvp_id])->one();
        $confirmGcp = ConfirmGcp::find()->where(['id' => $mvp->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $user = User::find()->where(['id' => $project->user_id])->one();
        $_user = Yii::$app->user->identity;

        if (!User::isUserDev(Yii::$app->user->identity['username'])) {

            //Действие доступно только проектанту, который создал данную модель
            if ($user->id != $_user['id']){
                Yii::$app->session->setFlash('error', 'У Вас нет прав на данное действие!');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }


        $responds = RespondsMvp::find()->where(['confirm_mvp_id' => $id])->all();

        if ($model->load(Yii::$app->request->post())) {

            if ($model->count_respond >= $model->count_positive) {

                if ($model->save()) {

                    /*if ((count($responds) + 1) <= $model->count_respond) {
                        for ($count = count($responds) + 1; $count <= $model->count_respond; $count++) {
                            $newRespond[$count] = new RespondsGcp();
                            $newRespond[$count]->confirm_gcp_id = $model->id;
                            $newRespond[$count]->name = 'Респондент ' . $count;
                            $newRespond[$count]->save();
                        }
                    } else {
                        $minus = count($responds) - $model->count_respond;
                        $respond = RespondsGcp::find()->orderBy(['id' => SORT_DESC])->limit($minus)->all();
                        foreach ($respond as $item) {
                            $item->delete();
                        }
                    }*/


                    $project->update_at = date('Y:m:d');

                    if ($project->save()) {

                        Yii::$app->session->setFlash('success', "Данные для подтверждения обновлены!");
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }
            } else {
                Yii::$app->session->setFlash('error', "Количество респондентов не должно быть меньше количества позитивных интервью!");
            }
        }

        return $this->render('update', [
            'model' => $model,
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
     * Deletes an existing ConfirmMvp model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    /*public function actionDelete($id)
    {
        $model =$this->findModel($id);
        $mvp = Mvp::find()->where(['id' => $model->mvp_id])->one();
        $confirmGcp = ConfirmGcp::find()->where(['id' => $mvp->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $user = User::find()->where(['id' => $project->user_id])->one();
        $_user = Yii::$app->user->identity;

        if (!User::isUserDev(Yii::$app->user->identity['username'])) {

            //Удаление доступно только проектанту, который создал данную модель
            if ($user->id != $_user['id']){
                Yii::$app->session->setFlash('error', 'У Вас нет прав на данное действие!');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $model->delete();

        return $this->redirect(['index']);
    }*/

    /**
     * Finds the ConfirmMvp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ConfirmMvp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ConfirmMvp::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
