<?php

namespace app\controllers;

use app\models\AnswersQuestionsConfirmProblem;
use app\models\ConfirmProblem;
use app\models\DescInterviewConfirm;
use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Projects;
use app\models\Segment;
use app\models\forms\UpdateRespondConfirmForm;
use app\models\User;
use Yii;
use app\models\RespondsConfirm;
use yii\web\NotFoundHttpException;

class RespondsConfirmController extends AppController
{

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\HttpException
     */
    public function beforeAction($action)
    {

        if (in_array($action->id, ['update']) || in_array($action->id, ['delete'])){

            $model = RespondsConfirm::findOne(Yii::$app->request->get());
            $confirmProblem = ConfirmProblem::find()->where(['id' => $model->confirm_problem_id])->one();
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

            $confirmProblem = ConfirmProblem::findOne(Yii::$app->request->get());
            $problem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
            $project = Projects::find()->where(['id' => $problem->project->id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

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
     * @return array
     */
    public function actionDataAvailability($id)
    {

        $models = RespondsConfirm::find()->where(['confirm_problem_id' => $id])->all();

        $exist_data_respond = 0;
        $exist_data_descInterview = 0;
        foreach ($models as $model){

            if (!empty($model->info_respond)){
                $exist_data_respond++;
            }
            if (!empty($model->descInterview)){
                $exist_data_descInterview++;
            }
        }

        if(Yii::$app->request->isAjax) {
            if (($exist_data_respond == count($models)) || ($exist_data_descInterview > 0)) {

                $response =  ['success' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;

            }else{

                $response = ['error' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }
        }
    }


    /**
     * @param $id
     * @return array
     */
    public function actionCreate($id)
    {
        $models = RespondsConfirm::find()->where(['confirm_problem_id' => $id])->all();
        $confirmProblem = ConfirmProblem::findOne($id);
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $limit_count_respond = RespondsConfirm::LIMIT_COUNT;

        $newRespond = new RespondsConfirm();
        $newRespond->confirm_problem_id = $id;

        if ($newRespond->load(Yii::$app->request->post())) {

            $kol = 0;
            foreach ($models as $elem){
                if ($newRespond->id != $elem->id && mb_strtolower(str_replace(' ', '', $newRespond->name)) == mb_strtolower(str_replace(' ', '',$elem->name))){
                    $kol++;
                }
            }

            if(Yii::$app->request->isAjax) {

                if (count($models) < $limit_count_respond) {

                    if ($kol == 0) {

                        if ($newRespond->save()) {

                            $newRespond->addAnswersForNewRespond();

                            $confirmProblem->count_respond = $confirmProblem->count_respond + 1;
                            $confirmProblem->save();

                            $project->updated_at = time();

                            if ($project->save()) {

                                $responds = RespondsConfirm::find()->where(['confirm_problem_id' => $id])->all();

                                $response = [
                                    'newRespond' => $newRespond,
                                    'responds' => $responds,
                                ];

                                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                \Yii::$app->response->data = $response;
                                return $response;
                            }
                        }
                    } else {
                        $response = ['error' => true];
                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;
                    }
                }  else {
                    $response = ['limit_count_respond' => true];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }

    }


    /**
     * @param $id
     * @return RespondsConfirm|array
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $updateRespondForm = new UpdateRespondConfirmForm($id);

        $confirmProblem = ConfirmProblem::find()->where(['id' => $model->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $models = RespondsConfirm::find()->where(['confirm_problem_id' => $confirmProblem->id])->all();

        if ($updateRespondForm->load(Yii::$app->request->post())) {

            $kol = 0;
            foreach ($models as $item){
                if ($updateRespondForm->id != $item->id && mb_strtolower(str_replace(' ', '',$updateRespondForm->name)) == mb_strtolower(str_replace(' ', '',$item->name))){
                    $kol++;
                }
            }

            if(Yii::$app->request->isAjax) {

                if ($kol == 0){

                    if ($updateRespondForm->updateRespond($model)){

                        $project->updated_at = time();

                        if ($project->save()){

                            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            \Yii::$app->response->data = $model;
                            return $model;
                        }
                    }
                }else{

                    $response = ['error' => true];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
    }


    /**
     * @param $id
     * @return array|bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete ($id) {

        $model = RespondsConfirm::findOne($id);
        $descInterview = DescInterviewConfirm::find()->where(['responds_confirm_id' => $model->id])->one();
        $answers = AnswersQuestionsConfirmProblem::find()->where(['respond_id' => $id])->all();

        $confirmProblem = ConfirmProblem::find()->where(['id' => $model->confirm_problem_id])->one();
        $responds = RespondsConfirm::find()->where(['confirm_problem_id' => $confirmProblem->id])->all();

        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        if (Yii::$app->request->isAjax){

            if (count($responds) == 1){

                $response = ['zero_value_responds' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }


            if ($confirmProblem->count_respond == $confirmProblem->count_positive){

                $response = ['number_less_than_allowed' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }

            $project->updated_at = time();

            if ($project->save()) {

                if ($descInterview) {
                    $descInterview->delete();
                }

                foreach ($answers as $answer){
                    $answer->delete();
                }

                if ($model->delete()) {

                    $confirmProblem->count_respond = $confirmProblem->count_respond - 1;
                    $confirmProblem->save();
                }

                $response = ['success' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }

        }
        return false;
    }

    /**
     * Finds the RespondsConfirm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return RespondsConfirm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RespondsConfirm::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
