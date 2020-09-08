<?php

namespace app\controllers;

use app\models\AnswersQuestionsConfirmGcp;
use app\models\ConfirmGcp;
use app\models\ConfirmProblem;
use app\models\DescInterviewGcp;
use app\models\Gcp;
use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Projects;
use app\models\Segment;
use app\models\User;
use Yii;
use app\models\RespondsGcp;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use app\models\UpdateRespondGcpForm;
use yii\filters\VerbFilter;

/**
 * RespondsGcpController implements the CRUD actions for RespondsGcp model.
 */
class RespondsGcpController extends AppController
{

    public function beforeAction($action)
    {

        if (in_array($action->id, ['update'])){

            $model = RespondsGcp::findOne(Yii::$app->request->get());
            $confirmGcp = ConfirmGcp::find()->where(['id' => $model->confirm_gcp_id])->one();
            $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
            $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
            $problem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
            $interview = Interview::find()->where(['id' => $problem->interview_id])->one();
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                if ($action->id == 'update') {
                    // ОТКЛЮЧАЕМ CSRF
                    $this->enableCsrfValidation = false;
                }

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }if (in_array($action->id, ['view'])){

        $model = RespondsGcp::findOne(Yii::$app->request->get());
        $confirmGcp = ConfirmGcp::find()->where(['id' => $model->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $problem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $problem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        /*Ограничение доступа к проэктам пользователя*/
        if (($project->user_id == Yii::$app->user->id) || User::isUserAdmin(Yii::$app->user->identity['username'])
            || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

            return parent::beforeAction($action);

        }else{
            throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
        }

    }elseif (in_array($action->id, ['create'])){

        $confirmGcp = ConfirmGcp::findOne(Yii::$app->request->get());
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $problem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $problem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        /*Ограничение доступа к проэктам пользователя*/
        if (($project->user_id == Yii::$app->user->id) || User::isUserAdmin(Yii::$app->user->identity['username'])
            || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

            if ($action->id == 'create') {
                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;
            }

            return parent::beforeAction($action);

        }else{
            throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
        }

    }elseif (in_array($action->id, ['by-status-interview']) || in_array($action->id, ['exist']) || in_array($action->id, ['index'])){

            $confirmGcp = ConfirmGcp::findOne(Yii::$app->request->get());
            $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
            $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
            $problem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
            $interview = Interview::find()->where(['id' => $problem->interview_id])->one();
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if ($project->user_id == Yii::$app->user->id || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }else{
            return parent::beforeAction($action);
        }

    }


    public function actionIndex($id)
    {
        return $this->renderList($id);
    }

    /**
     * Lists all RespondsGcp models.
     * @return mixed
     */
    public function renderList($id)
    {
        $models = RespondsGcp::find()->where(['confirm_gcp_id' => $id])->all();
        $confirmGcp = ConfirmGcp::findOne($id);
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $gcp_title = str_replace(' ', '_', $gcp->title);
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $problem_title = str_replace(' ', '_', $generationProblem->title);
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $segment_name = str_replace(' ', '_', $segment->name);
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $project_filename = str_replace(' ', '_', $project->project_name);


        $newRespond = new RespondsGcp();
        $newRespond->confirm_gcp_id = $id;


        $updateRespondForms = [];
        $createDescInterviewForms = [];
        $updateDescInterviewForms = [];
        foreach ($models as $i => $model) {

            $updateRespondForms[] = new UpdateRespondGcpForm($model->id);

            $createDescInterviewForms[] = new DescInterviewGcp();

            $updateDescInterviewForms[] = $model->descInterview;

        }


        $query = RespondsGcp::find()->where(['confirm_gcp_id' => $id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                    //'name' => SORT_ASC,
                ]
            ],
        ]);



        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'models' => $models,
            'confirmGcp' => $confirmGcp,
            'gcp' => $gcp,
            'confirmProblem' => $confirmProblem,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
            'newRespond' => $newRespond,
            'updateRespondForms' => $updateRespondForms,
            'createDescInterviewForms' => $createDescInterviewForms,
            'updateDescInterviewForms' => $updateDescInterviewForms,
            'project_filename' => $project_filename,
            'segment_name' => $segment_name,
            'problem_title' => $problem_title,
            'gcp_title' => $gcp_title,
        ]);
    }


    public function actionDataAvailability($id)
    {

        $models = RespondsGcp::find()->where(['confirm_gcp_id' => $id])->all();

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


    public function actionExist($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => RespondsGcp::find()->where(['confirm_gcp_id' => $id]),
        ]);

        $confirmGcp = ConfirmGcp::findOne($id);
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        return $this->render('exist', [
            'dataProvider' => $dataProvider,
            'confirmGcp' => $confirmGcp,
            'gcp' => $gcp,
            'confirmProblem' => $confirmProblem,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }


    public function actionByStatusInterview($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => RespondsGcp::find()->where(['confirm_gcp_id' => $id]),
        ]);

        $responds = RespondsGcp::find()->where(['confirm_gcp_id' => $id])->all();
        $confirmGcp = ConfirmGcp::findOne($id);
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        return $this->render('by-status-interview', [
            'dataProvider' => $dataProvider,
            'responds' => $responds,
            'confirmGcp' => $confirmGcp,
            'gcp' => $gcp,
            'confirmProblem' => $confirmProblem,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * Displays a single RespondsGcp model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $confirmGcp = ConfirmGcp::find()->where(['id' => $model->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $desc_interview = DescInterviewGcp::find()->where(['responds_gcp_id' => $model->id])->one();


        if (User::isUserSimple(Yii::$app->user->identity['username'])){

            if (empty($model->info_respond)){
                Yii::$app->session->setFlash('success', 'Для внесения новой информации о респонденте или корректировки пройдите по ссылке "Редактировать данные".');
            }

            if (!empty($model->info_respond) && empty($model->descInterview)){
                Yii::$app->session->setFlash('success', 'Для внесения данных в анкету респондента пройдите по ссылке "Добавить анкету".');
            }
        }


        return $this->render('view', [
            'model' => $model,
            'confirmGcp' => $confirmGcp,
            'gcp' => $gcp,
            'confirmProblem' => $confirmProblem,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
            'desc_interview' => $desc_interview,
        ]);
    }

    /**
     * Creates a new RespondsGcp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $confirmGcp = ConfirmGcp::findOne($id);
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $project->update_at = date('Y:m:d');


        $models = RespondsGcp::find()->where(['confirm_gcp_id' => $confirmGcp->id])->all();
        $newRespond = new RespondsGcp();
        $newRespond->confirm_gcp_id = $id;


        if ($newRespond->load(Yii::$app->request->post())) {

            $kol = 0;
            foreach ($models as $elem){
                if ($newRespond->id != $elem->id && mb_strtolower(str_replace(' ', '', $newRespond->name)) == mb_strtolower(str_replace(' ', '',$elem->name))){
                    $kol++;
                }
            }

            if(Yii::$app->request->isAjax) {
                if ($kol == 0) {
                    if ($newRespond->save()) {

                        $newRespond->addAnswersForNewRespond();

                        $confirmGcp->count_respond = $confirmGcp->count_respond + 1;
                        $confirmGcp->save();

                        if ($project->save()) {

                            $responds = RespondsGcp::find()->where(['confirm_gcp_id' => $id])->all();

                            $response =  [
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
            }
        }

    }

    /**
     * Updates an existing RespondsGcp model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $updateRespondForm = new UpdateRespondGcpForm($id);

        $confirmGcp = ConfirmGcp::find()->where(['id' => $model->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $project->update_at = date('Y:m:d');

        $models = RespondsGcp::find()->where(['confirm_gcp_id' => $confirmGcp->id])->all();
        $user = User::find()->where(['id' => $project->user_id])->one();
        $_user = Yii::$app->user->identity;


        if ($updateRespondForm->load(Yii::$app->request->post())) {

            $kol = 0;
            foreach ($models as $item){
                if ($updateRespondForm->id !== $item->id && mb_strtolower(str_replace(' ', '',$updateRespondForm->name)) == mb_strtolower(str_replace(' ', '',$item->name))){
                    $kol++;
                }
            }

            if(Yii::$app->request->isAjax) {

                if ($kol == 0){

                    if ($updateRespondForm->updateRespond($model)){

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



    public function actionDeleteRespond ($id) {

        $model = RespondsGcp::findOne($id);
        $descInterview = DescInterviewGcp::find()->where(['responds_gcp_id' => $model->id])->one();
        $answers = AnswersQuestionsConfirmGcp::find()->where(['respond_id' => $id])->all();

        $confirmGcp = ConfirmGcp::find()->where(['id' => $model->confirm_gcp_id])->one();
        $responds = RespondsGcp::find()->where(['confirm_gcp_id' => $confirmGcp->id])->all();

        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $project->update_at = date('Y:m:d');
        $user = User::find()->where(['id' => $project->user_id])->one();


        if ($confirmGcp->count_respond == $confirmGcp->count_positive){

            Yii::$app->session->setFlash('error', "Общее количество респондентов не должно быть меньше количества респондентов подтверждающих ценностное предложение!");
            return $this->redirect(['/responds-gcp/index', 'id' => $confirmGcp->id]);
        }

        if (count($responds) == 1){

            Yii::$app->session->setFlash('error', 'Удаление последнего респондента запрещено!');
            return $this->redirect(['/responds-gcp/index', 'id' => $confirmGcp->id]);
        }

        if (Yii::$app->request->isAjax){

            if ($project->save()) {

                if ($descInterview) {
                    $descInterview->delete();
                }

                foreach ($answers as $answer){
                    $answer->delete();
                }

                if ($model->delete()) {

                    $confirmGcp->count_respond = $confirmGcp->count_respond - 1;
                    $confirmGcp->save();
                }

                return $this->renderList($id = $confirmGcp->id);
            }

        }
        return false;
    }


    /**
     * Deletes an existing RespondsGcp model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $descInterview = DescInterviewGcp::find()->where(['responds_gcp_id' => $model->id])->one();
        $confirmGcp = ConfirmGcp::find()->where(['id' => $model->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $project->update_at = date('Y:m:d');
        $responds = RespondsGcp::find()->where(['confirm_gcp_id' => $confirmGcp->id])->all();

        $user = User::find()->where(['id' => $project->user_id])->one();
        $_user = Yii::$app->user->identity;

        if (!User::isUserDev(Yii::$app->user->identity['username'])) {

            //Удаление доступно только проектанту, который создал данную модель
            if ($user->id != $_user['id']){
                Yii::$app->session->setFlash('error', 'У Вас нет прав на данное действие!');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }


        if (count($responds) == 1){
            Yii::$app->session->setFlash('error', 'Удаление последнего респондента запрещено!');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        if ($confirmGcp->count_respond == $confirmGcp->count_positive){
            Yii::$app->session->setFlash('error', "Количество респондентов не должно быть меньше количества позитивных интервью!");
            return $this->redirect(['view', 'id' => $model->id]);
        }

        if ($project->save()){
            Yii::$app->session->setFlash('error', 'Респондент: "' . $model->name . '" удален!');

            if ($descInterview){
                $descInterview->delete();
            }

            if ($model->delete()){
                $confirmGcp->count_respond = $confirmGcp->count_respond -1;
                $confirmGcp->save();
            }
            return $this->redirect(['confirm-gcp/view', 'id' => $confirmGcp->id]);
        }

    }

    /**
     * Finds the RespondsGcp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return RespondsGcp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RespondsGcp::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
