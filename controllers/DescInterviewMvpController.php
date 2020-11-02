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
use yii\base\Model;
use yii\web\NotFoundHttpException;


class DescInterviewMvpController extends AppController
{

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\HttpException
     */
    public function beforeAction($action)
    {

        if (in_array($action->id, ['update']) || in_array($action->id, ['delete'])){

            $model = DescInterviewMvp::findOne(Yii::$app->request->get());
            $respond = RespondsMvp::find()->where(['id' => $model->responds_mvp_id])->one();
            $confirmMvp = ConfirmMvp::find()->where(['id' => $respond->confirm_mvp_id])->one();
            $mvp = Mvp::find()->where(['id' => $confirmMvp->mvp_id])->one();
            $project = Projects::find()->where(['id' => $mvp->project->id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){

            $respond = RespondsMvp::findOne(Yii::$app->request->get());
            $confirmMvp = ConfirmMvp::find()->where(['id' => $respond->confirm_mvp_id])->one();
            $mvp = Mvp::find()->where(['id' => $confirmMvp->mvp_id])->one();
            $project = Projects::find()->where(['id' => $mvp->project->id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }else{
            return parent::beforeAction($action);
        }

    }


    /**
     * @param $id
     * @return DescInterviewMvp|array|bool
     */
    public function actionCreate($id)
    {
        $model = new DescInterviewMvp();
        $model->responds_mvp_id = $id;
        $respond = RespondsMvp::findOne($id);

        //Если у респондента уже есть интервью, то отменить действие
        if ($respond->descInterview){
            return false;
        }

        $confirmMvp = ConfirmMvp::find()->where(['id' => $respond->confirm_mvp_id])->one();
        $answers = $respond->answers;
        $mvp = Mvp::find()->where(['id' => $confirmMvp->mvp_id])->one();
        $confirmGcp = ConfirmGcp::find()->where(['id' => $mvp->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();


        if(Yii::$app->request->isAjax) {

            if (Model::loadMultiple($answers, Yii::$app->request->post()) && Model::validateMultiple($answers)) {

                foreach ($answers as $answer) {
                    $answer->save(false);
                }

                if ($model->load(Yii::$app->request->post())) {

                    if ($model->save()) {

                        $project->updated_at = time();

                        if ($project->save()){

                            $response = $model;
                            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            \Yii::$app->response->data = $response;
                            return $response;
                        } else {

                            $response = ['error' => true];
                            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            \Yii::$app->response->data = $response;
                            return $response;
                        }
                    }else {

                        $response = ['error' => true];
                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;
                    }
                }
            }
        }
    }


    /**
     * @param $id
     * @return DescInterviewMvp|array
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $respond = RespondsMvp::find()->where(['id' => $model->responds_mvp_id])->one();
        $confirmMvp = ConfirmMvp::find()->where(['id' => $respond->confirm_mvp_id])->one();

        $answers = $respond->answers;
        $mvp = Mvp::find()->where(['id' => $confirmMvp->mvp_id])->one();
        $confirmGcp = ConfirmGcp::find()->where(['id' => $mvp->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();


        if(Yii::$app->request->isAjax) {

            if (Model::loadMultiple($answers, Yii::$app->request->post()) && Model::validateMultiple($answers)) {

                foreach ($answers as $answer) {
                    $answer->save(false);
                }

                if ($model->load(Yii::$app->request->post())) {

                    if ($model->save()) {

                        $project->updated_at = time();

                        if ($project->save()){

                            $response =  $model;
                            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            \Yii::$app->response->data = $response;
                            return $response;
                        } else {

                            $response = ['error' => true];
                            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            \Yii::$app->response->data = $response;
                            return $response;
                        }
                    }else {

                        $response = ['error' => true];
                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;
                    }
                }
            }
        }
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
        $project->updated_at = time();
        $user = User::find()->where(['id' => $project->user_id])->one();
        $_user = Yii::$app->user->identity;

        if (!User::isUserDev(Yii::$app->user->identity['username'])) {

            //Удаление доступно только проектанту, который создал данную модель
            if ($user->id != $_user['id']){
                Yii::$app->session->setFlash('error', 'У Вас нет прав на данное действие!');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        if ($model->delete()) {
            $project->save();
        }

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
