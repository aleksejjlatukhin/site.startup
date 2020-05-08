<?php

namespace app\controllers;

use app\models\ConfirmProblem;
use app\models\DescInterviewConfirm;
use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Projects;
use app\models\Segment;
use app\models\User;
use Yii;
use app\models\RespondsConfirm;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RespondsConfirmController implements the CRUD actions for RespondsConfirm model.
 */
class RespondsConfirmController extends AppController
{

    public function beforeAction($action)
    {

        if (in_array($action->id, ['update'])){

            $model = RespondsConfirm::findOne(Yii::$app->request->get());
            $confirmProblem = ConfirmProblem::find()->where(['id' => $model->confirm_problem_id])->one();
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

        }elseif (in_array($action->id, ['view'])){

            $model = RespondsConfirm::findOne(Yii::$app->request->get());
            $confirmProblem = ConfirmProblem::find()->where(['id' => $model->confirm_problem_id])->one();
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

            $confirmProblem = ConfirmProblem::findOne(Yii::$app->request->get());
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

        }else{
            return parent::beforeAction($action);
        }

    }

    /**
     * Lists all RespondsConfirm models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $models = RespondsConfirm::find()->where(['confirm_problem_id' => $id])->all();
        $confirmProblem = ConfirmProblem::findOne($id);
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


        $newRespond = new RespondsConfirm();
        $newRespond->confirm_problem_id = $id;
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
                $confirmProblem->count_respond = $confirmProblem->count_respond + 1;
                $confirmProblem->save();

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
            'query' => RespondsConfirm::find()->where(['confirm_problem_id' => $id]),
        ]);
        $confirmProblem = ConfirmProblem::findOne($id);
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        return $this->render('exist', [
            'dataProvider' => $dataProvider,
            'confirmProblem' => $confirmProblem,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /*public function actionByDateInterview($id)
    {
        $models = RespondsConfirm::find()->where(['confirm_problem_id' => $id])->all();
        $confirmProblem = ConfirmProblem::findOne($id);
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        return $this->render('by-date-interview', [
            'models' => $models,
            'confirmProblem' => $confirmProblem,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }*/


    public function actionByStatusInterview($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => RespondsConfirm::find()->where(['confirm_problem_id' => $id]),
        ]);
        $responds = RespondsConfirm::find()->where(['confirm_problem_id' => $id])->all();
        $confirmProblem = ConfirmProblem::findOne($id);
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        return $this->render('by-status-interview', [
            'dataProvider' => $dataProvider,
            'responds' => $responds,
            'confirmProblem' => $confirmProblem,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * Displays a single RespondsConfirm model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = RespondsConfirm::findOne($id);
        $confirmProblem = ConfirmProblem::find()->where(['id' => $model->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $desc_interview = DescInterviewConfirm::find()->where(['responds_confirm_id' => $model->id])->one();


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
            'confirmProblem' => $confirmProblem,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
            'desc_interview' => $desc_interview,
        ]);
    }

    /**
     * Creates a new RespondsConfirm model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    /*public function actionCreate()
    {
        $model = new RespondsConfirm();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }*/

    /**
     * Updates an existing RespondsConfirm model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $confirmProblem = ConfirmProblem::find()->where(['id' => $model->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $models = RespondsConfirm::find()->where(['confirm_problem_id' => $confirmProblem->id])->all();
        $user = User::find()->where(['id' => $project->user_id])->one();
        $_user = Yii::$app->user->identity;

        if (!User::isUserDev(Yii::$app->user->identity['username'])) {

            //Действие доступно только проектанту, который создал данную модель
            if ($user->id != $_user['id']){
                Yii::$app->session->setFlash('error', 'У Вас нет прав на данное действие!');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }


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
            'confirmProblem' => $confirmProblem,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * Deletes an existing RespondsConfirm model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $descInterview = DescInterviewConfirm::find()->where(['responds_confirm_id' => $model->id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $model->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $project->update_at = date('Y:m:d');
        $responds = RespondsConfirm::find()->where(['confirm_problem_id' => $confirmProblem->id])->all();
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

        if ($confirmProblem->count_respond == $confirmProblem->count_positive){
            Yii::$app->session->setFlash('error', "Количество респондентов не должно быть меньше количества позитивных интервью!");
            return $this->redirect(['view', 'id' => $model->id]);
        }

        if ($project->save()){
            Yii::$app->session->setFlash('error', 'Респондент: "' . $model->name . '" удален!');

            if ($descInterview){
                $descInterview->delete();
            }

            if ($model->delete()){
                $confirmProblem->count_respond = $confirmProblem->count_respond -1;
                $confirmProblem->save();
            }
            return $this->redirect(['confirm-problem/view', 'id' => $confirmProblem->id]);
        }


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
