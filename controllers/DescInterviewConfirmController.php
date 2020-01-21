<?php

namespace app\controllers;

use app\models\ConfirmProblem;
use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Projects;
use app\models\RespondsConfirm;
use app\models\Segment;
use app\models\User;
use Yii;
use app\models\DescInterviewConfirm;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * DescInterviewConfirmController implements the CRUD actions for DescInterviewConfirm model.
 */
class DescInterviewConfirmController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all DescInterviewConfirm models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => DescInterviewConfirm::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }



    /**
     * Displays a single DescInterviewConfirm model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $respond = RespondsConfirm::find()->where(['id' => $model->responds_confirm_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $respond->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        return $this->render('view', [
            'model' => $model,
            'respond' => $respond,
            'confirmProblem' => $confirmProblem,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * Creates a new DescInterviewConfirm model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $user = Yii::$app->user->identity;
        $model = new DescInterviewConfirm();
        $model->responds_confirm_id = $id;
        $model->date_fact = date('Y:m:d');

        $respond = RespondsConfirm::find()->where(['id' => $id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $respond->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();


        if ($respond->name == null || $respond->info_respond == null){
            Yii::$app->session->setFlash('error', "Необходимо заполнить данные о респонденте!");
            return $this->redirect(['responds-confirm/view', 'id' => $id]);
        }

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {

                $project->update_at = date('Y:m:d');
                if ($project->save()){
                    Yii::$app->session->setFlash('success', "Анкета добавлена!");
                    return $this->redirect(['responds-confirm/view', 'id' => $model->responds_confirm_id]);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'respond' => $respond,
            'confirmProblem' => $confirmProblem,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * Updates an existing DescInterviewConfirm model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $user = Yii::$app->user->identity;
        $model = $this->findModel($id);

        $respond = RespondsConfirm::find()->where(['id' => $model->responds_confirm_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $respond->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();


        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {

                $project->update_at = date('Y:m:d');
                if ($project->save()){
                    Yii::$app->session->setFlash('success', "Анкета обновлена!");
                    return $this->redirect(['responds-confirm/view', 'id' => $model->responds_confirm_id]);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'respond' => $respond,
            'confirmProblem' => $confirmProblem,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * Deletes an existing DescInterviewConfirm model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = DescInterviewConfirm::find()->where(['responds_confirm_id' => $id])->one();

        $respond = RespondsConfirm::findOne($id);
        $confirmProblem = ConfirmProblem::find()->where(['id' => $respond->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        if ($project->save()) {

            Yii::$app->session->setFlash('error', 'Анкета ' . $respond->name . ' удалена!');

            $model->delete();

            return $this->redirect(['responds-confirm/view', 'id' => $respond->id]);
        }
    }

    /**
     * Finds the DescInterviewConfirm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return DescInterviewConfirm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DescInterviewConfirm::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
