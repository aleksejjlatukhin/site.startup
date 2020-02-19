<?php

namespace app\controllers;

use app\models\ConfirmProblem;
use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Projects;
use app\models\Segment;
use Yii;
use app\models\Gcp;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GcpController implements the CRUD actions for Gcp model.
 */
class GcpController extends AppController
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
     * Lists all Gcp models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $user = Yii::$app->user->identity;
        $confirmProblem = ConfirmProblem::findOne($id);
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $Gcps = Gcp::find()->where(['confirm_problem_id' => $id])->all();

        if ($generationProblem->exist_confirm !== 1){
            Yii::$app->session->setFlash('error', "Отсутствует подтверждение проблемы с данным ID, поэтому вы не можете перейти к созданию ГЦП.");
            return $this->redirect(['generation-problem/view', 'id' => $generationProblem->id]);
        }

        if (count($Gcps) == 0){
            return $this->redirect(['create', 'id' => $id]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Gcp::find()->where(['confirm_problem_id' => $id]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'confirmProblem' => $confirmProblem,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * Displays a single Gcp model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $confirmProblem = ConfirmProblem::find()->where(['id' => $model->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        if ($generationProblem->exist_confirm !== 1){
            Yii::$app->session->setFlash('error', "У проблемы с данным ID отсутствует подтверждение, поэтому вы не можете перейти к просмотру ГЦП.");
            return $this->redirect(['generation-problem/view', 'id' => $generationProblem->id]);
        }

        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        return $this->render('view', [
            'model' => $model,
            'confirmProblem' => $confirmProblem,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * Creates a new Gcp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $user = Yii::$app->user->identity;
        $model = new Gcp();
        $confirmProblem = ConfirmProblem::findOne($id);
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $models = Gcp::find()->where(['confirm_problem_id' => $id])->all();
        $model->title = 'ГЦП ' . (count($models)+1);
        $model->confirm_problem_id = $id;
        $model->date_create = date('Y:m:d');
        $model->date_time_create = date('Y-m-d H:i:s');

        if ($generationProblem->exist_confirm !== 1){
            Yii::$app->session->setFlash('error', "У проблемы с данным ID отсутствует подтверждение, поэтому вы не можете перейти к созданию ГЦП.");
            return $this->redirect(['generation-problem/view', 'id' => $generationProblem->id]);
        }


        if ($model->load(Yii::$app->request->post())) {

            $model->description = 'Наш продукт "' . mb_strtolower($model->good) . '" ';
            $model->description .= 'помогает "' . mb_strtolower($segment->name) . '", ';
            $model->description .= 'который хочет удовлетворить проблему "' . mb_strtolower($generationProblem->description) . '", ';
            $model->description .= 'избавиться от проблемы(или снизить её) и позволяет получить выгоду в виде, "' . mb_strtolower($model->benefit) . '", ';
            $model->description .= 'в отличии от "' . mb_strtolower($model->contrast) . '".';

            if ($model->save()){

                $gcps_dir = UPLOAD . mb_convert_encoding($user['username'], "windows-1251") . '/' .
                    mb_convert_encoding($project->project_name , "windows-1251") . '/segments/'.
                    mb_convert_encoding($segment->name , "windows-1251") .'/generation problems/'
                    . mb_convert_encoding($generationProblem->title , "windows-1251") . '/gcps/';

                $gcps_dir = mb_strtolower($gcps_dir, "windows-1251");

                if (!file_exists($gcps_dir)){
                    mkdir($gcps_dir, 0777);
                }

                $gcp_dir = UPLOAD . mb_convert_encoding($user['username'], "windows-1251") . '/' .
                    mb_convert_encoding($project->project_name , "windows-1251") . '/segments/'.
                    mb_convert_encoding($segment->name , "windows-1251") .'/generation problems/'
                    . mb_convert_encoding($generationProblem->title , "windows-1251") . '/gcps/'
                    . mb_convert_encoding($model->title , "windows-1251");

                $gcp_dir = mb_strtolower($gcp_dir, "windows-1251");

                if (!file_exists($gcp_dir)){
                    mkdir($gcp_dir, 0777);
                }

                $project->update_at = date('Y:m:d');

                if ($project->save()){

                    Yii::$app->session->setFlash('success', "Вы успешно создали " . $model->title);
                    return $this->redirect(['index', 'id' => $model->confirm_problem_id]);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'confirmProblem' => $confirmProblem,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * Updates an existing Gcp model.
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

        if ($generationProblem->exist_confirm !== 1){
            Yii::$app->session->setFlash('error', "У проблемы с данным ID отсутствует подтверждение, поэтому вы не можете перейти к редактированию ГЦП.");
            return $this->redirect(['generation-problem/view', 'id' => $generationProblem->id]);
        }

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()){

                $project->update_at = date('Y:m:d');

                if ($project->save()){

                    Yii::$app->session->setFlash('success', "Вы успешно отредактировали " . $model->title);
                    return $this->redirect(['index', 'id' => $model->confirm_problem_id]);
                }
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
     * Deletes an existing Gcp model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $confirmProblem = ConfirmProblem::find()->where(['id' => $model->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $project->update_at = date('Y:m:d');

        if ($project->save()){

            Yii::$app->session->setFlash('error', '"' . $model->title . '" удалена!');

            if ($model->delete()){

                $models = Gcp::find()->where(['confirm_problem_id' => $confirmProblem->id])->all();
                $j = 0;
                foreach ($models as $item){
                    $j++;
                    $item->title = 'ГЦП ' . $j;
                    $item->save();
                }

                return $this->redirect(['index', 'id' => $confirmProblem->id]);
            }
        }
    }

    /**
     * Finds the Gcp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Gcp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Gcp::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
