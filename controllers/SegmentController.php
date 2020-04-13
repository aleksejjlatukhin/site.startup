<?php

namespace app\controllers;

use app\models\FeedbackExpert;
use app\models\FeedbackExpertConfirm;
use app\models\Gcp;
use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Mvp;
use app\models\Projects;
use app\models\Questions;
use app\models\Respond;
use app\models\RespondsConfirm;
use app\models\User;
use Yii;
use app\models\Segment;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SegmentController implements the CRUD actions for Segment model.
 */
class SegmentController extends AppController
{

    public function beforeAction($action)
    {

        if (in_array($action->id, ['view']) || in_array($action->id, ['one-roadmap'])){

            $model = Segment::findOne(Yii::$app->request->get());
            $project = Projects::find()->where(['id' => $model->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if ($project->user_id == Yii::$app->user->id || User::isUserAdmin(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['update'])){

            $model = Segment::findOne(Yii::$app->request->get());
            $project = Projects::find()->where(['id' => $model->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if ($project->user_id == Yii::$app->user->id){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['index']) || in_array($action->id, ['roadmap'])){

            $project = Projects::findOne(Yii::$app->request->get());

            /*Ограничение доступа к проэктам пользователя*/
            if ($project->user_id == Yii::$app->user->id || User::isUserAdmin(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }else{
            return parent::beforeAction($action);
        }

    }

    /**
     * Lists all Segment models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $project = Projects::findOne($id);
        $user = User::find()->where(['id' => $project->user_id])->one();

        $dataProvider = new ActiveDataProvider([
            'query' => Segment::find()->where(['project_id' => $id]),
        ]);

        $models = Segment::find()->where(['project_id' => $project->id])->all();

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

        $newModel = new Segment();
        $newModel->project_id = $id;

        if ($newModel->load(Yii::$app->request->post())){

            $countSeg = 0;
            foreach ($models as $model){
                if(mb_strtolower(str_replace(' ', '', $newModel->name)) == mb_strtolower(str_replace(' ', '', $model->name))){
                    $countSeg++;
                }
            }

            if ($countSeg == 0){
                if ($newModel->save()){

                    if ($project->save()){
                        $project->update_at = date('Y:m:d');
                        return $this->redirect(['index', 'id' => $newModel->project_id]);
                    }
                }
            }else{
                Yii::$app->session->setFlash('error', 'Сегмент с названием "'. $newModel->name .'" уже существует!');
            }
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'project' => $project,
            'newModel' => $newModel,
        ]);
    }


    private function lastItem($items)
    {
        $itemTime = [];

        if (count($items) > 1){

            for ($i = 0; $i <count($items); $i++){
                $itemTime[] = $items[$i]->date_time_create;
            }

            for ($i = 0; $i <count($items); $i++){

                if($items[$i]->date_time_create == max($itemTime)) {
                    $lastItem = $items[$i];
                }
            }

        }else{
            $lastItem = $items[0];
        }

        return $lastItem;
    }


    private function firstConfirm($confirms)
    {
        $confirmTime = [];

        if (count($confirms) > 1){

            for ($i = 0; $i <count($confirms); $i++){
                $confirmTime[] = $confirms[$i]->date_time_confirm;
            }

            for ($i = 0; $i <count($confirms); $i++){

                if($confirms[$i]->date_time_confirm == min($confirmTime)) {
                    $firstConfirm = $confirms[$i];
                }
            }

        }else{
            $firstConfirm = $confirms[0];
        }

        return $firstConfirm;
    }


    public function actionRoadmap($id)
    {
        $project = Projects::findOne($id);

        $models = Segment::find()->where(['project_id' => $id])->all();

        $gps = [];
        $confirmProblems = [];
        $offersGcp = [];
        $comfirmGcpses = [];
        $mvProds = [];
        $comfirmMvpses = [];

        foreach ($models as $model){
            $interview = Interview::find()->where(['segment_id' => $model->id])->one();
            $problems = GenerationProblem::find()->where(['interview_id' => $interview->id])->all();

            $confirmGps = [];
            $offers = [];
            $comfirmGcps = [];
            $mvProducts = [];
            $comfirmMvps = [];

            if (!empty($problems)){

                foreach ($problems as $k => $problem){
                    /*Выбираем последнюю добавленную ГПС*/
                    if (($k+1) == count($problems)){

                        $gps[] = $problem;

                        if (!empty($problem)){
                            if ($model->fact_gps !== $problem->date_gps){
                                $model->fact_gps = $problem->date_gps;
                                $model->save();
                            }
                        }
                    }
                    if ($problem->exist_confirm === 1){
                        $confirmGps[] = $problem;
                    }

                    $gcps = Gcp::find()->where(['confirm_problem_id' => $problems[$k]->confirm->id])->all();
                    foreach ($gcps as $gcp) {
                        $offers[] = $gcp;
                    }
                }


                $confirmProblem = $this->firstConfirm($confirmGps);
                $confirmProblems[] = $confirmProblem;

                if ($model->fact_ps !== $confirmProblem->date_confirm){
                    $model->fact_ps = $confirmProblem->date_confirm;
                    $model->save();
                }

                foreach ($offers as $i => $offer){

                    if ($offer->exist_confirm === 1){
                        $comfirmGcps[] = $offer;
                    }

                    $mvps = Mvp::find()->where(['confirm_gcp_id' => $offer->confirm->id])->all();
                    foreach ($mvps as $mvp){
                        $mvProducts[] = $mvp;
                    }
                }

                $offer = $this->lastItem($offers);
                $offersGcp[] = $offer;


                if($model->fact_dev_gcp !== $offer->date_create){
                    $model->fact_dev_gcp = $offer->date_create;
                    $model->save();
                }


                $confirmGcp = $this->firstConfirm($comfirmGcps);
                $comfirmGcpses[] = $confirmGcp;

                if ($model->fact_gcp !== $confirmGcp->date_confirm){
                    $model->fact_gcp = $confirmGcp->date_confirm;
                    $model->save();
                }

                $mvProduct = $this->lastItem($mvProducts);
                $mvProds[] = $mvProduct;

                if($model->fact_dev_gmvp !== $mvProduct->date_create){
                    $model->fact_dev_gmvp = $mvProduct->date_create;
                    $model->save();
                }

                foreach ($mvProducts as $mvProduct){
                    if ($mvProduct->exist_confirm === 1){
                        $comfirmMvps[] = $mvProduct;
                    }
                }

                $confirmMvp = $this->firstConfirm($comfirmMvps);
                $comfirmMvpses[] = $confirmMvp;

                if ($model->fact_gmvp !== $confirmMvp->date_confirm){
                    $model->fact_gmvp = $confirmMvp->date_confirm;
                    $model->save();
                }
            }
        }



        return $this->render('roadmap', [
            'project' => $project,
            'models' => $models,
            'gps' => $gps,
            'confirmProblems' => $confirmProblems,
            'offersGcp' => $offersGcp,
            'comfirmGcpses' => $comfirmGcpses,
            'mvProds' => $mvProds,
            'comfirmMvpses' => $comfirmMvpses,
        ]);
    }


    public function actionOneRoadmap($id)
    {
        $model = Segment::findOne($id);
        $project = Projects::find()->where(['id' => $model->project_id])->one();
        $interview = Interview::find()->where(['segment_id' => $model->id])->one();
        $problems = GenerationProblem::find()->where(['interview_id' => $interview->id])->all();

        $confirmGps = [];
        $offers = [];
        $comfirmGcps = [];
        $mvProducts = [];
        $comfirmMvps = [];

        if (!empty($problems)){
            foreach ($problems as $k => $problem){

                if (($k+1) == count($problems)){
                    if (!empty($problem)){
                        if ($model->fact_gps !== $problem->date_gps){
                            $model->fact_gps = $problem->date_gps;
                            $model->save();
                        }
                    }
                }

                if ($problem->exist_confirm === 1){
                    $confirmGps[] = $problem;
                }

                $gcps = Gcp::find()->where(['confirm_problem_id' => $problems[$k]->confirm->id])->all();
                foreach ($gcps as $gcp) {
                    $offers[] = $gcp;
                }
            }
        }


        $confirmProblem = $this->firstConfirm($confirmGps);
        //debug($confirmProblem);
        if ($model->fact_ps !== $confirmProblem->date_confirm){
            $model->fact_ps = $confirmProblem->date_confirm;
            $model->save();
        }


        $offerGcp = $this->lastItem($offers);


        if($model->fact_dev_gcp !== $offerGcp->date_create){
            $model->fact_dev_gcp = $offerGcp->date_create;
            $model->save();
        }


        foreach ($offers as $i => $offer){

            if ($offer->exist_confirm === 1){
                $comfirmGcps[] = $offer;
            }

            if (!empty($offer->confirm)){

                $mvps = Mvp::find()->where(['confirm_gcp_id' => $offer->confirm->id])->all();
                foreach ($mvps as $mvp){
                    $mvProducts[] = $mvp;
                }
            }
        }


        $confirmGcp = $this->firstConfirm($comfirmGcps);
        //debug($confirmGcp);

        if ($model->fact_gcp !== $confirmGcp->date_confirm){
            $model->fact_gcp = $confirmGcp->date_confirm;
            $model->save();
        }

        foreach ($mvProducts as $i => $mvProduct){

            if ($mvProduct->exist_confirm === 1){
                $comfirmMvps[] = $mvProduct;
            }
        }


        $mvProduct = $this->lastItem($mvProducts);

        if($model->fact_dev_gmvp !== $mvProduct->date_create){
            $model->fact_dev_gmvp = $mvProduct->date_create;
            $model->save();
        }


        $comfirmMvp = $this->firstConfirm($comfirmMvps);

        if ($model->fact_gmvp !== $comfirmMvp->date_confirm){
            $model->fact_gmvp = $comfirmMvp->date_confirm;
            $model->save();
        }


        return $this->render('one-roadmap', [
            'model' => $model,
            'project' => $project,
            'problem' => $problem,
            'confirmProblem' => $confirmProblem,
            'offerGcp' => $offerGcp,
            'confirmGcp' => $confirmGcp,
            'mvProduct' => $mvProduct,
            'comfirmMvp' => $comfirmMvp,

        ]);

    }


    /**
     * Displays a single Segment model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $project = Projects::find()->where(['id' => $this->findModel($id)->project_id])->one();

        if (empty($model->creat_date)){
            Yii::$app->session->setFlash('error', "Необходимо заполнить все данные о сегменте!");
        }

        return $this->render('view', [
            'model' => $model,
            'project' => $project,
        ]);
    }

    /**
     * Creates a new Segment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
/*    public function actionCreate($id)
    {
        $model = new Segment();
        $model->project_id = $id;

        $project = Projects::find()->where(['id' => $model->project_id])->one();
        $project->update_at = date('Y:m:d');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($project->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'project' => $project,
        ]);
    }*/

    /**
     * Updates an existing Segment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $project = Projects::find()->where(['id' => $model->project_id])->one();
        $user = User::find()->where(['id' => $project->user_id])->one();
        $_user = Yii::$app->user->identity;

        //Действие доступно только проектанту, который создал данную модель
        if ($user->id != $_user['id']){
            Yii::$app->session->setFlash('error', 'У Вас нет прав на данное действие!');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $models = Segment::find()->where(['project_id' => $project->id])->all();
        $project->update_at = date('Y:m:d');

        if ($model->load(Yii::$app->request->post())) {

            $kol = 0;
            foreach ($models as $item){
                if (mb_strtolower(str_replace(' ', '', $model->name)) == mb_strtolower(str_replace(' ', '',$item->name)) && $model->id !== $item->id){

                    $kol++;
                }
            }

            if ($kol == 0){

                foreach ($models as $item){

                    if ($model->id == $item->id && mb_strtolower($model->name) !== mb_strtolower($item->name)){

                        $old_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251")
                            . '/' . mb_convert_encoding($this->translit($project->project_name), "windows-1251") . '/segments/' .
                            mb_convert_encoding($this->translit($item->name) , "windows-1251") . '/';

                        $old_dir = mb_strtolower($old_dir, "windows-1251");

                        $new_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251")
                            . '/' . mb_convert_encoding($this->translit($project->project_name), "windows-1251") . '/segments/' .
                            mb_convert_encoding($this->translit($model->name) , "windows-1251") . '/';

                        $new_dir = mb_strtolower($new_dir, "windows-1251");

                        rename($old_dir, $new_dir);
                    }
                }

                if ($model->validate() && $model->save()){

                    if ($project->save()) {

                        if ($_POST['Segment']['field_of_activity'] && $_POST['Segment']['sort_of_activity'] && $_POST['Segment']['age_from'] && $_POST['Segment']['age_to']
                            && $_POST['Segment']['income_from'] && $_POST['Segment']['income_to'] && $_POST['Segment']['quantity_from'] && $_POST['Segment']['quantity_to']
                            && $_POST['Segment']['market_volume_from'] && $_POST['Segment']['market_volume_to'])
                        {
                            if (empty($model->creat_date))
                            {
                                $model->creat_date = date('Y:m:d');
                                $model->plan_gps = date('Y:m:d', (time() + 3600*24*30));
                                $model->plan_ps = date('Y:m:d', (time() + 3600*24*60));
                                $model->plan_dev_gcp = date('Y:m:d', (time() + 3600*24*90));
                                $model->plan_gcp = date('Y:m:d', (time() + 3600*24*120));
                                $model->plan_dev_gmvp = date('Y:m:d', (time() + 3600*24*150));
                                $model->plan_gmvp = date('Y:m:d', (time() + 3600*24*180));
                                $model->save();
                            }
                        }

                        if (!empty($model->creat_date) && !empty($model->interview)){
                            Yii::$app->session->setFlash('success', "Сегмент {$model->name} обновлен");
                        }

                        if (!empty($model->creat_date) && empty($model->interview)){
                            Yii::$app->session->setFlash('success', "Переходите к генерации ГПС");
                        }

                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }
            }else{
                Yii::$app->session->setFlash('error', 'Сегмент с названием "'. $model->name .'" уже существует!');
            }
        }

        return $this->render('update', [
            'model' => $model,
            'project' => $project,
        ]);
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

        $project->update_at = date('Y:m:d');
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
