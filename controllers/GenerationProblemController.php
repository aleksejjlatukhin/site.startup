<?php

namespace app\controllers;

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

            $interview = Interview::findOne(Yii::$app->request->get());
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['index'])){

            $interview = Interview::findOne(Yii::$app->request->get());
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

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

        $interview = Interview::find()->with('questions')->where(['id' => $id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $models = GenerationProblem::find()->where(['interview_id' => $id])->all();

        //Выбор респондентов, которые являются представителями сегмента
        $responds = Respond::find()->with('descInterview')
            ->leftJoin('desc_interview', '`desc_interview`.`respond_id` = `responds`.`id`')
            ->where(['interview_id' => $id, 'desc_interview.status' => '1'])->all();

        $newProblem = new GenerationProblem();
        $newProblem->interview_id = $id;

        return $this->render('index', [
            'models' => $models,
            'responds' => $responds,
            'newProblem' => $newProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }


    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionCreate($id)
    {

        $model = new GenerationProblem();
        $model->interview_id = $id;
        $last_model = GenerationProblem::find()->where(['interview_id' => $id])->orderBy(['id' => SORT_DESC])->one();
        $last_model_number = explode(' ',$last_model->title)[1];
        $model->title = 'ГПС ' . ($last_model_number + 1);

        $interview = Interview::findOne($id);
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $model->segment_id = $segment->id;
        $model->project_id = $project->id;

        if ($model->load(Yii::$app->request->post())) {
            $model->description = $_POST['GenerationProblem']['description'];

            if ($model->save()){

                $project->updated_at = time();
                if ($project->save()){

                    return $this->redirect(['/generation-problem/index', 'id' => $id]);
                }
            }
        }

    }


    /**
     * @param $id
     * @return GenerationProblem
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $interview = Interview::find()->where(['id' => $model->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();


        if ($model->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax) {

                if ($model->save()) {

                    $project->updated_at = time();
                    if ($project->save()) {

                        $response = $model;
                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;
                    }
                }
            }
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
        $user = User::find()->where(['id' => $project->user_id])->one();

        if(Yii::$app->request->isAjax) {

            $pathDelete = \Yii::getAlias(UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251")
                    . '/' . mb_strtolower(mb_convert_encoding($this->translit($project->project_name), "windows-1251"),"windows-1251") .
                    '/segments/' . mb_strtolower(mb_convert_encoding($this->translit($segment->name), "windows-1251"), "windows-1251")) .
                '/generation problems/' . mb_strtolower(mb_convert_encoding($this->translit($model->title) , "windows-1251"), "windows-1251");

            if (file_exists($pathDelete)){
                $this->delTree($pathDelete);
            }

            $project->updated_at = time();
            $project->save();

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
