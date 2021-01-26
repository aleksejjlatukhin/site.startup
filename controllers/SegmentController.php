<?php

namespace app\controllers;

use app\models\forms\FormCreateSegment;
use app\models\forms\FormUpdateSegment;
use app\models\Projects;
use app\models\Roadmap;
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

        if (in_array($action->id, ['update']) || in_array($action->id, ['delete'])){

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
        $models = Segment::findAll(['project_id' => $project->id]);
        $sortModel = new SortForm();

        return $this->render('index', [
            'project' => $project,
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

        if (Yii::$app->request->isAjax) {

            $response =  ['renderAjax' => $this->renderAjax('_index_ajax', [
                'models' => $sort->fetchModels($current_id, $type_sort_id)
                ])
            ];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
    }


    //Создание файла и запись в него состояния формы создания сегмента
    public function actionSaveFileCreationForm($id)
    {
        $project = Projects::findOne($id);
        $user = User::findOne(['id' => $project->user_id]);

        if(Yii::$app->request->isAjax) {

            $segments_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/';

            if (!file_exists($segments_dir)){
                mkdir($segments_dir, 0777);
            }

            $form_creation_dir = $segments_dir . '/__segment__form__creation__file/';
            $form_creation_dir = mb_strtolower($form_creation_dir, "windows-1251");

            if (!file_exists($form_creation_dir)){
                mkdir($form_creation_dir, 0777);
            }

            // строка, которую будем записывать
            $string = '';
            if (isset($_POST['FormCreateSegment'])){
                foreach ($_POST['FormCreateSegment'] as $key=>$value){
                    $string .= '&FormCreateSegment['.$key.']='.$value;
                }
                // добавим дату изменения файла
                $string .= '&FormCreateSegment[updated_at]='.time();
            }
            // открываем файл, если файл не существует,
            // делается попытка создать его
            $fp = fopen($form_creation_dir . 'form-creation-file.txt', 'w');
            // записываем в файл текст
            fwrite($fp, $string);
            // закрываем
            fclose($fp);

            return true;
        }
    }


    public function actionGetHypothesisToCreate ($id)
    {
        $project = Projects::findOne($id);
        $user = User::findOne(['id' => $project->user_id]);
        $model = new FormCreateSegment();

        if(Yii::$app->request->isAjax) {

            $path_file = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/__segment__form__creation__file/form-creation-file.txt';

            if (file_exists($path_file)){

                $response = [
                    'renderAjax' => $this->renderAjax('create', [
                        'model' => $model,
                        'project' => $project,
                    ]),
                    'file_form_creation' => file_get_contents($path_file, FILE_USE_INCLUDE_PATH),
                ];

            } else {

                $response = [
                    'renderAjax' => $this->renderAjax('create', [
                        'model' => $model,
                        'project' => $project
                    ]),
                ];
            }
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
    }


    /**
     * @param $id
     * @return array|bool
     */
    public function actionCreate($id)
    {
        $model = new FormCreateSegment();
        $model->project_id = $id;
        $project = Projects::findOne(['id' => $model->project_id]);

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model->checkFillingFields() == true) {

                    if ($model->validate(['name'])) {

                        if ($model->create()) {

                            $type_sort_id = $_POST['type_sort_id'];

                            if ($type_sort_id != '') {

                                $sort = new SegmentSort();

                                $response =  [
                                    'success' => true,
                                    'renderAjax' => $this->renderAjax('_index_ajax', [
                                        'models' => $sort->fetchModels($project->id, $type_sort_id),
                                    ]),
                                ];
                                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                \Yii::$app->response->data = $response;
                                return $response;

                            } else {

                                $response =  [
                                    'success' => true,
                                    'renderAjax' => $this->renderAjax('_index_ajax', [
                                        'models' => Segment::findAll(['project_id' => $project->id]),
                                    ]),
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
        return false;
    }


    public function actionGetHypothesisToUpdate ($id)
    {
        $model = new FormUpdateSegment($id);

        if(Yii::$app->request->isAjax) {

            $response = [
                'model' => $model,
                'renderAjax' => $this->renderAjax('update', ['model' => $model,]),
            ];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
    }

    /**
     * @param $id
     * @return array|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $segment = $this->findModel($id);
        $project = Projects::findOne(['id' => $segment->project_id]);
        $model = new FormUpdateSegment($id);

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model->checkFillingFields() == true) {

                    if ($model->validate(['name'])) {

                        if ($model->update()) {

                            $type_sort_id = $_POST['type_sort_id'];

                            if ($type_sort_id != '') {

                                $sort = new SegmentSort();

                                $response =  [
                                    'success' => true,
                                    'renderAjax' => $this->renderAjax('_index_ajax', [
                                        'models' => $sort->fetchModels($project->id, $type_sort_id),
                                    ]),
                                ];
                                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                \Yii::$app->response->data = $response;
                                return $response;

                            } else {

                                $response =  [
                                    'success' => true,
                                    'renderAjax' => $this->renderAjax('_index_ajax', [
                                        'models' => Segment::findAll(['project_id' => $project->id]),
                                    ]),
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
     * @return array|bool
     */
    public function actionShowAllInformation ($id)
    {
        $segment = Segment::findOne($id);

        if(Yii::$app->request->isAjax) {

            $response = [
                'renderAjax' => $this->renderAjax('all-information', ['segment' => $segment]),
                'segment' => $segment,
            ];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }



    /**
     * @param $id
     * @return array|bool
     */
    public function actionShowRoadmap ($id)
    {
        $roadmap = new Roadmap($id);
        $segment = Segment::findOne($id);

        if(Yii::$app->request->isAjax) {

            $response = [
                'renderAjax' => $this->renderAjax('roadmap', ['roadmap' => $roadmap]),
                'segment' => $segment,
                ];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }



    /**
     * Deletes an existing Segment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $project = Projects::findOne(['id' => $model->project_id]);
        $user = User::findOne(['id' => $project->user_id]);

        if(Yii::$app->request->isAjax) {

            $pathDelete = \Yii::getAlias(UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251")
                    . '/' . mb_strtolower(mb_convert_encoding($this->translit($project->project_name), "windows-1251"),"windows-1251") .
                    '/segments/' . mb_strtolower(mb_convert_encoding($this->translit($model->name), "windows-1251"), "windows-1251"));

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
