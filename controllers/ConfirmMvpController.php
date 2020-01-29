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
use Yii;
use app\models\ConfirmMvp;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ConfirmMvpController implements the CRUD actions for ConfirmMvp model.
 */
class ConfirmMvpController extends Controller
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
     * Lists all ConfirmMvp models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ConfirmMvp::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

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
                return $this->redirect(['projects/result', 'id' => $project->id]);
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

        if ($mvp->save()){

            $project->update_at = date('Y:m:d');
            if ($project->save()){
                return $this->redirect(['projects/result', 'id' => $project->id]);
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
        $user = Yii::$app->user->identity;
        $mvp = Mvp::findOne($id);
        $confirmGcp = ConfirmGcp::find()->where(['id' => $mvp->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $model = new ConfirmMvp();
        $model->mvp_id = $id;

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

                    $mvp_dir = UPLOAD . mb_convert_encoding($user['username'], "windows-1251") . '/' .
                        mb_convert_encoding($project->project_name , "windows-1251") . '/segments/'.
                        mb_convert_encoding($segment->name , "windows-1251") .'/generation problems/'
                        . mb_convert_encoding($generationProblem->title , "windows-1251") . '/gcps/'
                        . mb_convert_encoding($gcp->title , "windows-1251") . '/mvps/'
                        . mb_convert_encoding($mvp->title , "windows-1251");

                    $mvp_dir = mb_strtolower($mvp_dir, "windows-1251");

                    if (!file_exists($mvp_dir)){
                        mkdir($mvp_dir, 0777);
                    }

                    $feedbacks_dir = UPLOAD . mb_convert_encoding($user['username'], "windows-1251") . '/' .
                        mb_convert_encoding($project->project_name , "windows-1251") . '/segments/'.
                        mb_convert_encoding($segment->name , "windows-1251") .'/generation problems/'
                        . mb_convert_encoding($generationProblem->title , "windows-1251") . '/gcps/'
                        . mb_convert_encoding($gcp->title , "windows-1251") . '/mvps/'
                        . mb_convert_encoding($mvp->title , "windows-1251") . '/feedbacks-confirm/';

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

                        Yii::$app->session->setFlash('success', "Данные для проведения подтверждения ". $mvp->title ." загружены");
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }
            }else{
                Yii::$app->session->setFlash('error', "Количество респондентов не должно быть меньше количества позитивных интервью!");
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

                        Yii::$app->session->setFlash('success', "Данные для подтверждения " . $mvp->title . " обновлены!");
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
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

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
