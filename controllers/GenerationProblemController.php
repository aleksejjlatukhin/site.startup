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
        $models = GenerationProblem::find()->where(['interview_id' => $id])->all();
        $model->title = 'ГПС ' . (count($models)+1);

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
    /*public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $interview = Interview::find()->where(['id' => $model->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['gps_id' => $model->id])->one();
        $responds = RespondsConfirm::find()->where(['confirm_problem_id' => $confirmProblem->id])->all();
        $project->updated_at = time();
        $user = User::find()->where(['id' => $project->user_id])->one();
        $_user = Yii::$app->user->identity;

        //Удаление доступно только проектанту, который создал данную модель
        if (!User::isUserDev(Yii::$app->user->identity['username'])) {

            //Действие доступно только проектанту, который создал данную модель
            if ($user->id != $_user['id']){
                Yii::$app->session->setFlash('error', 'У Вас нет прав на данное действие!');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }


        if ($project->save()) {

            foreach ($responds as $respond){
                $descInterview = $respond->descInterview;

                if ($descInterview->interview_file !== null){
                    unlink('upload/interviews-confirm/' . $descInterview->interview_file);
                }

                if (!empty($descInterview)){
                    $descInterview->delete();
                }
            }

            if (!empty($confirmProblem->feedbacks)){
                foreach ($confirmProblem->feedbacks as $feedback) {
                    if ($feedback->feedback_file !== null){
                        unlink('upload/feedbacks-confirm/' . $feedback->feedback_file);
                    }
                }
            }

            RespondsConfirm::deleteAll(['confirm_problem_id' => $confirmProblem->id]);
            FeedbackExpertConfirm::deleteAll(['confirm_problem_id' => $confirmProblem->id]);

            Yii::$app->session->setFlash('error', '"' . $model->title . '" удалена!');

            $confirmProblem->delete();

            $model->delete();

            $j = 0;
            foreach ($interview->problems as $item){
                $j++;
                $item->title = 'ГПС ' . $j;
                $item->save();
            }

            return $this->redirect(['interview/view', 'id' => $interview->id]);
        }


    }*/

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
