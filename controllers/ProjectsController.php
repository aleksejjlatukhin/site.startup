<?php

namespace app\controllers;

use app\models\Authors;
use app\models\BusinessModel;
use app\models\ConfirmMvp;
use app\models\Gcp;
use app\models\GenerationProblem;
use app\models\Mvp;
use app\models\PreFiles;
use app\models\ProjectSort;
use app\models\Roadmap;
use app\models\Segment;
use app\models\SortForm;
use app\models\User;
use kartik\mpdf\Pdf;
use Yii;
use app\models\Projects;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\web\Response;


class ProjectsController extends AppController
{

    /**
     * @param $action
     * @return bool
     * @throws HttpException
     */
    public function beforeAction($action)
    {

        if (in_array($action->id, ['result']) || in_array($action->id, ['report']) || in_array($action->id, ['upshot'])){

            $model = Projects::findOne(Yii::$app->request->get());

            /*Ограничение доступа к проэктам пользователя*/
            if (($model->user_id == Yii::$app->user->id) || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['mpdf-business-model'])){

            $businessModel = BusinessModel::findOne(Yii::$app->request->get());
            $model = $businessModel->project;

            /*Ограничение доступа к проэктам пользователя*/
            if (($model->user_id == Yii::$app->user->id) || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){


            if (User::isUserAdmin(Yii::$app->user->identity['username']) || User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');

            }else {

                $user = User::findOne(Yii::$app->request->get());

                /*Ограничение доступа к проэктам пользователя*/
                if ($user->id == Yii::$app->user->id){

                    return parent::beforeAction($action);

                }else{

                    if (User::isUserDev(Yii::$app->user->identity['username'])){

                        return parent::beforeAction($action);

                    }else {

                        throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
                    }
                }
            }

        }elseif (in_array($action->id, ['index'])){

            $user = User::findOne(Yii::$app->request->get());

            /*Ограничение доступа к проэктам пользователя*/
            if (($user->id == Yii::$app->user->id) || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['update']) || in_array($action->id, ['delete'])){

            $project = Projects::findOne(Yii::$app->request->get());
            $user = User::findOne(['id' => $project->user_id]);

            /*Ограничение доступа к проэктам пользователя*/
            if (($user->id == Yii::$app->user->id)  || User::isUserDev(Yii::$app->user->identity['username'])){

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
        $user = User::findOne($id);
        $models = Projects::findAll(['user_id' => $id]);
        $new_author = new Authors();
        $sortModel = new SortForm();

        return $this->render('index', [
            'user' => $user,
            'models' => $models,
            'new_author' => $new_author,
            'sortModel' => $sortModel,
        ]);
    }


    public function actionGetHypothesisToCreate ($id)
    {
        $user = User::findOne($id);
        $model = new Projects();
        $author = new Authors();

        if(Yii::$app->request->isAjax) {

            $response = [
                'renderAjax' => $this->renderAjax('create', [
                    'user' => $user,
                    'model' => $model,
                    'author' => $author
                ]),
            ];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
    }


    public function actionGetHypothesisToUpdate ($id)
    {
        $model = Projects::findOne($id);
        $workers = Authors::findAll(['project_id' => $id]);

        if(Yii::$app->request->isAjax) {

            $response = [
                'renderAjax' => $this->renderAjax('update', [
                    'model' => $model,
                    'workers' => $workers
                ]),
            ];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
    }


    /**
     * @param $current_id
     * @param $type_sort_id
     * @return array
     */
    public function actionSortingModels($current_id, $type_sort_id)
    {
        $sort = new ProjectSort();

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


    /**
     * @param $id
     * @return \yii\console\Response|Response
     */
    public function actionDownload($id)
    {
        $model = PreFiles::findOne($id);
        $project = Projects::find()->where(['id' => $model->project_id])->one();
        $user = User::find()->where(['id' => $project->user_id])->one();

        $path = \Yii::getAlias('upload/'. mb_strtolower(mb_convert_encoding($user['username'], "windows-1251"),"windows-1251")
            . '/' . mb_strtolower(mb_convert_encoding($this->translit($project->project_name), "windows-1251"),"windows-1251") . '/present files/');

        $file = $path . $model->server_file;

        if (file_exists($file)) {

            $project->updated_at = time();
            $project->save();

            return \Yii::$app->response->sendFile($file, $model->file_name);
        }
    }


    /**
     * @param $id
     * @return array|Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteFile($id)
    {
        $model = PreFiles::findOne($id);
        $project = Projects::find()->where(['id' => $model->project_id])->one();
        $user = User::find()->where(['id' => $project->user_id])->one();

        $path = \Yii::getAlias('upload/'. mb_strtolower(mb_convert_encoding($user['username'], "windows-1251"),"windows-1251")
            . '/' . mb_strtolower(mb_convert_encoding($this->translit($project->project_name), "windows-1251"),"windows-1251") . '/present files/');

        unlink($path . $model->server_file);

        if($model->delete()) {

            $models = PreFiles::findAll(['project_id' => $project->id]);

            if (Yii::$app->request->isAjax)
            {
                $response =  [
                    'success' => true,
                    'count_files' => count($models),
                    'project_id' => $project->id,
                ];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;

            }else{
                return $this->redirect(['/projects/index', 'id' => $user->id]);
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
                $out = ProjectSort::getListTypes($cat_id);
                return ['output' => $out, 'selected' => ''];
            }
        }
        return ['output' => '', 'selected' => ''];
    }


    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function actionCreate($id)
    {
        $model = new Projects();
        $model->user_id = $id;
        $user = User::findOne($id);

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                //Преобразование даты в число
                if ($model->patent_date){
                    $model->patent_date = strtotime($model->patent_date);
                }
                if ($model->register_date){
                    $model->register_date = strtotime($model->register_date);
                }
                if ($model->invest_date){
                    $model->invest_date = strtotime($model->invest_date);
                }
                if ($model->date_of_announcement){
                    $model->date_of_announcement = strtotime($model->date_of_announcement);
                }

                //Проверка на совпадение по названию проекта у данного пользователя
                if ($model->validate(['project_name'])) {

                    if ($model->save()){

                        //Загрузка участников команды проекта (Authors)
                        $model->saveAuthors();

                        //Создание дирректорий проекта
                        if ($present_files_dir = $model->createDirectories($user)) {

                            //Загрузка презентационных файлов
                            $model->present_files = UploadedFile::getInstances($model, 'present_files');
                            $model->upload($present_files_dir);

                            //Проверка наличия сортировки
                            $type_sort_id = $_POST['type_sort_id'];

                            if ($type_sort_id != '') {

                                $sort = new ProjectSort();

                                $response =  [
                                    'success' => true,
                                    'renderAjax' => $this->renderAjax('_index_ajax', [
                                        'models' => $sort->fetchModels($user->id, $type_sort_id),
                                    ]),
                                ];
                                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                \Yii::$app->response->data = $response;
                                return $response;

                            } else {

                                $response =  [
                                    'success' => true,
                                    'renderAjax' => $this->renderAjax('_index_ajax', [
                                        'models' => Projects::findAll(['user_id' => $user->id]),
                                    ]),
                                ];
                                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                \Yii::$app->response->data = $response;
                                return $response;
                            }
                        }
                    }
                }else{

                    //Проект с таким именем уже существует
                    $response =  ['project_already_exists' => true];
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
     * @throws \yii\base\Exception
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $user = User::find()->where(['id' => $model->user_id])->one();

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                /*Преобразование даты в число*/
                if ($model->patent_date){
                    $model->patent_date = strtotime($model->patent_date);
                }
                if ($model->register_date){
                    $model->register_date = strtotime($model->register_date);
                }
                if ($model->invest_date){
                    $model->invest_date = strtotime($model->invest_date);
                }
                if ($model->date_of_announcement){
                    $model->date_of_announcement = strtotime($model->date_of_announcement);
                }

                //Проверка на совпадение по названию проекта у данного пользователя
                if ($model->validate(['project_name']) && $model->updateProjectDirectory()) {

                    if ($model->save()){

                        //Загрузка участников команды проекта (Authors)
                        $model->saveAuthors();

                        //Проверка существования дирректорий проекта
                        if ($present_files_dir = $model->createDirectories($user)) {

                            $model->present_files = UploadedFile::getInstances($model, 'present_files');
                            $model->upload($present_files_dir);

                            //Проверка наличия сортировки
                            $type_sort_id = $_POST['type_sort_id'];

                            if ($type_sort_id != '') {

                                $sort = new ProjectSort();

                                $response =  [
                                    'success' => true,
                                    'renderAjax' => $this->renderAjax('_index_ajax', [
                                        'models' => $sort->fetchModels($user->id, $type_sort_id),
                                    ]),
                                ];
                                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                \Yii::$app->response->data = $response;
                                return $response;

                            } else {

                                $response =  [
                                    'success' => true,
                                    'renderAjax' => $this->renderAjax('_index_ajax', [
                                        'models' => Projects::findAll(['user_id' => $user->id]),
                                    ]),
                                ];
                                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                \Yii::$app->response->data = $response;
                                return $response;
                            }
                        }
                    }
                } else{

                    //Проект с таким именем уже существует
                    $response =  ['project_already_exists' => true];
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
     */
    public function actionShowAllInformation ($id)
    {
        $project = Projects::findOne($id);

        if(Yii::$app->request->isAjax) {

            $response = [
                'renderAjax' => $this->renderAjax('all-information', ['project' => $project]),
                'project' => $project,
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
        $project = Projects::findOne($id);
        $roadmaps = [];

        foreach ($project->segments as $i => $segment){
            $roadmaps[$i] = new Roadmap($segment->id);
        }

        if(Yii::$app->request->isAjax) {

            $response = [
                'renderAjax' => $this->renderAjax('roadmap', ['roadmaps' => $roadmaps]),
                'project' => $project,
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
    public function actionResult ($id)
    {
        $project = Projects::findOne($id);
        $segments = Segment::find()->where(['project_id' => $id])->all();

        if(Yii::$app->request->isAjax) {

            $response = [
                'renderAjax' => $this->renderAjax('result', ['project' => $project, 'segments' => $segments]),
                'project' => $project,
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
    public function actionReport ($id)
    {
        $project = Projects::findOne($id);
        $segments = Segment::findAll(['project_id' => $id]);

        foreach ($segments as $s => $segment) {

            $segment->propertyContainer->addProperty('title', 'Сегмент ' . ($s+1));

            foreach ($segment->problems as $p => $problem) {

                $problem->propertyContainer->addProperty('title', 'ГПС ' . ($s+1) . '.' . ($p+1));

                foreach ($problem->gcps as $g => $gcp) {

                    $gcp->propertyContainer->addProperty('title', 'ГЦП ' . ($s+1) . '.' . ($p+1) . '.' . ($g+1));

                    foreach ($gcp->mvps as $m => $mvp) {

                        $mvp->propertyContainer->addProperty('title', 'MVP ' . ($s+1) . '.' . ($p+1) . '.' . ($g+1) . '.' . ($m+1));
                    }
                }
            }
        }

        if(Yii::$app->request->isAjax) {

            $response = [
                'renderAjax' => $this->renderAjax('report', ['segments' => $segments]),
                'project' => $project,
            ];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @param $id
     * @return string
     */
    /*public function actionResultTest($id)
    {

        $segments = Segment::find()->where(['project_id' => $id])->with(['interview', 'problems'])->all();

        $businessModels = [];

        foreach ($segments as $i => $segment) {

            if ($segment->interview && $segment->problems) {

                $problems = GenerationProblem::find()->where(['segment_id' => $segment->id])->with(['gcps'])->all();

                foreach ($problems as $problem) {

                    if ($segment->interview && $segment->problems && $problem->gcps) {

                        $gcps = Gcp::find()->where(['problem_id' => $problem->id])->with(['mvps'])->all();

                        foreach ($gcps as $gcp) {

                            if ($segment->interview && $segment->problems && $problem->gcps && $gcp->mvps){

                                $mvps = Mvp::find()->where(['gcp_id' => $gcp->id])->with(['businessModel'])->all();

                                foreach ($mvps as $mvp) {

                                    if ($segment->interview && $segment->problems && $problem->gcps && $gcp->mvps && $mvp->businessModel) {

                                        $businessModels[] = $mvp->businessModel;
                                    }

                                    if ((empty($mvp->confirm) && empty($mvp->businessModel))
                                        || (empty($mvp->confirm) || empty($mvp->businessModel))) {

                                        $businessModel = new BusinessModel();
                                        $businessModel->mvp_id = $mvp->id;
                                        $businessModel->gcp_id = $gcp->id;
                                        $businessModel->problem_id = $problem->id;
                                        $businessModel->segment_id = $segment->id;
                                        $businessModels[] = $businessModel;
                                    }
                                }
                            }
                            if ((empty($gcp->confirm) && empty($gcp->mvps))
                                || (empty($gcp->confirm) || empty($gcp->mvps))) {

                                $businessModel = new BusinessModel();
                                $businessModel->gcp_id = $gcp->id;
                                $businessModel->problem_id = $problem->id;
                                $businessModel->segment_id = $segment->id;
                                $businessModels[] = $businessModel;
                            }
                        }
                    }
                    if ((empty($problem->confirm) && empty($problem->gcps))
                        || (empty($problem->confirm) || empty($problem->gcps))) {

                        $businessModel = new BusinessModel();
                        $businessModel->problem_id = $problem->id;
                        $businessModel->segment_id = $segment->id;
                        $businessModels[] = $businessModel;
                    }
                }

            }if ((empty($segment->interview) || empty($segment->problems))
                || (empty($segment->interview) && empty($segment->problems))){

                $businessModel = new BusinessModel();
                $businessModel->segment_id = $segment->id;
                $businessModels[] = $businessModel;
            }
        }




        //Добавление номера сегмента
        $j = 0;
        foreach ($businessModels as $k => $businessModel) {
            if ($businessModels[$k]->segment->id !== $businessModels[$k-1]->segment->id) {
                $j++;
                $businessModels[$k]->segment->name = $j . '. ' . $businessModels[$k]->segment->name;
            }else {
                $businessModels[$k]->segment->name = $businessModels[$k-1]->segment->name;
            }
        }


        //Добавление номера ГПС
        foreach ($businessModels as $k => $businessModel) {

            if ($businessModels[$k]->problem->title !== '' && $businessModels[$k]->problem) {

                $arrS = explode('. ' . $businessModels[$k]->problem->segment->name, $businessModels[$k]->segment->name);
                $numberSegment = $arrS[0];

                $arrP = explode('ГПС ', $businessModels[$k]->problem->title);
                $numberProblem = $arrP[1];

                $businessModels[$k]->problem->title = 'ГПС ' . $numberSegment . '.' . $numberProblem;
            }
        }


        //Добавление номера ГЦП
        foreach ($businessModels as $k => $businessModel) {

            if ($businessModels[$k]->problem->gcps) {

                $arrP = explode('ГПС ', $businessModels[$k]->problem->title);
                $numberProblem = $arrP[1];

                $arrG = explode('ГЦП ', $businessModels[$k]->gcp->title);
                $numberGcp = $arrG[1];

                $businessModels[$k]->gcp->title = 'ГЦП ' . $numberProblem . '.' . $numberGcp;
            }
        }


        //Добавление номера сегмента ГMVP
        foreach ($businessModels as $k => $businessModel) {

            if ($businessModels[$k]->gcp->mvps) {

                $arrP = explode('ГЦП ', $businessModels[$k]->gcp->title);
                $numberGcp = $arrP[1];

                $arrG = explode('ГMVP ', $businessModels[$k]->mvp->title);
                $numberMvp = $arrG[1];

                $businessModels[$k]->mvp->title = 'ГMVP ' . $numberGcp . '.' . $numberMvp;
            }
        }




        foreach ($businessModels as $k => $businessModel) {

            if ($businessModels[$k]->problem->gcps) {
                $i = 0;
                foreach ($businessModels[$k]->problem->gcps as $gcp) {
                    //Если id следующего ГЦП равно id предыдущего, то выполняем следующее
                    if ($businessModels[$k+1]->gcp->id === $businessModels[$k]->gcp->id) {

                        $i++;
                        if ($i > 1) {
                            $businessModels[$k+1]->gcp->title = '';
                            $businessModels[$k+1]->gcp->created_at = null;
                            $businessModels[$k+1]->gcp->time_confirm = null;
                        }
                    }
                }
            }

            if ($businessModels[$k]->segment->problems) {
                $i = 0;
                foreach ($businessModels[$k]->segment->problems as $problem) {
                    //Если id следующего ГПС равно id предыдущего, то выполняем следующее
                    if ($businessModels[$k+1]->problem->id === $businessModels[$k]->problem->id) {

                        $i++;
                        if ($i > 1) {
                            $businessModels[$k+1]->problem->title = '';
                            $businessModels[$k+1]->problem->created_at = null;
                            $businessModels[$k+1]->problem->time_confirm = null;
                        }
                    }
                }
            }
        }



        //debug($businessModels);

//        foreach ($mvps as $mvp) {
//            //debug($mvp->valueProposition->exist_confirm);
//        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $businessModels,
            //'pagination' => [
                //'pageSize' => 100,
            //],
            'pagination' => false,
            'sort' => false,
        ]);


        $project = Projects::findOne($id);
        $project_filename = str_replace(' ', '_', $project->project_name);

        return $this->render('result-test', [
            'dataProvider' => $dataProvider,
            'project' => $project,
            'project_filename' => $project_filename,
            ]
        );

    }*/


    /**
     * @param $id
     * @return string
     */
    /*public function actionReportTest ($id) {

        $segments = Segment::find()->where(['project_id' => $id])->with(['interview', 'problems'])->all();

        $statModels = [];

        foreach ($segments as $s => $segment) {

            if (empty($segment->problems)) {

                $newProblem = new GenerationProblem();
                $newProblem->segment_id = $segment->id;
                $newProblem->project_id = $id;
                $newProblem->description = 'У данного сегмента отсутствуют дальнейшие этапы';
                $statModels[] = $newProblem;
            }

            if ($segment->interview && $segment->problems) {

                $problems = GenerationProblem::find()->where(['segment_id' => $segment->id])->with(['gcps'])->all();

                foreach ($problems as $p => $problem) {

                    $problem->description = 'ГПС ' . ($s+1) . '.' . ($p+1) . ': ' . $problem->description;

                    $statModels[] = $problem;

                    if ($segment->interview && $segment->problems && $problem->gcps) {

                        $gcps = Gcp::find()->where(['problem_id' => $problem->id])->with(['mvps'])->all();

                        foreach ($gcps as $g => $gcp) {

                            $gcp->description = 'ГЦП ' . ($s+1) . '.' . ($p+1) . '.' . ($g+1) . ': ' . $gcp->description;

                            $statModels[] = $gcp;

                            if ($segment->interview && $segment->problems && $problem->gcps && $gcp->mvps){

                                $mvps = Mvp::find()->where(['gcp_id' => $gcp->id])->with(['businessModel'])->all();

                                foreach ($mvps as $m => $mvp) {

                                    $mvp->description = 'ГMVP ' . ($s+1) . '.' . ($p+1) . '.' . ($g+1) . '.' . ($m+1) . ': ' . $mvp->description;

                                    $statModels[] = $mvp;
                                }
                            }
                        }
                    }
                }
            }
        }


        $dataProvider = new ArrayDataProvider([
            'allModels' => $statModels,
            //'pagination' => [
                //'pageSize' => 100,
            //],
            'pagination' => false,
            'sort' => false,
        ]);

        $project = Projects::findOne($id);
        $project_filename = str_replace(' ', '_', $project->project_name);

        return $this->render('report-test', [
                'dataProvider' => $dataProvider,
                'project' => $project,
                'project_filename' => $project_filename,
            ]
        );
    }*/


    /**
     * @param $id
     * @return mixed
     * @throws \Mpdf\MpdfException
     * @throws \setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException
     * @throws \setasign\Fpdi\PdfParser\PdfParserException
     * @throws \setasign\Fpdi\PdfParser\Type\PdfTypeException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionMpdfBusinessModel($id) {

        $model = BusinessModel::findOne($id);

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('/business-model/viewpdf', ['model' => $model]);

        $destination = Pdf::DEST_BROWSER;
        //$destination = Pdf::DEST_DOWNLOAD;

        $filename = 'business-model-'. $model->id .'.pdf';

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
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            //'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssFile' => '@app/web/css/style.css',
            // any css to be embedded if required
            'cssInline' => '.business-model-view-export {color: #3c3c3c;};',
            'marginFooter' => 5,
            // call mPDF methods on the fly
            'methods' => [
                'SetTitle' => ['Бизнес-модель PDF'],
                'SetHeader' => ['<div style="color: #3c3c3c;">Бизнес-модель для проекта «'.$model->project->project_name.'»</div>||<div style="color: #3c3c3c;">Сгенерировано: ' . date("H:i d.m.Y") . '</div>'],
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
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteAuthor($id)
    {
        $model = Authors::findOne($id);
        $project = Projects::find()->where(['id' => $model->project_id])->one();

        if ($model){
            $project->updated_at = time();
            $model->delete();
        }
    }


    /**
     * @param $id
     * @return bool
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $user = User::find()->where(['id' => $model->user_id])->one();

        if(Yii::$app->request->isAjax) {

            $pathDelete = \Yii::getAlias(UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251")
                . '/' . mb_strtolower(mb_convert_encoding($this->translit($model->project_name), "windows-1251"),"windows-1251"));

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
     * Finds the Projects model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Projects the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Projects::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
