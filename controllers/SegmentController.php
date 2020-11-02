<?php

namespace app\controllers;

use app\models\FormCreateSegment;
use app\models\FormUpdateSegment;
use app\models\Projects;
use app\models\SegmentSearch;
use app\models\TypeOfActivityB2B;
use app\models\TypeOfActivityB2C;
use app\models\User;
use Yii;
use app\models\Segment;
use yii\web\NotFoundHttpException;
use app\models\SortForm;
use app\models\SegmentSort;

class SegmentController extends AppController
{

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\HttpException
     */
    public function beforeAction($action)
    {

        if (in_array($action->id, ['update'])){

            $model = Segment::findOne(Yii::$app->request->get());
            $project = Projects::find()->where(['id' => $model->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['index'])){

            $project = Projects::findOne(Yii::$app->request->get('id'));

            //Ограничение доступа к проэктам пользователя
            if (($project->user_id == Yii::$app->user->id) || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){

            $project = Projects::findOne(Yii::$app->request->get());

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
     * @return string
     */
    public function actionIndex($id)
    {

        $project = Projects::findOne($id);
        $user = User::find()->where(['id' => $project->user_id])->one();
        $models = Segment::findAll(['project_id' => $project->id]);
        $searchModel = new SegmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $newSegment = new FormCreateSegment();
        $updateSegments = [];

        foreach ($models as $model){
            $updateSegments[] = new FormUpdateSegment($model->id);
        }

        //Проверка и создание необходимых папок на сервере --- начало ---
        $segments_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
            mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/';

        if (!file_exists($segments_dir)){
            mkdir($segments_dir, 0777);
        }

        foreach ($models as $model){
            $segment_dir = $segments_dir . '/' . mb_convert_encoding($this->translit($model->name) , "windows-1251") . '/';
            $segment_dir = mb_strtolower($segment_dir, "windows-1251");

            if (!file_exists($segment_dir)){
                mkdir($segment_dir, 0777);
            }
        }
        //Проверка и создание необходимых папок на сервере --- конец ---

        //Модель сортировки
        $sortModel = new SortForm();


        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'project' => $project,
            'newSegment' => $newSegment,
            'updateSegments' => $updateSegments,
            'models' => $models,
            'sortModel' => $sortModel,
        ]);
    }


    /**
     * @param $current_id
     * @param $type_sort_id
     * @return array
     */
    public function actionSortingModels($current_id, $type_sort_id)
    {
        $sort = new SegmentSort();

        $content = $sort->showModels($current_id, $type_sort_id);

        if (Yii::$app->request->isAjax) {

            $response =  ['content' => $content];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
    }


    /**
     * @param $id
     * @return array|string|\yii\web\Response
     */
    public function actionCreate($id)
    {
        $model = new FormCreateSegment();
        $model->project_id = $id;
        $project = Projects::find()->where(['id' => $model->project_id])->one();

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model->type_of_interaction_between_subjects == Segment::TYPE_B2C) {

                    if (!empty($model->name) && !empty($model->description) && !empty($model->field_of_activity_b2c) && !empty($model->sort_of_activity_b2c)
                        && !empty($model->specialization_of_activity_b2c) && !empty($model->age_from) && !empty($model->age_to) && !empty($model->gender_consumer) && !empty($model->education_of_consumer)
                        && !empty($model->income_from) && !empty($model->income_to) && !empty($model->quantity_from) && !empty($model->quantity_to) && !empty($model->market_volume_b2c)) {

                        if ($model->validate()) {

                            if ($model->create()) {

                                $project->updated_at = time();

                                if ($project->save()){

                                    $new_segment = Segment::find()->where(['project_id' => $project->id])->orderBy(['id' => SORT_DESC])->one();

                                    $response =  ['success' => true, 'new_segment_id' => $new_segment->id];
                                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                    \Yii::$app->response->data = $response;
                                    return $response;
                                }
                            }

                        }else {

                            //Сегмент с таким именем уже существует
                            $response =  ['segment_already_exists' => true];
                            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            \Yii::$app->response->data = $response;
                            return $response;
                        }

                    } else {

                        //Данные не загружены
                        $response =  ['data_not_loaded' => true];
                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;
                    }



                }elseif ($model->type_of_interaction_between_subjects == Segment::TYPE_B2B){

                    if (!empty($model->name) && !empty($model->description) && !empty($model->field_of_activity_b2b) && !empty($model->sort_of_activity_b2b)
                        && !empty($model->specialization_of_activity_b2b) && !empty($model->company_products) && !empty($model->company_partner) && !empty($model->quantity_from_b2b)
                        && !empty($model->quantity_to_b2b) && !empty($model->income_company_from) && !empty($model->income_company_to) && !empty($model->market_volume_b2b)) {

                        if ($model->validate()) {

                            if ($model->create()) {

                                $project->updated_at = time();

                                if ($project->save()){

                                    $new_segment = Segment::find()->where(['project_id' => $project->id])->orderBy(['id' => SORT_DESC])->one();

                                    $response =  [
                                        'success' => true,
                                        'new_segment_id' => $new_segment->id,
                                        'project_id' => $project->id
                                    ];
                                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                    \Yii::$app->response->data = $response;
                                    return $response;
                                }
                            }

                        }else {

                            //Сегмент с таким именем уже существует
                            $response =  ['segment_already_exists' => true];
                            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            \Yii::$app->response->data = $response;
                            return $response;
                        }

                    } else {

                        //Данные не загружены
                        $response =  ['data_not_loaded' => true];
                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;
                    }
                }
            }
        }
        return false;
    }


    /**
     * @return array
     */
    public function actionListTypeSort()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (isset($_POST['depdrop_parents'])) {

            $parents = $_POST['depdrop_parents'];

            if ($parents != null && $parents[0] != 0) {

                $cat_id = $parents[0];
                $out = SegmentSort::getListTypes($cat_id);
                return ['output' => $out, 'selected' => ''];
            }
        }
        return ['output' => '', 'selected' => ''];
    }


    /**
     * @return array
     */
    public function actionListOfActivitiesForSelectedAreaB2c()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (isset($_POST['depdrop_parents'])) {

            $parents = $_POST['depdrop_parents'];

            if ($parents != null && $parents[0] != 0) {

                $cat_id = $parents[0];
                $out = TypeOfActivityB2C::getListOfActivities($cat_id);
                return ['output' => $out, 'selected' => ''];
            }
        }
        return ['output' => '', 'selected' => ''];
    }


    /**
     * @return array
     */
    public function actionListOfSpecializationsForSelectedActivityB2c()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (isset($_POST['depdrop_parents'])) {

            $parents = $_POST['depdrop_parents'];

            if ($parents != null && $parents[0] != 0) {

                $cat_id = $parents[0];
                $out = TypeOfActivityB2C::getListOfActivities($cat_id);
                return ['output' => $out, 'selected' => ''];
            }
        }
        return ['output' => '', 'selected' => ''];
    }


    /**
     * @return array
     */
    public function actionListOfActivitiesForSelectedAreaB2b()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (isset($_POST['depdrop_parents'])) {

            $parents = $_POST['depdrop_parents'];

            if ($parents != null && $parents[0] != 0) {

                $cat_id = $parents[0];
                $out = TypeOfActivityB2B::getListOfActivities($cat_id);
                return ['output' => $out, 'selected' => ''];
            }
        }

        return ['output' => '', 'selected' => ''];
    }


    /**
     * @return array
     */
    public function actionListOfSpecializationsForSelectedActivityB2b()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (isset($_POST['depdrop_parents'])) {

            $parents = $_POST['depdrop_parents'];

            if ($parents != null && $parents[0] != 0) {

                $out = [
                    ['id' => 'Производственная компания', 'name' => 'Производственная компания'],
                    ['id' => 'Государственное учреждение', 'name' => 'Государственное учреждение'],
                    ['id' => 'Предоставление услуг', 'name' => 'Предоставление услуг'],
                    ['id' => 'Торговая компания', 'name' => 'Торговая компания'],
                    ['id' => 'Консалтинговая компания', 'name' => 'Консалтинговая компания'],
                    ['id' => 'Финансовая компания', 'name' => 'Финансовая компания'],
                    ['id' => 'Организация рекламы', 'name' => 'Организация рекламы'],
                    ['id' => 'Научно-образовательное учреждение', 'name' => 'Научно-образовательное учреждение'],
                    ['id' => 'IT компания', 'name' => 'IT компания'],
                    ['id' => 'Иное', 'name' => 'Иное'],
                ];
                return ['output' => $out, 'selected' => ''];
            }
        }
        return ['output' => '', 'selected' => ''];
    }


    /**
     * @param $id
     * @return array|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $segment = $this->findModel($id);
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $model = new FormUpdateSegment($id);

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {


                if ($model->type_of_interaction_between_subjects == Segment::TYPE_B2C) {

                    if (!empty($model->name) && !empty($model->description) && !empty($model->field_of_activity_b2c) && !empty($model->sort_of_activity_b2c)
                        && !empty($model->specialization_of_activity_b2c) && !empty($model->age_from) && !empty($model->age_to) && !empty($model->gender_consumer) && !empty($model->education_of_consumer)
                        && !empty($model->income_from) && !empty($model->income_to) && !empty($model->quantity_from) && !empty($model->quantity_to) && !empty($model->market_volume_b2c)) {

                        if ($model->validate()) {

                            if ($model->update()) {

                                $project->updated_at = time();

                                if ($project->save()){

                                    $response =  ['success' => true, 'model_id' => $model->id];
                                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                    \Yii::$app->response->data = $response;
                                    return $response;
                                }
                            }
                        }else {

                            //Сегмент с таким именем уже существует
                            $response =  ['segment_already_exists' => true];
                            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            \Yii::$app->response->data = $response;
                            return $response;
                        }
                    } else {

                        //Данные не загружены
                        $response =  ['data_not_loaded' => true];
                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;
                    }

                }elseif ($model->type_of_interaction_between_subjects == Segment::TYPE_B2B) {

                    if (!empty($model->name) && !empty($model->description) && !empty($model->field_of_activity_b2b) && !empty($model->sort_of_activity_b2b)
                        && !empty($model->specialization_of_activity_b2b) && !empty($model->company_products) && !empty($model->company_partner) && !empty($model->quantity_from_b2b)
                        && !empty($model->quantity_to_b2b) && !empty($model->income_company_from) && !empty($model->income_company_to) && !empty($model->market_volume_b2b)) {

                        if ($model->validate()) {

                            if ($model->update()) {

                                if ($project->save()){

                                    $response =  ['success' => true, 'model_id' => $model->id];
                                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                    \Yii::$app->response->data = $response;
                                    return $response;
                                }
                            }
                        }else {

                            //Сегмент с таким именем уже существует
                            $response =  ['segment_already_exists' => true];
                            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            \Yii::$app->response->data = $response;
                            return $response;
                        }
                    } else {

                        //Данные не загружены
                        $response =  ['data_not_loaded' => true];
                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;
                    }
                }
            }
        }
    }

    /**
     * Deletes an existing Segment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    /*public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $project = Projects::find()->where(['id' => $model->project_id])->one();
        $user = User::find()->where(['id' => $project->user_id])->one();
        $_user = Yii::$app->user->identity;

        //Удаление доступно только проектанту, который создал данную модель
        if ($user->id != $_user['id']){
            Yii::$app->session->setFlash('error', 'У Вас нет прав на данное действие!');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $project->updated_at = time();
        $interview = Interview::find()->where(['segment_id' => $model->id])->one();
        $responds = Respond::find()->where(['interview_id' => $interview->id])->all();
        $generationProblems = GenerationProblem::find()->where(['interview_id' => $interview->id])->all();

        if ($project->save()) {

            Yii::$app->session->setFlash('error', "Сегмент {$this->findModel($id)->name} удален");

            foreach ($responds as $respond){
                $descInterview = $respond->descInterview;

                if ($descInterview->interview_file !== null){
                    unlink('upload/interviews/' . $descInterview->interview_file);
                }

                if (!empty($descInterview)){
                    $descInterview->delete();
                }
            }

            if (!empty($interview->feedbacks)){
                foreach ($interview->feedbacks as $feedback) {
                    if ($feedback->feedback_file !== null){
                        unlink('upload/feedbacks/' . $feedback->feedback_file);
                    }
                }
            }


            if (!empty($generationProblems)){
                foreach ($generationProblems as $generationProblem){
                    if (!empty($generationProblem->confirm)){
                        $confirmProblem = $generationProblem->confirm;

                        if (!empty($confirmProblem->feedbacks)){
                            $feedbacksConfirm = $confirmProblem->feedbacks;
                            foreach ($feedbacksConfirm as $feedbackConfirm){
                                if ($feedbackConfirm->feedback_file !== null){
                                    unlink('upload/feedbacks-confirm/' . $feedbackConfirm->feedback_file);
                                }
                            }
                            FeedbackExpertConfirm::deleteAll(['confirm_problem_id' => $confirmProblem->id]);
                        }


                        if (!empty($confirmProblem->responds)){
                            $respondsConfirm = $confirmProblem->responds;
                            foreach ($respondsConfirm as $respondConfirm){
                                if (!empty($respondConfirm->descInterview)){
                                    $descInterviewConfirm = $respondConfirm->descInterview;
                                    if ($descInterviewConfirm->interview_file !== null){
                                        unlink('upload/interviews-confirm/' . $descInterviewConfirm->interview_file);
                                    }
                                    $descInterviewConfirm->delete();
                                }
                            }
                            RespondsConfirm::deleteAll(['confirm_problem_id' => $confirmProblem->id]);
                        }

                        $confirmProblem->delete();
                    }
                }
            }


            Questions::deleteAll(['interview_id' => $interview->id]);
            Respond::deleteAll(['interview_id' => $interview->id]);
            FeedbackExpert::deleteAll(['interview_id' => $interview->id]);
            GenerationProblem::deleteAll(['interview_id' => $interview->id]);

            $pathDelete = \Yii::getAlias(UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251")
                . '/' . mb_strtolower(mb_convert_encoding($this->translit($project->project_name), "windows-1251"),"windows-1251") .
                '/segments/' . mb_strtolower(mb_convert_encoding($this->translit($model->name), "windows-1251"), "windows-1251"));
            $this->delTree($pathDelete);

            if ($interview){
                $interview->delete();
            }

            $model->delete();

            return $this->redirect(['index', 'id' => $project->id]);

        }
    }*/

    /**
     * Finds the Segment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Segment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Segment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
