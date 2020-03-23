<?php

namespace app\controllers;

use app\models\DescInterview;
use app\models\Interview;
use app\models\Projects;
use app\models\Segment;
use Yii;
use app\models\Respond;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RespondController implements the CRUD actions for Respond model.
 */
class RespondController extends AppController
{

    public function beforeAction($action)
    {

        if (in_array($action->id, ['view']) || in_array($action->id, ['update']) || in_array($action->id, ['delete'])){

            $respond = Respond::findOne(Yii::$app->request->get());
            $interview = Interview::find()->where(['id' => $respond->interview_id])->one();
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if ($project->user_id == Yii::$app->user->id){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['by-date-interview']) || in_array($action->id, ['by-status-responds'])
            || in_array($action->id, ['exist']) || in_array($action->id, ['index'])){

            $interview = Interview::findOne(Yii::$app->request->get());
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if ($project->user_id == Yii::$app->user->id){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }else{
            return parent::beforeAction($action);
        }

    }

    /**
     * Lists all Respond models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $models = Respond::find()->where(['interview_id' => $id])->all();
        $interview = Interview::findOne($id);
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $not_exist_data = 0;
        $exist_data = 0;
        foreach ($models as $model){
            if (empty($model->info_respond) || empty($model->place_interview) || empty($model->date_plan) || empty($model->descInterview)){
                $not_exist_data++;
            }
            if (!empty($model->info_respond) && !empty($model->place_interview) && !empty($model->date_plan) && !empty($model->descInterview)){
                $exist_data++;
            }
        }

        if ($not_exist_data != 0){
            Yii::$app->session->setFlash('success', 'Пройдите последовательно по ссылкам в таблице, заполняя информацию о каждом респонденте.');
        }

        if ($exist_data == count($models)){
            Yii::$app->session->setFlash('success', 'Все данные о респондентах заполнены! При необходимости добавляйте новых респондентов.');
        }

        $newRespond = new Respond();
        $newRespond->interview_id = $id;
        if ($newRespond->load(Yii::$app->request->post()))
        {
            $kol = 0;
            foreach ($models as $elem){
                if ($newRespond->id !== $elem->id && mb_strtolower(str_replace(' ', '', $newRespond->name)) == mb_strtolower(str_replace(' ', '',$elem->name))){
                    $kol++;
                }
            }

            if ($kol == 0){
                if($newRespond->save()){
                    $interview->count_respond = $interview->count_respond + 1;
                    $interview->save();

                    $project->update_at = date('Y:m:d');
                    if ($project->save()){
                        Yii::$app->session->setFlash('success', 'Создан новый респондент: "' . $newRespond->name . '"');
                        return $this->redirect(['index', 'id' => $id]);
                    }
                }
            }else{
                Yii::$app->session->setFlash('error', 'Респондент с таким именем уже есть! Имя респондента должно быть уникальным!');
            }
        }

        return $this->render('index', [
            'models' => $models,
            'newRespond' => $newRespond,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    public function actionExist($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Respond::find()->where(['interview_id' => $id]),
        ]);
        $interview = Interview::findOne($id);
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        return $this->render('exist', [
            'dataProvider' => $dataProvider,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    public function actionByDateInterview($id)
    {
        $models = Respond::find()->where(['interview_id' => $id])->all();
        $interview = Interview::findOne($id);
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        return $this->render('by-date-interview', [
            'models' => $models,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }


    public function actionByStatusResponds($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Respond::find()->where(['interview_id' => $id]),
        ]);

        $models = Respond::find()->where(['interview_id' => $id])->all();
        $interview = Interview::findOne($id);
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        return $this->render('by-status-responds', [
            'dataProvider' => $dataProvider,
            'models' => $models,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * Displays a single Respond model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $interview = Interview::find()->where(['id' => $model->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $desc_interview = DescInterview::find()->where(['respond_id' => $model->id])->one();

        if (empty($model->info_respond) || empty($model->place_interview) || empty($model->date_plan)){
            Yii::$app->session->setFlash('success', 'Для внесения новой информации о респонденте или корректировки пройдите по ссылке "Редактировать данные".');
        }

        if (!empty($model->info_respond) && !empty($model->place_interview) && !empty($model->date_plan) && empty($model->descInterview)){
            Yii::$app->session->setFlash('success', 'Для внесения данных интервью респондента пройдите по ссылке "Добавить интервью".');
        }

        return $this->render('view', [
            'model' => $model,
            'segment' => $segment,
            'project' => $project,
            'desc_interview' => $desc_interview,
        ]);
    }


    /**
     * Creates a new Respond model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    /*public function actionCreate()
    {
        $model = new Respond();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }*/

    /**
     * Updates an existing Respond model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $user = Yii::$app->user->identity;
        $model = $this->findModel($id);

        $interview = Interview::find()->where(['id' => $model->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $models = Respond::find()->where(['interview_id' => $interview->id])->all();

        if ($model->load(Yii::$app->request->post())) {

            $kol = 0;
            foreach ($models as $item){
                if ($model->id !== $item->id && mb_strtolower(str_replace(' ', '',$model->name)) == mb_strtolower(str_replace(' ', '',$item->name))){
                    $kol++;
                }
            }

            if ($kol == 0){

                foreach ($models as $elem){
                    if ($model->id == $elem->id && mb_strtolower(str_replace(' ', '',$model->name)) !== mb_strtolower(str_replace(' ', '',$elem->name))){

                        $old_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                            mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
                            mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/interviews/' .
                            mb_convert_encoding($this->translit($elem->name) , "windows-1251") . '/';

                        $new_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                            mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
                            mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/interviews/' .
                            mb_convert_encoding($this->translit($model->name) , "windows-1251") . '/';

                        if (file_exists($old_dir)){
                            rename($old_dir, $new_dir);
                        }
                    }
                }

                $respond_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                    mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
                    mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/interviews/' .
                    mb_convert_encoding($this->translit($model->name) , "windows-1251") . '/';
                if (!file_exists($respond_dir)){
                    mkdir($respond_dir, 0777);
                }

                if ($model->save()){

                    $project->update_at = date('Y:m:d');
                    if ($project->save()){
                        Yii::$app->session->setFlash('success', 'Данные обновлены!');
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }
            }else{
                Yii::$app->session->setFlash('error', 'Респондент с таким именем уже есть! Имя респондента должно быть уникальным!');
            }
        }

        return $this->render('update', [
            'model' => $model,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * Deletes an existing Respond model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $user = Yii::$app->user->identity;
        $model = $this->findModel($id);
        $descInterview = DescInterview::find()->where(['respond_id' => $model->id])->one();
        $interview = Interview::find()->where(['id' => $model->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $project->update_at = date('Y:m:d');
        $responds = Respond::find()->where(['interview_id' => $interview->id])->all();

        if (count($responds) == 1){
            Yii::$app->session->setFlash('error', 'Удаление последнего респондента запрещено!');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        if ($interview->count_respond == $interview->count_positive){
            Yii::$app->session->setFlash('error', "Количество респондентов не должно быть меньше количества представителей сегмента!");
            return $this->redirect(['view', 'id' => $model->id]);
        }

        if ($project->save()){
            Yii::$app->session->setFlash('error', 'Респондент: "' . $model->name . '" удален!');

            if ($descInterview){
                $descInterview->delete();
            }

            $del_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
                mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/interviews/' .
                mb_convert_encoding($this->translit($model->name) , "windows-1251") . '/';

            if (file_exists($del_dir)){
                $this->delTree($del_dir);
            }


            if ($model->delete()){
                $interview->count_respond = $interview->count_respond -1;
                $interview->save();
            }
            return $this->redirect(['interview/view', 'id' => $interview->id]);
        }
    }

    /**
     * Finds the Respond model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Respond the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Respond::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
