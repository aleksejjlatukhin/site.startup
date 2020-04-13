<?php

namespace app\controllers;

use app\models\ConfirmGcp;
use app\models\ConfirmProblem;
use app\models\Gcp;
use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Projects;
use app\models\RespondsGcp;
use app\models\Segment;
use app\models\User;
use Yii;
use app\models\DescInterviewGcp;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DescInterviewGcpController implements the CRUD actions for DescInterviewGcp model.
 */
class DescInterviewGcpController extends AppController
{

    public function beforeAction($action)
    {

        if (in_array($action->id, ['view'])){

            $model = DescInterviewGcp::findOne(Yii::$app->request->get());
            $respond = RespondsGcp::find()->where(['id' => $model->responds_gcp_id])->one();
            $confirmGcp = ConfirmGcp::find()->where(['id' => $respond->confirm_gcp_id])->one();
            $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
            $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
            $problem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
            $interview = Interview::find()->where(['id' => $problem->interview_id])->one();
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if ($project->user_id == Yii::$app->user->id || User::isUserAdmin(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['update'])){

            $model = DescInterviewGcp::findOne(Yii::$app->request->get());
            $respond = RespondsGcp::find()->where(['id' => $model->responds_gcp_id])->one();
            $confirmGcp = ConfirmGcp::find()->where(['id' => $respond->confirm_gcp_id])->one();
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

            $respond = RespondsGcp::findOne(Yii::$app->request->get());
            $confirmGcp = ConfirmGcp::find()->where(['id' => $respond->confirm_gcp_id])->one();
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
     * Lists all DescInterviewGcp models.
     * @return mixed
     */
    /*public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => DescInterviewGcp::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }*/

    /**
     * Displays a single DescInterviewGcp model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $respond = RespondsGcp::find()->where(['id' => $model->responds_gcp_id])->one();
        $confirmGcp = ConfirmGcp::find()->where(['id' => $respond->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        return $this->render('view', [
            'model' => $model,
            'respond' => $respond,
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
     * Creates a new DescInterviewGcp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new DescInterviewGcp();
        $model->responds_gcp_id = $id;
        $model->date_fact = date('Y:m:d');

        $respond = RespondsGcp::findOne($id);
        $confirmGcp = ConfirmGcp::find()->where(['id' => $respond->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $user = User::find()->where(['id' => $project->user_id])->one();
        $_user = Yii::$app->user->identity;

        //Действие доступно только проектанту, который создал данную модель
        if ($user->id != $_user['id']){
            Yii::$app->session->setFlash('error', 'У Вас нет прав на данное действие!');
            return $this->redirect(['responds-gcp/view', 'id' => $respond->id]);
        }

        if (!empty($respond->descInterview)){
            return $this->redirect(['view', 'id' => $respond->descInterview->id]);
        }

        if ($respond->name == null || $respond->info_respond == null){
            Yii::$app->session->setFlash('error', "Необходимо заполнить данные о респонденте!");
            return $this->redirect(['responds-gcp/view', 'id' => $id]);
        }

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {

                $project->update_at = date('Y:m:d');
                if ($project->save()){
                    Yii::$app->session->setFlash('success', "Анкета добавлена!");
                    return $this->redirect(['responds-gcp/view', 'id' => $model->responds_gcp_id]);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'respond' => $respond,
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
     * Updates an existing DescInterviewGcp model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $respond = RespondsGcp::find()->where(['id' => $model->responds_gcp_id])->one();
        $confirmGcp = ConfirmGcp::find()->where(['id' => $respond->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $user = User::find()->where(['id' => $project->user_id])->one();
        $_user = Yii::$app->user->identity;

        //Действие доступно только проектанту, который создал данную модель
        if ($user->id != $_user['id']){
            Yii::$app->session->setFlash('error', 'У Вас нет прав на данное действие!');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {

                $project->update_at = date('Y:m:d');
                if ($project->save()){
                    Yii::$app->session->setFlash('success', "Анкета обновлена!");
                    return $this->redirect(['responds-gcp/view', 'id' => $model->responds_gcp_id]);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'respond' => $respond,
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
     * Deletes an existing DescInterviewGcp model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    /*public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $respond = RespondsGcp::find()->where(['id' => $model->responds_gcp_id])->one();
        $confirmGcp = ConfirmGcp::find()->where(['id' => $respond->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $user = User::find()->where(['id' => $project->user_id])->one();
        $_user = Yii::$app->user->identity;

        //Удаление доступно только проектанту, который создал данную модель
        if ($user->id != $_user['id']){
            Yii::$app->session->setFlash('error', 'У Вас нет прав на данное действие!');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $model->delete();

        return $this->redirect(['index']);
    }*/

    /**
     * Finds the DescInterviewGcp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return DescInterviewGcp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DescInterviewGcp::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
