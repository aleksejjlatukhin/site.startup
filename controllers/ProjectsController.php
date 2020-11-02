<?php

namespace app\controllers;

use app\models\Authors;
use app\models\BusinessModel;
use app\models\ConfirmMvp;
use app\models\FeedbackExpert;
use app\models\FeedbackExpertConfirm;
use app\models\FeedbackExpertGcp;
use app\models\FeedbackExpertMvp;
use app\models\Gcp;
use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Mvp;
use app\models\PreFiles;
use app\models\ProjectSort;
use app\models\Questions;
use app\models\Respond;
use app\models\RespondsConfirm;
use app\models\RespondsGcp;
use app\models\RespondsMvp;
use app\models\Segment;
use app\models\SortForm;
use app\models\User;
use kartik\mpdf\Pdf;
use Yii;
use app\models\Projects;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\data\ArrayDataProvider;
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
        $models = Projects::find()->where(['user_id' => $id])->all();

        $new_author = new Authors();
        $newModel = new Projects();
        $newModel->user_id = $id;

        $workers = [];
        foreach ($models as $model) {
            $workers[] = Authors::find()->where(['project_id' => $model->id])->all();
        }

        //Модель сортировки
        $sortModel = new SortForm();

        return $this->render('index', [
            'user' => $user,
            'models' => $models,
            'new_author' => $new_author,
            'workers' => $workers,
            'newModel' => $newModel,
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
        $sort = new ProjectSort();

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

            $project->updated_at = time();
            $models = PreFiles::find()->where(['project_id' => $project->id])->all();
            $project->save();

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
     * @return string
     */
    public function actionUpshot($id)
    {
        $model = Projects::findOne($id);
        $segments = Segment::find()->where(['project_id' => $model->id])->all();
        $problems = [];
        $offers = [];
        $mvProducts = [];
        $confirmMvps = [];
        foreach ($segments as $segment){
            $generationProblems = GenerationProblem::find()->where(['interview_id' => $segment->interview->id])->all();
            foreach ($generationProblems as $k => $generationProblem){
                $problems[] = $generationProblem;
                $gcps = Gcp::find()->where(['confirm_problem_id' => $generationProblem->confirm->id])->all();
                foreach ($gcps as $gcp){
                    $offers[] = $gcp;
                    $mvps = Mvp::find()->where(['confirm_gcp_id' => $gcp->confirm->id])->all();
                    foreach ($mvps as $mvp){
                        $mvProducts[] = $mvp;
                        $confMvp = ConfirmMvp::find()->where(['mvp_id' => $mvp->id])->one();
                        $confirmMvps[] = $confMvp;
                    }
                }
            }
        }

        return $this->render('upshot', [
            'model' => $model,
            'segments' => $segments,
            'generationProblems' => $generationProblems,
            'problems' => $problems,
            'offers' => $offers,
            'mvProducts' => $mvProducts,
            'confirmMvps' => $confirmMvps,

        ]);
    }


    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionCreate($id)
    {
        $model = new Projects();
        $model->user_id = $id;

        $models = Projects::find()->where(['user_id' => $id])->all();
        $user = User::findOne(['id' => $id]);

        if ($user->status === User::STATUS_ACTIVE){
            //В зависимости от статуса пользователя
            // создаем папку на сервере, если она ещё не создана
            $user->createDirName();
        }

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


                $countMod = 0;
                foreach ($models as $item) {
                    if (mb_strtolower(str_replace(' ', '', $model->project_name)) == mb_strtolower(str_replace(' ', '', $item->project_name))) {
                        $countMod++;
                    }
                }

                if ($countMod === 0){

                    if ($model->save()){


                        //Загрузка участников команды проекта (Authors)
                        //---Начало---

                        $arr_authors = $_POST['Authors'];
                        $arr_authors = array_values($arr_authors);

                        foreach ($arr_authors as $arr_author) {

                            $worker = new Authors();
                            $worker->fio = $arr_author['fio'];
                            $worker->role = $arr_author['role'];
                            $worker->experience = $arr_author['experience'];
                            $worker->project_id = $model->id;
                            $worker->save();
                        }

                        //Загрузка участников команды проекта (Authors)
                        //---Конец---


                        $user_dir = UPLOAD . mb_convert_encoding($user['username'], "windows-1251") . '/';
                        $user_dir = mb_strtolower($user_dir, "windows-1251");
                        if (!file_exists($user_dir)){
                            mkdir($user_dir, 0777);
                        }

                        $project_dir = $user_dir . '/' . mb_convert_encoding($this->translit($model->project_name) , "windows-1251") . '/';
                        $project_dir = mb_strtolower($project_dir, "windows-1251");
                        if (!file_exists($project_dir)){
                            mkdir($project_dir, 0777);
                        }

                        $present_files_dir = $project_dir . '/present files/';
                        if (!file_exists($present_files_dir)){
                            mkdir($present_files_dir, 0777);
                        }

                        $segments_dir = $project_dir . '/segments/';
                        if (!file_exists($segments_dir)){
                            mkdir($segments_dir, 0777);
                        }

                        $model->present_files = UploadedFile::getInstances($model, 'present_files');

                        $model->upload($present_files_dir);


                        $response =  ['success' => true, 'model_id' => $model->id];
                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;
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
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $user = User::find()->where(['id' => $model->user_id])->one();
        $models = Projects::find()->where(['user_id' => $user['id']])->all();
        $workers = Authors::find()->where(['project_id'=>$id])->all();

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

                $countCon = 0;
                foreach ($models as $item) {
                    if ($model->id !== $item->id && mb_strtolower(str_replace(' ', '', $model->project_name)) == mb_strtolower(str_replace(' ', '', $item->project_name))) {
                        $countCon++;
                    }
                }

                if ($countCon === 0){
                    foreach ($models as $elem){
                        if ($model->id == $elem->id && mb_strtolower(str_replace(' ', '',$model->project_name)) !== mb_strtolower(str_replace(' ', '',$elem->project_name))){

                            $old_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251")
                                . '/' . mb_convert_encoding($this->translit($elem->project_name), "windows-1251") . '/';

                            $old_dir = mb_strtolower($old_dir, "windows-1251");

                            $new_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251")
                                . '/' . mb_convert_encoding($this->translit($model->project_name), "windows-1251") . '/';

                            $new_dir = mb_strtolower($new_dir, "windows-1251");

                            rename($old_dir, $new_dir);
                        }
                    }

                    if ($model->save()){

                        //Загрузка участников команды проекта (Authors)
                        //---Начало---

                        $arr_authors = $_POST['Authors'];
                        $arr_authors = array_values($arr_authors);

                        if (count($arr_authors) > count($workers)) {

                            foreach ($arr_authors as $i => $arr_author) {

                                if (($i+1) <= count($workers)) {
                                    $workers[$i]->fio = $arr_authors[$i]['fio'];
                                    $workers[$i]->role = $arr_authors[$i]['role'];
                                    $workers[$i]->experience = $arr_authors[$i]['experience'];
                                    $workers[$i]->save();
                                } else {
                                    $worker = new Authors();
                                    $worker->fio = $arr_authors[$i]['fio'];
                                    $worker->role = $arr_authors[$i]['role'];
                                    $worker->experience = $arr_authors[$i]['experience'];
                                    $worker->project_id = $model->id;
                                    $worker->save();
                                }
                            }

                        } else {

                            foreach ($arr_authors as $i => $arr_author) {
                                $workers[$i]->fio = $arr_author['fio'];
                                $workers[$i]->role = $arr_author['role'];
                                $workers[$i]->experience = $arr_author['experience'];
                                $workers[$i]->save();
                            }
                        }

                        //Загрузка участников команды проекта (Authors)
                        //---Конец---

                        $segments_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                            mb_convert_encoding($this->translit($model->project_name) , "windows-1251") . '/segments/';

                        if (!file_exists($segments_dir)){
                            mkdir($segments_dir, 0777);
                        }

                        $model->present_files = UploadedFile::getInstances($model, 'present_files');

                        $present_files_dir = UPLOAD . mb_strtolower(mb_convert_encoding($user['username'], "windows-1251"),"windows-1251")
                            . '/' . mb_strtolower(mb_convert_encoding($this->translit($model->project_name), "windows-1251"),"windows-1251") . '/present files/';

                        $model->upload($present_files_dir);

                        $response =  ['success' => true, 'model_id' => $model->id];
                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;
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
     * @return string
     */
    public function actionResult($id)
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

                $arrG = explode('ГMVP ', $businessModels[$k]->gmvp->title);
                $numberMvp = $arrG[1];

                $businessModels[$k]->gmvp->title = 'ГMVP ' . $numberGcp . '.' . $numberMvp;
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
                            $businessModels[$k+1]->gcp->date_create = null;
                            $businessModels[$k+1]->gcp->date_confirm = null;
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
                            $businessModels[$k+1]->problem->date_gps = null;
                            $businessModels[$k+1]->problem->date_confirm = null;
                        }
                    }
                }
            }
        }



        //debug($businessModels);

        /*foreach ($mvps as $mvp) {
            //debug($mvp->valueProposition->exist_confirm);
        }*/

        $dataProvider = new ArrayDataProvider([
            'allModels' => $businessModels,
            /*'pagination' => [
                'pageSize' => 100,
            ],*/
            'pagination' => false,
            'sort' => false,
        ]);


        $project = Projects::findOne($id);
        $project_filename = str_replace(' ', '_', $project->project_name);

        return $this->render('result', [
            'dataProvider' => $dataProvider,
            'project' => $project,
            'project_filename' => $project_filename,
            ]
        );

    }


    /**
     * @param $id
     * @return string
     */
    public function actionReport ($id) {

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
            /*'pagination' => [
                'pageSize' => 100,
            ],*/
            'pagination' => false,
            'sort' => false,
        ]);

        $project = Projects::findOne($id);
        $project_filename = str_replace(' ', '_', $project->project_name);

        return $this->render('report', [
                'dataProvider' => $dataProvider,
                'project' => $project,
                'project_filename' => $project_filename,
            ]
        );
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
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $segments = Segment::find()->where(['project_id' => $model->id])->all();
        $user = User::find()->where(['id' => $model->user_id])->one();
        $_user = Yii::$app->user->identity;


        if (!User::isUserDev(Yii::$app->user->identity['username'])) {

            //Удаление доступно только проектанту, который создал данную модель
            if ($user->id != $_user['id']){
                Yii::$app->session->setFlash('error', 'У Вас нет прав на данное действие!');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        if(!empty($segments)){
            foreach ($segments as $segment){

                $interview = Interview::find()->where(['segment_id' => $segment->id])->one();
                $responds = Respond::find()->where(['interview_id' => $interview->id])->all();

                if(!empty($responds)){
                    foreach ($responds as $respond){
                        if (!empty($respond->descInterview)){
                            $respond->descInterview->delete();
                        }
                    }
                }

                $generationProblems = GenerationProblem::find()->where(['interview_id' => $interview->id])->all();

                if (!empty($generationProblems)){
                    foreach ($generationProblems as $generationProblem){
                        if (!empty($generationProblem->confirm)){
                            $confirmProblem = $generationProblem->confirm;

                            if (!empty($confirmProblem->feedbacks)){
                                FeedbackExpertConfirm::deleteAll(['confirm_problem_id' => $confirmProblem->id]);
                            }


                            if (!empty($confirmProblem->responds)){
                                $respondsConfirm = $confirmProblem->responds;

                                foreach ($respondsConfirm as $respondConfirm){
                                    if (!empty($respondConfirm->descInterview)){
                                        $respondConfirm->descInterview->delete();
                                    }
                                }

                                RespondsConfirm::deleteAll(['confirm_problem_id' => $confirmProblem->id]);
                            }


                            if (!empty($confirmProblem->gcps)){
                                $gcps = $confirmProblem->gcps;

                                foreach ($gcps as $gcp){
                                    if(!empty($gcp->confirm)){
                                        $confirmGcp = $gcp->confirm;

                                        if (!empty($confirmGcp->feedbacks)){
                                            FeedbackExpertGcp::deleteAll(['confirm_gcp_id' => $confirmGcp->id]);
                                        }

                                        if (!empty($confirmGcp->responds)){
                                            $respondsGcp = $confirmGcp->responds;

                                            foreach ($respondsGcp as $respondGcp){
                                                if (!empty($respondGcp->descInterview)){
                                                    $respondGcp->descInterview->delete();
                                                }
                                            }

                                            RespondsGcp::deleteAll(['confirm_gcp_id' => $confirmGcp->id]);
                                        }

                                        if (!empty($confirmGcp->mvps)){
                                            $mvps = $confirmGcp->mvps;

                                            foreach ($mvps as $mvp){
                                                if (!empty($mvp->confirm)){
                                                    $confirmMvp = $mvp->confirm;

                                                    if (!empty($confirmMvp->feedbacks)){
                                                        FeedbackExpertMvp::deleteAll(['confirm_mvp_id' => $confirmMvp->id]);
                                                    }

                                                    if (!empty($confirmMvp->responds)){
                                                        $respondsMvp = $confirmMvp->responds;

                                                        foreach ($respondsMvp as $respondMvp){
                                                            if (!empty($respondMvp->descInterview)){
                                                                $respondMvp->descInterview->delete();
                                                            }
                                                        }

                                                        RespondsMvp::deleteAll(['confirm_mvp_id' => $confirmMvp->id]);
                                                    }

                                                    if (!empty($confirmMvp->business)){
                                                        $confirmMvp->business->delete();
                                                    }


                                                    $confirmMvp->delete();
                                                }
                                            }

                                            Mvp::deleteAll(['confirm_gcp_id' => $confirmGcp->id]);
                                        }

                                        $confirmGcp->delete();
                                    }
                                }

                                Gcp::deleteAll(['confirm_problem_id' => $confirmProblem->id]);
                            }

                            $confirmProblem->delete();
                        }
                    }
                }



                Questions::deleteAll(['interview_id' => $interview->id]);
                FeedbackExpert::deleteAll(['interview_id' => $interview->id]);
                Respond::deleteAll(['interview_id' => $interview->id]);
                GenerationProblem::deleteAll(['interview_id' => $interview->id]);
                Interview::deleteAll(['segment_id' => $segment->id]);
            }
        }

        /*Удаление загруженных папок и файлов пользователя*/
        $pathDelete = \Yii::getAlias(UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251")
            . '/' . mb_strtolower(mb_convert_encoding($this->translit($model->project_name), "windows-1251"),"windows-1251"));
        if (file_exists($pathDelete)){
            $this->delTree($pathDelete);
        }
        /*-----------------------------------------------*/


        PreFiles::deleteAll(['project_id' => $model->id]);
        Authors::deleteAll(['project_id' => $model->id]);
        Segment::deleteAll(['project_id' => $model->id]);


        Yii::$app->session->setFlash('error', 'Проект "' . $this->findModel($id)->project_name . '" удален');

        $model->delete();

        return $this->redirect(['index', 'id' => $user->id]);
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
