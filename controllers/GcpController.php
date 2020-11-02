<?php

namespace app\controllers;

use app\models\ConfirmProblem;
use app\models\FormCreateGcp;
use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Projects;
use app\models\Segment;
use app\models\User;
use Yii;
use app\models\Gcp;
use yii\web\NotFoundHttpException;


class GcpController extends AppController
{

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\HttpException
     */
    public function beforeAction($action)
    {

        if (in_array($action->id, ['update']) || in_array($action->id, ['delete'])){

            $model = Gcp::findOne(Yii::$app->request->get());
            $project = Projects::find()->where(['id' => $model->project->id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){

            $confirmProblem = ConfirmProblem::findOne(Yii::$app->request->get());
            $problem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
            $project = Projects::find()->where(['id' => $problem->project->id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['index'])){

            $confirmProblem = ConfirmProblem::findOne(Yii::$app->request->get());
            $problem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
            $project = Projects::find()->where(['id' => $problem->project->id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

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
     * @return string
     */
    public function actionIndex($id)
    {
        $models = Gcp::find()->where(['confirm_problem_id' => $id])->all();
        $confirmProblem = ConfirmProblem::findOne($id);
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $formCreateGcp = new FormCreateGcp();

        return $this->render('index', [
            'models' => $models,
            'confirmProblem' => $confirmProblem,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
            'formCreateGcp' => $formCreateGcp,
        ]);
    }


    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionCreate($id)
    {
        $formCreateGcp = new FormCreateGcp();
        $confirmProblem = ConfirmProblem::findOne($id);
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        if ($formCreateGcp->load(Yii::$app->request->post())) {

            if ($formCreateGcp->create($id, $generationProblem->id, $segment->id, $project->id)){

                $project->updated_at = time();

                if ($project->save()){

                    return $this->redirect(['/gcp/index', 'id' => $id]);
                }
            }
        }
    }


    /**
     * @param $id
     * @return Gcp|array|bool
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $confirmProblem = ConfirmProblem::find()->where(['id' => $model->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();


        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model->save()){

                    $project->updated_at = time();

                    if ($project->save()){

                        $response = $model;
                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;

                    }else{

                        $response = ['error' => true];
                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;
                    }

                }else{

                    $response = ['error' => true];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }

        return false;
    }

    /**
     * Deletes an existing Gcp model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    /*public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $confirmProblem = ConfirmProblem::find()->where(['id' => $model->confirm_problem_id])->one();
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

        if ($project->save()){

            Yii::$app->session->setFlash('error', '"' . $model->title . '" удалена!');

            if ($model->delete()){

                $models = Gcp::find()->where(['confirm_problem_id' => $confirmProblem->id])->all();
                $j = 0;
                foreach ($models as $item){
                    $j++;
                    $item->title = 'ГЦП ' . $j;
                    $item->save();
                }

                return $this->redirect(['index', 'id' => $confirmProblem->id]);
            }
        }
    }*/

    /**
     * Finds the Gcp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Gcp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Gcp::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
