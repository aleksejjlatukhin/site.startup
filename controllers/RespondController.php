<?php

namespace app\controllers;

use app\models\AnswersQuestionsConfirmSegment;
use app\models\DescInterview;
use app\models\forms\CreateRespondForm;
use app\models\forms\FormUpdateConfirmSegment;
use app\models\Interview;
use app\models\Projects;
use app\models\Segment;
use app\models\forms\UpdateRespondForm;
use app\models\User;
use Yii;
use app\models\Respond;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

class RespondController extends AppController
{

    public function beforeAction($action)
    {

        if (in_array($action->id, ['update']) || in_array($action->id, ['delete'])){

            $respond = Respond::findOne(Yii::$app->request->get());
            $interview = Interview::find()->where(['id' => $respond->interview_id])->one();
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){

            $interview = Interview::findOne(Yii::$app->request->get('id'));
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if ($project->user_id == Yii::$app->user->id || User::isUserAdmin(Yii::$app->user->identity['username'])
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

        $count_models = Respond::find()->where(['interview_id' => $id])->count();

        //Кол-во респондентов, у кот-х заполнены данные
        $count_exist_data_respond = Respond::find()->where(['interview_id' => $id])->andWhere(['not', ['info_respond' => '']])
            ->andWhere(['not', ['date_plan' => null]])->andWhere(['not', ['place_interview' => '']])->count();

        //Кол-во респондентов, у кот-х существует интервью
        $count_exist_data_descInterview = Respond::find()->with('descInterview')
            ->leftJoin('desc_interview', '`desc_interview`.`respond_id` = `responds`.`id`')
            ->where(['interview_id' => $id])->andWhere(['not', ['desc_interview.id' => null]])->count();

        if(Yii::$app->request->isAjax) {
            if (($count_exist_data_respond == $count_models) || ($count_exist_data_descInterview > 0)) {

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


    public function actionGetDataCreateForm($id)
    {
        $interview = Interview::findOne($id);
        $model = new CreateRespondForm();

        if(Yii::$app->request->isAjax) {

            $response = ['renderAjax' => $this->renderAjax('create', ['interview' => $interview, 'model' => $model])];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
    }


    /**
     * @param $id
     * @return array
     */
    public function actionCreate($id)
    {
        $interview = Interview::findOne($id);
        $segment = Segment::findOne(['id' => $interview->segment_id]);
        $project = Projects::findOne(['id' => $segment->project_id]);
        $count_models = Respond::find()->where(['interview_id' => $id])->count();
        $limit_count_respond = Respond::LIMIT_COUNT;
        $newRespond = new CreateRespondForm();
        $newRespond->interview_id = $id;

        if ($newRespond->load(Yii::$app->request->post()))
        {
            if(Yii::$app->request->isAjax) {

                if ($count_models < $limit_count_respond) {

                    if ($newRespond->validate(['name'])) {

                        if ($newRespond = $newRespond->create()) {

                            $newRespond->addAnswersForNewRespond();

                            $interview->count_respond = $interview->count_respond + 1;
                            $interview->save();

                            $responds = Respond::findAll(['interview_id' => $id]);
                            $page = floor((count($responds) - 1) / 10) + 1;

                            $response =  [
                                'newRespond' => $newRespond,
                                'responds' => $responds,
                                'page' => $page,
                                'interview_id' => $id,
                                'ajax_data_confirm' => $this->renderAjax('/interview/ajax_data_confirm', ['model' => Interview::findOne($id), 'formUpdateConfirmSegment' => new FormUpdateConfirmSegment($id), 'project' => $project]),
                            ];

                            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            \Yii::$app->response->data = $response;
                            return $response;
                        }
                    } else {
                        $response = ['error' => true];
                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;
                    }
                } else {
                    $response = ['limit_count_respond' => true];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
    }


    public function actionGetDataUpdateForm($id)
    {
        $model = new UpdateRespondForm($id);

        if(Yii::$app->request->isAjax) {

            $response = ['renderAjax' => $this->renderAjax('update', ['model' => $model])];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
    }


    /**
     * @param $id
     * @return Respond|array
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $updateRespondForm = new UpdateRespondForm($id);
        $interview = Interview::findOne(['id' => $model->interview_id]);

        if ($updateRespondForm->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($updateRespondForm->validate(['name'])){

                    if ($updateRespondForm->updateRespond()){

                        $response = ['interview_id' => $interview->id];
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
    }


    /**
     * @param $id
     * @return Respond
     * @throws NotFoundHttpException
     */
    public function actionGetDataModel($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->isAjax) {

            $response = $model;
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
    }


    public function actionGetQueryResponds($id, $page)
    {
        $model = Interview::findOne($id);
        $queryResponds = Respond::find()->where(['interview_id' => $id]);
        $pagesResponds = new Pagination(['totalCount' => $queryResponds->count(), 'page' => ($page - 1), 'pageSize' => 10]);
        $pagesResponds->pageSizeParam = false; //убираем параметр $per-page
        $responds = $queryResponds->offset($pagesResponds->offset)->limit(10)->all();

        if(Yii::$app->request->isAjax) {

            $response = ['ajax_data_responds' => $this->renderAjax('view_ajax', ['model' => $model, 'responds' => $responds, 'pagesResponds' => $pagesResponds])];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @param $id
     * @return array|bool
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete ($id) {

        $model = $this->findModel($id);
        $descInterview = DescInterview::findOne(['respond_id' => $model->id]);
        $answers = AnswersQuestionsConfirmSegment::findAll(['respond_id' => $id]);
        $interview = Interview::findOne(['id' => $model->interview_id]);
        $segment = Segment::findOne(['id' => $interview->segment_id]);
        $project = Projects::findOne(['id' => $segment->project_id]);
        $user = User::findOne(['id' => $project->user_id]);
        $count_responds = Respond::find()->where(['interview_id' => $interview->id])->count();

        if (Yii::$app->request->isAjax){

            if ($count_responds == 1){

                $response = ['zero_value_responds' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }

            elseif ($interview->count_respond == $interview->count_positive){

                $response = ['number_less_than_allowed' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }

            else {

                if ($descInterview) {
                    $descInterview->delete();
                }

                foreach ($answers as $answer){
                    $answer->delete();
                }

                $del_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                    mb_convert_encoding($this->translit($project->project_name), "windows-1251") . '/segments/' .
                    mb_convert_encoding($this->translit($segment->name), "windows-1251") . '/interviews/' .
                    mb_convert_encoding($this->translit($model->name), "windows-1251") . '/';

                if (file_exists($del_dir)) {
                    $this->delTree($del_dir);
                }


                if ($model->delete()) {
                    $interview->count_respond = $interview->count_respond - 1;
                    $interview->save();
                }

                $response = [
                    'success' => true,
                    'interview_id' => $model->interview_id,
                    'ajax_data_confirm' => $this->renderAjax('/interview/ajax_data_confirm', ['model' => Interview::findOne($model->interview_id), 'formUpdateConfirmSegment' => new FormUpdateConfirmSegment($model->interview_id), 'project' => $project]),
                ];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }

        }
        return false;
    }

    /**
     * Finds the Respond model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Respond the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Respond::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}