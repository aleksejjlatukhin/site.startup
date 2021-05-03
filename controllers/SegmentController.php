<?php

namespace app\controllers;

use app\models\forms\FormCreateSegment;
use app\models\forms\FormUpdateSegment;
use app\models\Projects;
use app\models\Roadmap;
use app\models\TypeOfActivityB2B;
use app\models\TypeOfActivityB2C;
use app\models\User;
use kartik\mpdf\Pdf;
use Yii;
use app\models\Segment;
use yii\web\NotFoundHttpException;
use app\models\SortForm;
use app\models\SegmentSort;

class SegmentController extends AppUserPartController
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

        }elseif (in_array($action->id, ['mpdf-segment'])){

            $model = Segment::findOne(Yii::$app->request->get());
            $project = Projects::find()->where(['id' => $model->project_id])->one();

            //Ограничение доступа к проэктам пользователя
            if (($project->user_id == Yii::$app->user->id) || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['index']) || in_array($action->id, ['mpdf-table-segments'])){

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

        if (!$models) return $this->redirect(['/segment/instruction', 'id' => $id]);

        return $this->render('index', [
            'project' => $project,
            'models' => $models,
            'sortModel' => new SortForm(),
        ]);
    }


    /**
     * @param $id
     * @return string
     */
    public function actionInstruction ($id)
    {
        return $this->render('index_first', [
            'project' => Projects::findOne($id),
        ]);
    }


    /**
     * @return bool|string
     */
    public function actionGetInstruction ()
    {
        if(Yii::$app->request->isAjax) {
            $response = $this->renderAjax('instruction');
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @param $current_id
     * @param $type_sort_id
     * @return array|bool
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
        return false;
    }


    /**
     * @param $id
     * @return bool
     */
    public function actionSaveCacheCreationForm($id)
    {
        $project = Projects::findOne($id);
        $user = User::findOne(['id' => $project->user_id]);
        $cache = Yii::$app->cache; //Обращаемся к кэшу приложения

        if(Yii::$app->request->isAjax) {

            $string = ''; //Строка, которую будем записывать в кэш
            if (isset($_POST['FormCreateSegment'])){
                foreach ($_POST['FormCreateSegment'] as $key=>$value){
                    $string .= '&FormCreateSegment['.$key.']='.$value;
                }
            }
            $cache->cachePath = '../runtime/cache/forms/user-'.$user->id. '/projects/project-'.$project->id.'/segments/formCreate/';
            $key = 'formCreateSegmentCache'; //Формируем ключ
            $cache->set($key, $string, 3600*24*30); //Создаем файл кэша на 30дней
        }
        return false;
    }


    /**
     * @param $id
     * @return array|bool
     */
    public function actionGetHypothesisToCreate ($id)
    {
        $project = Projects::findOne($id);
        $user = User::findOne(['id' => $project->user_id]);
        $model = new FormCreateSegment();
        $cache = Yii::$app->cache;

        if(Yii::$app->request->isAjax) {

            $cache->cachePath = '../runtime/cache/forms/user-'.$user->id. '/projects/project-'.$project->id.'/segments/formCreate/';
            $cache_form_creation = $cache->get('formCreateSegmentCache');

            if ($cache_form_creation){

                $response = [
                    'renderAjax' => $this->renderAjax('create', [
                        'model' => $model,
                        'project' => $project,
                    ]),
                    'cache_form_creation' => $cache_form_creation,
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
        return false;
    }


    /**
     * @param $id
     * @return array|bool
     * @throws NotFoundHttpException
     * @throws \yii\base\ErrorException
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
                                    'success' => true, 'count' => Segment::find()->where(['project_id' => $id])->count(),
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


    /**
     * @param $id
     * @return array|bool
     */
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
        return false;
    }


    /**
     * @param $id
     * @return array|bool
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
     * @return mixed
     * @throws \Mpdf\MpdfException
     * @throws \setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException
     * @throws \setasign\Fpdi\PdfParser\PdfParserException
     * @throws \setasign\Fpdi\PdfParser\Type\PdfTypeException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionMpdfSegment ($id) {

        $model = Segment::findOne($id);

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('mpdf_segment', ['segment' => $model]);

        $destination = Pdf::DEST_BROWSER;
        //$destination = Pdf::DEST_DOWNLOAD;

        $filename = 'Сегмент «'.$model->name .'».pdf';

        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            //'format' => Pdf::FORMAT_TABLOID,
            // portrait orientation
            //'orientation' => Pdf::ORIENT_LANDSCAPE,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => $destination,
            'filename' => $filename,
            'content' => $content,
            'cssFile' => '@app/web/css/mpdf-hypothesis-style.css',
            'marginFooter' => 5,
            // call mPDF methods on the fly
            'methods' => [
                'SetTitle' => [$model->name],
                'SetHeader' => ['<div style="color: #3c3c3c;">Сегмент «'.$model->name.'»</div>||<div style="color: #3c3c3c;">Сгенерировано: ' . date("H:i d.m.Y") . '</div>'],
                'SetFooter' => ['<div style="color: #3c3c3c;">Страница {PAGENO}</div>'],
                //'SetSubject' => 'Generating PDF files via yii2-mpdf extension has never been easy',
                //'SetAuthor' => 'Kartik Visweswaran',
                //'SetCreator' => 'Kartik Visweswaran',
                //'SetKeywords' => 'Krajee, Yii2, Export, PDF, MPDF, Output, Privacy, Policy, yii2-mpdf',
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }


    /**
     * @param $id
     * @return mixed
     * @throws \Mpdf\MpdfException
     * @throws \setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException
     * @throws \setasign\Fpdi\PdfParser\PdfParserException
     * @throws \setasign\Fpdi\PdfParser\Type\PdfTypeException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionMpdfTableSegments ($id) {

        $project = Projects::findOne($id);
        $models = $project->segments;

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('mpdf_table_segments', ['models' => $models]);

        $destination = Pdf::DEST_BROWSER;
        //$destination = Pdf::DEST_DOWNLOAD;

        $filename = 'Сегменты проекта «'.$project->project_name .'».pdf';

        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            //'format' => Pdf::FORMAT_TABLOID,
            // portrait orientation
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            //'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => $destination,
            'filename' => $filename,
            'content' => $content,
            'cssFile' => '@app/web/css/mpdf-index-table-hypothesis-style.css',
            'marginFooter' => 5,
            // call mPDF methods on the fly
            'methods' => [
                'SetTitle' => ['Сегменты проекта «'.$project->project_name .'»'],
                'SetHeader' => ['<div style="color: #3c3c3c;">Сегменты проекта «'.$project->project_name.'»</div>||<div style="color: #3c3c3c;">Сгенерировано: ' . date("H:i d.m.Y") . '</div>'],
                'SetFooter' => ['<div style="color: #3c3c3c;">Страница {PAGENO}</div>'],
                //'SetSubject' => 'Generating PDF files via yii2-mpdf extension has never been easy',
                //'SetAuthor' => 'Kartik Visweswaran',
                //'SetCreator' => 'Kartik Visweswaran',
                //'SetKeywords' => 'Krajee, Yii2, Export, PDF, MPDF, Output, Privacy, Policy, yii2-mpdf',
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
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
