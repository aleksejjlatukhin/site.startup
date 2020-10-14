<?php

namespace app\controllers;

use app\models\ConfirmProblem;
use app\models\DescInterview;
use app\models\FeedbackExpertConfirm;
use app\models\Interview;
use app\models\Projects;
use app\models\Respond;
use app\models\RespondsConfirm;
use app\models\Segment;
use app\models\User;
use Yii;
use app\models\GenerationProblem;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GenerationProblemController implements the CRUD actions for GenerationProblem model.
 */
class GenerationProblemController extends AppController
{

    public function beforeAction($action)
    {

        if (in_array($action->id, ['view'])){

            $model = GenerationProblem::findOne(Yii::$app->request->get());
            $interview = Interview::find()->where(['id' => $model->interview_id])->one();
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['update'])){

            $model = GenerationProblem::findOne(Yii::$app->request->get());
            $interview = Interview::find()->where(['id' => $model->interview_id])->one();
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){

            $interview = Interview::findOne(Yii::$app->request->get());
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['index'])){

            $interview = Interview::findOne(Yii::$app->request->get());
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
     * Lists all GenerationProblem models.
     * @return mixed
     */
    public function actionIndex($id)
    {

        $interview = Interview::find()->with('questions')->where(['id' => $id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $dataProvider = new ActiveDataProvider([
            'query' => GenerationProblem::find()->where(['interview_id' => $id]),
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                    //'name' => SORT_ASC,
                ]
            ],
        ]);

        //Выбор респондентов, которые являются представителями сегмента
        $dataProviderRespondsPositive = new ActiveDataProvider([
            'query' => Respond::find()->with('descInterview')
                ->leftJoin('desc_interview', '`desc_interview`.`respond_id` = `responds`.`id`')
                ->where(['interview_id' => $id, 'desc_interview.status' => '1']),
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                    //'name' => SORT_ASC,
                ]
            ],
        ]);


        $newProblem = new GenerationProblem();
        $newProblem->interview_id = $id;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'dataProviderRespondsPositive' => $dataProviderRespondsPositive,
            'newProblem' => $newProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * Displays a single GenerationProblem model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = GenerationProblem::findOne($id);
        $interview = Interview::find()->where(['id' => $model->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        return $this->render('view', [
            'model' => $model,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * Creates a new GenerationProblem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */



    public function actionCreate($id)
    {

        $model = new GenerationProblem();
        $model->interview_id = $id;
        $models = GenerationProblem::find()->where(['interview_id' => $id])->all();
        $model->title = 'ГПС ' . (count($models)+1);

        $interview = Interview::findOne($id);
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $model->segment_id = $segment->id;
        $model->project_id = $project->id;

        $user = User::find()->where(['id' => $project->user_id])->one();
        $_user = Yii::$app->user->identity;

        if (!User::isUserDev(Yii::$app->user->identity['username'])) {

            //Действие доступно только проектанту, который создал данную модель
            if ($user->id != $_user['id']){
                Yii::$app->session->setFlash('error', 'У Вас нет прав на данное действие!');
                return $this->redirect(['interview/view', 'id' => $interview->id]);
            }
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->description = $_POST['GenerationProblem']['description'];

            if ($model->save()){

                $project->updated_at = time();
                if ($project->save()){

                    return $this->redirect(['/generation-problem/index', 'id' => $id]);
                }
            }
        }

    }


    /**
     * Updates an existing GenerationProblem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $interview = Interview::find()->where(['id' => $model->interview_id])->one();
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


        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $project->updated_at = time();
            if ($project->save()) {
                Yii::$app->session->setFlash('success', "Данные по " . $model->title . " обновлены!");
                return $this->redirect(['generation-problem/view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * Deletes an existing GenerationProblem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    /*public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $interview = Interview::find()->where(['id' => $model->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['gps_id' => $model->id])->one();
        $responds = RespondsConfirm::find()->where(['confirm_problem_id' => $confirmProblem->id])->all();
        $project->updated_at = time();
        $user = User::find()->where(['id' => $project->user_id])->one();
        $_user = Yii::$app->user->identity;

        //Удаление доступно только проектанту, который создал данную модель
        if (!User::isUserDev(Yii::$app->user->identity['username'])) {

            //Действие доступно только проектанту, который создал данную модель
            if ($user->id != $_user['id']){
                Yii::$app->session->setFlash('error', 'У Вас нет прав на данное действие!');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }


        if ($project->save()) {

            foreach ($responds as $respond){
                $descInterview = $respond->descInterview;

                if ($descInterview->interview_file !== null){
                    unlink('upload/interviews-confirm/' . $descInterview->interview_file);
                }

                if (!empty($descInterview)){
                    $descInterview->delete();
                }
            }

            if (!empty($confirmProblem->feedbacks)){
                foreach ($confirmProblem->feedbacks as $feedback) {
                    if ($feedback->feedback_file !== null){
                        unlink('upload/feedbacks-confirm/' . $feedback->feedback_file);
                    }
                }
            }

            RespondsConfirm::deleteAll(['confirm_problem_id' => $confirmProblem->id]);
            FeedbackExpertConfirm::deleteAll(['confirm_problem_id' => $confirmProblem->id]);

            Yii::$app->session->setFlash('error', '"' . $model->title . '" удалена!');

            $confirmProblem->delete();

            $model->delete();

            $j = 0;
            foreach ($interview->problems as $item){
                $j++;
                $item->title = 'ГПС ' . $j;
                $item->save();
            }

            return $this->redirect(['interview/view', 'id' => $interview->id]);
        }


    }*/

    /**
     * Finds the GenerationProblem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return GenerationProblem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GenerationProblem::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
