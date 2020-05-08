<?php

namespace app\controllers;

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

    /**
     * Lists all RespondsGcp models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $models = RespondsGcp::find()->where(['confirm_gcp_id' => $id])->all();
        $confirmGcp = ConfirmGcp::findOne($id);
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();


        $not_exist_data = 0;
        $exist_data = 0;
        foreach ($models as $model){
            if (empty($model->info_respond) || empty($model->descInterview)){
                $not_exist_data++;
            }
            if (!empty($model->info_respond) && !empty($model->descInterview)){
                $exist_data++;
            }
        }

        if (User::isUserSimple(Yii::$app->user->identity['username'])){

            if ($not_exist_data != 0){
                Yii::$app->session->setFlash('success', 'Пройдите последовательно по ссылкам в таблице, заполняя информацию о каждом респонденте.');
            }

            if ($exist_data == count($models)){
                Yii::$app->session->setFlash('success', 'Все данные о респондентах заполнены! При необходимости добавляйте новых респондентов.');
            }
        }


        $newRespond = new RespondsGcp();
        $newRespond->confirm_gcp_id = $id;
        if ($newRespond->load(Yii::$app->request->post()))
        {
            $kol = 0;
            foreach ($models as $elem){
                if ($newRespond->id !== $elem->id && mb_strtolower(str_replace(' ', '', $newRespond->name)) == mb_strtolower(str_replace(' ', '',$elem->name))){
                    $kol++;
                }
            }

            if ($kol == 0){

                $newRespond->save();
                $confirmGcp->count_respond = $confirmGcp->count_respond + 1;
                $confirmGcp->save();

                $project->update_at = date('Y:m:d');
                if ($project->save()){
                    Yii::$app->session->setFlash('success', 'Создан новый респондент: "' . $newRespond->name . '"');
                    return $this->redirect(['index', 'id' => $id]);
                }
            }else{
                Yii::$app->session->setFlash('error', 'Респондент с таким именем уже есть! Имя респондента должно быть уникальным!');
            }
        }



        return $this->render('index', [
            'models' => $models,
            'confirmGcp' => $confirmGcp,
            'gcp' => $gcp,
            'confirmProblem' => $confirmProblem,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
            'newRespond' => $newRespond,
        ]);
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
    /*public function actionCreate()
    {
        $model = new RespondsGcp();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }*/

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
        $confirmGcp = ConfirmGcp::find()->where(['id' => $model->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $user = User::find()->where(['id' => $project->user_id])->one();
        $_user = Yii::$app->user->identity;

        if (!User::isUserDev(Yii::$app->user->identity['username'])) {

            //Действие доступно только проектанту, который создал данную модель
            if ($user->id != $_user['id']){
                Yii::$app->session->setFlash('error', 'У Вас нет прав на данное действие!');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }


        $models = RespondsGcp::find()->where(['confirm_gcp_id' => $confirmGcp->id])->all();

        if ($model->load(Yii::$app->request->post())) {

            $kol = 0;
            foreach ($models as $item){
                if ($model->id !== $item->id && mb_strtolower(str_replace(' ', '',$model->name)) == mb_strtolower(str_replace(' ', '',$item->name))){
                    $kol++;
                }
            }

            if ($kol == 0){

                if ($model->save()){
                    $project->update_at = date('Y:m:d');
                    if ($project->save()){
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }
            }else{
                Yii::$app->session->setFlash('error', 'Респондент с таким именем уже есть! Имя респондента должно быть уникальным!');
            }
        }

        return $this->render('update', [
            'model' => $model,
            'confirmGcp' => $confirmGcp,
            'gcp' => $gcp,
            'confirmProblem' => $confirmProblem,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
            'models' => $models,
        ]);
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
