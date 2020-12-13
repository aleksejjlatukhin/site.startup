<?php

namespace app\controllers;

use app\models\ConfirmProblem;
use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Projects;
use app\models\RespondsConfirm;
use app\models\Segment;
use app\models\User;
use yii\base\Model;
use Yii;
use app\models\DescInterviewConfirm;
use yii\web\NotFoundHttpException;


class DescInterviewConfirmController extends AppController
{

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\HttpException
     */
    public function beforeAction($action)
    {

        if (in_array($action->id, ['update']) || in_array($action->id, ['delete'])){

            $model = DescInterviewConfirm::findOne(Yii::$app->request->get());
            $respond = RespondsConfirm::find()->where(['id' => $model->responds_confirm_id])->one();
            $confirmProblem = ConfirmProblem::find()->where(['id' => $respond->confirm_problem_id])->one();
            $problem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
            $project = Projects::find()->where(['id' => $problem->project->id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){

            $respond = RespondsConfirm::findOne(Yii::$app->request->get());
            $confirmProblem = ConfirmProblem::find()->where(['id' => $respond->confirm_problem_id])->one();
            $problem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
            $project = Projects::find()->where(['id' => $problem->project->id])->one();

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


    public function actionGetDataCreateForm($id)
    {
        $respond = RespondsConfirm::findOne($id);
        $model = new DescInterviewConfirm();

        if(Yii::$app->request->isAjax) {

            $response = ['renderAjax' => $this->renderAjax('create', ['respond' => $respond, 'model' => $model])];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
    }


    /**
     * @param $id
     * @return DescInterviewConfirm|array|bool
     */
    public function actionCreate($id)
    {
        $model = new DescInterviewConfirm();
        $model->responds_confirm_id = $id;
        $respond = RespondsConfirm::findOne($id);
        $confirmProblem = ConfirmProblem::findOne(['id' => $respond->confirm_problem_id]);
        $answers = $respond->answers;
        $generationProblem = GenerationProblem::findOne(['id' => $confirmProblem->gps_id]);
        $interview = Interview::findOne(['id' => $generationProblem->interview_id]);
        $segment = Segment::findOne(['id' => $interview->segment_id]);
        $project = Projects::findOne(['id' => $segment->project_id]);

        if(Yii::$app->request->isAjax) {

            if (Model::loadMultiple($answers, Yii::$app->request->post()) && Model::validateMultiple($answers)) {

                foreach ($answers as $answer) {
                    $answer->save(false);
                }

                if ($model->load(Yii::$app->request->post())) {

                    if ($model->save()) {

                        $project->updated_at = time();

                        if ($project->save()){

                            $response = ['confirm_problem_id' => $confirmProblem->id];
                            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            \Yii::$app->response->data = $response;
                            return $response;
                        }
                    }
                }
            }
        }
    }


    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionGetDataUpdateForm($id)
    {
        $model = $this->findModel($id);
        $respond = $model->respond;

        if(Yii::$app->request->isAjax) {

            $response = ['renderAjax' => $this->renderAjax('update', ['respond' => $respond, 'model' => $model])];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
    }


    /**
     * @param $id
     * @return DescInterviewConfirm|array
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $respond = RespondsConfirm::findOne(['id' => $model->responds_confirm_id]);
        $confirmProblem = ConfirmProblem::findOne(['id' => $respond->confirm_problem_id]);
        $answers = $respond->answers;
        $generationProblem = GenerationProblem::findOne(['id' => $confirmProblem->gps_id]);
        $interview = Interview::findOne(['id' => $generationProblem->interview_id]);
        $segment = Segment::findOne(['id' => $interview->segment_id]);
        $project = Projects::findOne(['id' => $segment->project_id]);


        if(Yii::$app->request->isAjax) {

            if (Model::loadMultiple($answers, Yii::$app->request->post()) && Model::validateMultiple($answers)) {

                foreach ($answers as $answer) {
                    $answer->save(false);
                }

                if ($model->load(Yii::$app->request->post())) {

                    if ($model->save()) {

                        $project->updated_at = time();

                        if ($project->save()){

                            $response = ['confirm_problem_id' => $confirmProblem->id];
                            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            \Yii::$app->response->data = $response;
                            return $response;
                        }
                    }
                }
            }
        }
    }

    /**
     * Deletes an existing DescInterviewConfirm model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    /*public function actionDelete($id)
    {
        $model = DescInterviewConfirm::find()->where(['responds_confirm_id' => $id])->one();

        $respond = RespondsConfirm::findOne($id);
        $confirmProblem = ConfirmProblem::find()->where(['id' => $respond->confirm_problem_id])->one();
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

        if ($project->save()) {

            Yii::$app->session->setFlash('error', 'Анкета ' . $respond->name . ' удалена!');

            $model->delete();

            return $this->redirect(['responds-confirm/view', 'id' => $respond->id]);
        }
    }*/

    /**
     * Finds the DescInterviewConfirm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return DescInterviewConfirm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DescInterviewConfirm::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
