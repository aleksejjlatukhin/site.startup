<?php

namespace app\controllers;

use app\models\DescInterview;
use app\models\forms\FormCreateProblem;
use app\models\Interview;
use app\models\Projects;
use app\models\Respond;
use app\models\Segment;
use app\models\User;
use Yii;
use app\models\GenerationProblem;
use yii\web\NotFoundHttpException;


class GenerationProblemController extends AppController
{

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\HttpException
     */
    public function beforeAction($action)
    {

        if (in_array($action->id, ['update']) || in_array($action->id, ['delete'])){

            $model = GenerationProblem::findOne(Yii::$app->request->get());
            $project = Projects::findOne(['id' => $model->project->id]);

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){

            $interview = Interview::findOne(Yii::$app->request->get());
            $segment = Segment::findOne(['id' => $interview->segment_id]);
            $project = Projects::findOne(['id' => $segment->project_id]);

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['index'])){

            $interview = Interview::findOne(Yii::$app->request->get());
            $segment = Segment::findOne(['id' => $interview->segment_id]);
            $project = Projects::findOne(['id' => $segment->project_id]);

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

        $interview = Interview::findOne($id);
        $segment = Segment::findOne(['id' => $interview->segment_id]);
        $project = Projects::findOne(['id' => $segment->project_id]);
        $models = GenerationProblem::findAll(['interview_id' => $id]);

        return $this->render('index', [
            'models' => $models,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }


    public function actionSaveCacheCreationForm($id)
    {
        $confirmSegment = Interview::findOne($id);
        $segment = Segment::findOne(['id' => $confirmSegment->segment_id]);
        $project = Projects::findOne(['id' => $segment->project_id]);
        $user = User::findOne(['id' => $project->user_id]);
        $cache = Yii::$app->cache; //Обращаемся к кэшу приложения

        if(Yii::$app->request->isAjax) {

            $data = $_POST; //Массив, который будем записывать в кэш
            $cache->cachePath = '../runtime/cache/forms/'.mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251").
                '/projects/'.mb_strtolower(mb_convert_encoding($this->translit($project->project_name), "windows-1251"),"windows-1251").
                '/segments/'.mb_strtolower(mb_convert_encoding($this->translit($segment->name), "windows-1251"),"windows-1251").'/problems/formCreate/';
            $key = 'formCreateProblemCache'; //Формируем ключ
            $cache->set($key, $data, 3600*24*30); //Создаем файл кэша на 30дней
        }
    }


    /**
     * @param $id
     * @return array
     */
    public function actionCreate($id)
    {

        $model = new FormCreateProblem();
        $interview = Interview::findOne($id);
        $segment = Segment::findOne(['id' => $interview->segment_id]);
        $project = Projects::findOne(['id' => $segment->project_id]);
        $user = User::findOne(['id' => $project->user_id]);
        $cache = Yii::$app->cache;

        if ($model->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax) {

                if ($model->create($interview->id, $segment->id, $project->id)){

                    //Удаление кэша формы создания
                    $cache->cachePath = '../runtime/cache/forms/'.mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251").
                        '/projects/'.mb_strtolower(mb_convert_encoding($this->translit($project->project_name), "windows-1251"),"windows-1251").
                        '/segments/'.mb_strtolower(mb_convert_encoding($this->translit($segment->name), "windows-1251"),"windows-1251").'/problems/formCreate/';
                    if ($cache->exists('formCreateProblemCache')) $cache->delete('formCreateProblemCache');

                    $response = [
                        'renderAjax' => $this->renderAjax('_index_ajax', [
                            'models' => GenerationProblem::findAll(['interview_id' => $id]),
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
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $interview = Interview::findOne(['id' => $model->interview_id]);

        if ($model->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax) {

                if ($model->save()) {

                    $response = [
                        'renderAjax' => $this->renderAjax('_index_ajax', [
                            'models' => GenerationProblem::findAll(['interview_id' => $interview->id]),
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

        //Выбор респондентов, которые являются представителями сегмента
        $responds = Respond::find()->with('descInterview')
            ->leftJoin('desc_interview', '`desc_interview`.`respond_id` = `responds`.`id`')
            ->where(['interview_id' => $model->interview_id, 'desc_interview.status' => '1'])->all();

        if(Yii::$app->request->isAjax) {

            $response = [
                'model' => $model,
                'renderAjax' => $this->renderAjax('update', [
                    'model' => $model,
                    'responds' => $responds
                ]),
            ];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
    }


    /**
     * @param $id
     * @return array
     */
    public function actionGetInterviewRespond ($id)
    {
        $respond = Respond::findOne($id);
        $descInterview = DescInterview::findOne(['respond_id' => $id]);

        if(Yii::$app->request->isAjax) {

            $response = [
                'respond' => $respond,
                'renderAjax' => $this->renderAjax('data_respond', [
                    'respond' => $respond,
                    'descInterview' => $descInterview
                ]),
            ];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
    }



    /**
     * Deletes an existing GenerationProblem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $segment = Segment::findOne(['id' => $model->segment_id]);
        $project = Projects::findOne(['id' => $model->project_id]);
        $user = User::findOne(['id' => $project->user_id]);

        if(Yii::$app->request->isAjax) {

            $pathDelete = \Yii::getAlias(UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251")
                    . '/' . mb_strtolower(mb_convert_encoding($this->translit($project->project_name), "windows-1251"),"windows-1251") .
                    '/segments/' . mb_strtolower(mb_convert_encoding($this->translit($segment->name), "windows-1251"), "windows-1251")) .
                '/generation problems/' . mb_strtolower(mb_convert_encoding($this->translit($model->title) , "windows-1251"), "windows-1251");

            if (file_exists($pathDelete)){
                $this->delTree($pathDelete);
            }

            if ($model->deleteStage()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Finds the GenerationProblem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return GenerationProblem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GenerationProblem::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
