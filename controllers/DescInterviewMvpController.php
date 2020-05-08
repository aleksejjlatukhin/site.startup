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
use app\models\RespondsMvp;
use app\models\Segment;
use app\models\User;
use Yii;
use app\models\DescInterviewMvp;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DescInterviewMvpController implements the CRUD actions for DescInterviewMvp model.
 */
class DescInterviewMvpController extends AppController
{

    public function beforeAction($action)
    {

        if (in_array($action->id, ['view'])){

            $model = DescInterviewMvp::findOne(Yii::$app->request->get());
            $respond = RespondsMvp::find()->where(['id' => $model->responds_mvp_id])->one();
            $confirmMvp = ConfirmMvp::find()->where(['id' => $respond->confirm_mvp_id])->one();
            $mvp = Mvp::find()->where(['id' => $confirmMvp->mvp_id])->one();
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

            $model = DescInterviewMvp::findOne(Yii::$app->request->get());
            $respond = RespondsMvp::find()->where(['id' => $model->responds_mvp_id])->one();
            $confirmMvp = ConfirmMvp::find()->where(['id' => $respond->confirm_mvp_id])->one();
            $mvp = Mvp::find()->where(['id' => $confirmMvp->mvp_id])->one();
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

            $respond = RespondsMvp::findOne(Yii::$app->request->get());
            $confirmMvp = ConfirmMvp::find()->where(['id' => $respond->confirm_mvp_id])->one();
            $mvp = Mvp::find()->where(['id' => $confirmMvp->mvp_id])->one();
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
     * Lists all DescInterviewMvp models.
     * @return mixed
     */
    /*public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => DescInterviewMvp::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }*/

    /**
     * Displays a single DescInterviewMvp model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $respond = RespondsMvp::find()->where(['id' => $model->responds_mvp_id])->one();
        $confirmMvp = ConfirmMvp::find()->where(['id' => $respond->confirm_mvp_id])->one();
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
            'respond' => $respond,
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
     * Creates a new DescInterviewMvp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new DescInterviewMvp();
        $model->responds_mvp_id = $id;
        $model->date_fact = date('Y:m:d');

        $respond = RespondsMvp::findOne($id);
        $confirmMvp = ConfirmMvp::find()->where(['id' => $respond->confirm_mvp_id])->one();
        $mvp = Mvp::find()->where(['id' => $confirmMvp->mvp_id])->one();
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
                return $this->redirect(['responds-mvp/view', 'id' => $respond->id]);
            }
        }


        if (!empty($respond->descInterview)){
            return $this->redirect(['view', 'id' => $respond->descInterview->id]);
        }

        if ($respond->name == null || $respond->info_respond == null){
            Yii::$app->session->setFlash('error', "Необходимо заполнить данные о респонденте!");
            return $this->redirect(['responds-mvp/view', 'id' => $id]);
        }

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {

                $project->update_at = date('Y:m:d');
                if ($project->save()){
                    Yii::$app->session->setFlash('success', "Анкета добавлена!");
                    return $this->redirect(['responds-mvp/view', 'id' => $model->responds_mvp_id]);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'respond' => $respond,
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
     * Updates an existing DescInterviewMvp model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $respond = RespondsMvp::find()->where(['id' => $model->responds_mvp_id])->one();
        $confirmMvp = ConfirmMvp::find()->where(['id' => $respond->confirm_mvp_id])->one();
        $mvp = Mvp::find()->where(['id' => $confirmMvp->mvp_id])->one();
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


        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $project->update_at = date('Y:m:d');

            if ($project->save()){
                Yii::$app->session->setFlash('success', "Анкета обновлена!");
                return $this->redirect(['responds-mvp/view', 'id' => $model->responds_mvp_id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'respond' => $respond,
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
     * Deletes an existing DescInterviewMvp model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    /*public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $respond = RespondsMvp::find()->where(['id' => $model->responds_mvp_id])->one();
        $confirmMvp = ConfirmMvp::find()->where(['id' => $respond->confirm_mvp_id])->one();
        $mvp = Mvp::find()->where(['id' => $confirmMvp->mvp_id])->one();
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
     * Finds the DescInterviewMvp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return DescInterviewMvp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DescInterviewMvp::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
