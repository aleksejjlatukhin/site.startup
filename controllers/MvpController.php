<?php

namespace app\controllers;

use app\models\ConfirmGcp;
use app\models\ConfirmProblem;
use app\models\forms\FormCreateMvp;
use app\models\Gcp;
use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Projects;
use app\models\Segment;
use app\models\User;
use Yii;
use app\models\Mvp;
use yii\web\NotFoundHttpException;

class MvpController extends AppUserPartController
{

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\HttpException
     */
    public function beforeAction($action)
    {

        if (in_array($action->id, ['update']) || in_array($action->id, ['delete'])){

            $model = Mvp::findOne(Yii::$app->request->get());
            $project = Projects::findOne(['id' => $model->project->id]);

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа к данному действию.');
            }

        }elseif (in_array($action->id, ['create'])){

            $confirmGcp = ConfirmGcp::findOne(Yii::$app->request->get());
            $gcp = Gcp::findOne(['id' => $confirmGcp->gcp_id]);
            $project = Projects::findOne(['id' => $gcp->project->id]);

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа к данному действию.');
            }

        }elseif (in_array($action->id, ['index'])){

            $confirmGcp = ConfirmGcp::findOne(Yii::$app->request->get());
            $gcp = Gcp::findOne(['id' => $confirmGcp->gcp_id]);
            $project = Projects::findOne(['id' => $gcp->project->id]);

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
        $models = Mvp::find()->where(['confirm_gcp_id' => $id])->all();
        $confirmGcp = ConfirmGcp::findOne($id);
        $gcp = Gcp::findOne(['id' => $confirmGcp->gcp_id]);
        $confirmProblem = ConfirmProblem::findOne(['id' => $gcp->confirm_problem_id]);
        $generationProblem = GenerationProblem::findOne(['id' => $confirmProblem->gps_id]);
        $interview = Interview::findOne(['id' => $generationProblem->interview_id]);
        $segment = Segment::findOne(['id' => $interview->segment_id]);
        $project = Projects::findOne(['id' => $segment->project_id]);

        return $this->render('index', [
            'models' => $models,
            'confirmGcp' => $confirmGcp,
            'gcp' => $gcp,
            'confirmProblem' => $confirmProblem,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }


    public function actionSaveCacheCreationForm($id)
    {
        $confirmGcp = ConfirmGcp::findOne($id);
        $gcp = Gcp::findOne(['id' => $confirmGcp->gcp_id]);
        $problem = GenerationProblem::find()->where(['id' => $gcp->problem_id])->one();
        $segment = Segment::findOne(['id' => $gcp->segment_id]);
        $project = Projects::findOne(['id' => $gcp->project_id]);
        $user = User::findOne(['id' => $project->user_id]);
        $cache = Yii::$app->cache; //Обращаемся к кэшу приложения

        if(Yii::$app->request->isAjax) {

            $data = $_POST; //Массив, который будем записывать в кэш
            $cache->cachePath = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.'/segments/segment-'.$segment->id.
                '/problems/problem-'.$problem->id.'/gcps/gcp-'.$gcp->id.'/mvps/formCreate/';
            $key = 'formCreateMvpCache'; //Формируем ключ
            $cache->set($key, $data, 3600*24*30); //Создаем файл кэша на 30дней
        }
    }

    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     * @throws \yii\base\ErrorException
     */
    public function actionCreate($id)
    {
        $model = new FormCreateMvp();
        $model->confirm_gcp_id = $id;

        if ($model->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax) {

                if ($model->create()) {

                    $response = ['renderAjax' => $this->renderAjax('_index_ajax', [
                        'models' => Mvp::findAll(['confirm_gcp_id' => $id])
                    ])];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
    }


    /**
     * @param $id
     * @return Mvp|array|bool
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $confirmGcp = ConfirmGcp::findOne(['id' => $model->confirm_gcp_id]);

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model->save()){

                    $response = [
                        'renderAjax' => $this->renderAjax('_index_ajax', [
                            'models' => Mvp::findAll(['confirm_gcp_id' => $confirmGcp->id]),
                        ]),
                    ];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
    }


    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionGetHypothesisToUpdate ($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->isAjax) {

            $response = [
                'model' => $model,
                'renderAjax' => $this->renderAjax('update', ['model' => $model]),
            ];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
    }


    /**
     * @param $id
     * @return bool
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\base\ErrorException
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->isAjax) {

            if ($model->deleteStage()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Finds the mvp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Mvp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mvp::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
