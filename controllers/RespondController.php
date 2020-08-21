<?php

namespace app\controllers;

use app\models\DescInterview;
use app\models\Interview;
use app\models\Projects;
use app\models\Segment;
use app\models\UpdateRespondForm;
use app\models\User;
use Yii;
use app\models\Respond;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;

/**
 * RespondController implements the CRUD actions for Respond model.
 */
class RespondController extends AppController
{

    public function beforeAction($action)
    {

        if (in_array($action->id, ['update'])){

            $respond = Respond::findOne(Yii::$app->request->get());
            $interview = Interview::find()->where(['id' => $respond->interview_id])->one();
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

        }elseif (in_array($action->id, ['view'])){

            $respond = Respond::findOne(Yii::$app->request->get());
            $interview = Interview::find()->where(['id' => $respond->interview_id])->one();
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if ($project->user_id == Yii::$app->user->id || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['delete-respond'])){

            $respond = Respond::findOne(Yii::$app->request->get('id'));
            $interview = Interview::find()->where(['id' => $respond->interview_id])->one();
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if ($project->user_id == Yii::$app->user->id || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                if ($action->id == 'delete-respond') {
                    // ОТКЛЮЧАЕМ CSRF
                    $this->enableCsrfValidation = false;
                }

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['by-date-interview']) || in_array($action->id, ['by-status-responds'])
            || in_array($action->id, ['exist']) || in_array($action->id, ['index']) || in_array($action->id, ['create'])
            || in_array($action->id, ['data-availability'])){

            $interview = Interview::findOne(Yii::$app->request->get('id'));
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if ($project->user_id == Yii::$app->user->id || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                if ($action->id == 'index' || $action->id == 'create' || $action->id == 'data-availability') {
                    // ОТКЛЮЧАЕМ CSRF
                    $this->enableCsrfValidation = false;
                }

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
    /*public function actionIndex($id)
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

        if (User::isUserSimple(Yii::$app->user->identity['username'])){

            if ($not_exist_data != 0){
                Yii::$app->session->setFlash('success', 'Пройдите последовательно по ссылкам в таблице, заполняя информацию о каждом респонденте.');
            }

            if ($exist_data == count($models)){
                Yii::$app->session->setFlash('success', 'Все данные о респондентах заполнены! При необходимости добавляйте новых респондентов.');
            }
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
    }*/



    public function actionCreate($id)
    {
        $models = Respond::find()->where(['interview_id' => $id])->all();
        $interview = Interview::findOne($id);
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $newRespond = new Respond();
        $newRespond->interview_id = $id;
        if ($newRespond->load(Yii::$app->request->post()))
        {
            $kol = 0;
            foreach ($models as $elem){
                if ($newRespond->id != $elem->id && mb_strtolower(str_replace(' ', '', $newRespond->name)) == mb_strtolower(str_replace(' ', '',$elem->name))){
                    $kol++;
                }
            }

            if(Yii::$app->request->isAjax) {
                if ($kol == 0) {
                    if ($newRespond->save()) {
                        $interview->count_respond = $interview->count_respond + 1;
                        $interview->save();

                        $project->update_at = date('Y:m:d');
                        if ($project->save()) {

                            $responds = Respond::find()->where(['interview_id' => $id])->all();

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


    public function actionDataAvailability($id)
    {

        $models = Respond::find()->where(['interview_id' => $id])->all();

        $exist_data_respond = 0;
        $exist_data_descInterview = 0;
        foreach ($models as $model){

            if (!empty($model->info_respond) && !empty($model->place_interview) && !empty($model->date_plan)){
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


    public function actionIndex($id)
    {
        return $this->renderList($id);
    }


    protected function renderList($id)
    {
        $models = Respond::find()->where(['interview_id' => $id])->all();
        $interview = Interview::findOne($id);
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $segment_name = str_replace(' ', '_', $segment->name);
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $project_filename = str_replace(' ', '_', $project->project_name);

        $newRespond = new Respond();
        $newRespond->interview_id = $interview->id;

        $updateRespondForms = [];
        $createDescInterviewForms = [];
        $updateDescInterviewForms = [];
        foreach ($models as $i => $model) {

            $updateRespondForms[] = new UpdateRespondForm($model->id);

            $createDescInterviewForms[] = new DescInterview();

            $updateDescInterviewForms[] = $model->descInterview;

        }


        $query = Respond::find()->where(['interview_id' => $id]);

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


        return $this->render('_index', [
            'dataProvider' => $dataProvider,
            'models' => $models,
            'newRespond' => $newRespond,
            'updateRespondForms' => $updateRespondForms,
            'createDescInterviewForms' => $createDescInterviewForms,
            'updateDescInterviewForms' => $updateDescInterviewForms,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
            'project_filename' => $project_filename,
            'segment_name' => $segment_name,
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

        if (User::isUserSimple(Yii::$app->user->identity['username'])){

            if (empty($model->info_respond) || empty($model->place_interview) || empty($model->date_plan)){
                Yii::$app->session->setFlash('success', 'Для внесения новой информации о респонденте или корректировки пройдите по ссылке "Редактировать данные".');
            }

            if (!empty($model->info_respond) && !empty($model->place_interview) && !empty($model->date_plan) && empty($model->descInterview)){
                Yii::$app->session->setFlash('success', 'Для внесения данных интервью респондента пройдите по ссылке "Добавить интервью".');
            }
        }

        return $this->render('view', [
            'model' => $model,
            'segment' => $segment,
            'project' => $project,
            'desc_interview' => $desc_interview,
        ]);
    }




    /**
     * Updates an existing Respond model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $updateRespondForm = new UpdateRespondForm($id);

        $interview = Interview::find()->where(['id' => $model->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $models = Respond::find()->where(['interview_id' => $interview->id])->all();
        $user = User::find()->where(['id' => $project->user_id])->one();


        if ($updateRespondForm->load(Yii::$app->request->post())) {

            $kol = 0;
            foreach ($models as $item){
                if ($updateRespondForm->id != $item->id && mb_strtolower(str_replace(' ', '',$updateRespondForm->name)) == mb_strtolower(str_replace(' ', '',$item->name))){
                    $kol++;
                }
            }

            if(Yii::$app->request->isAjax) {

                if ($kol == 0){

                    foreach ($models as $elem){

                        if ($updateRespondForm->id == $elem->id && mb_strtolower(str_replace(' ', '',$updateRespondForm->name)) != mb_strtolower(str_replace(' ', '',$elem->name))){

                            $old_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                                mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
                                mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/interviews/' .
                                mb_convert_encoding($this->translit($elem->name) , "windows-1251") . '/';

                            $new_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                                mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
                                mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/interviews/' .
                                mb_convert_encoding($this->translit($updateRespondForm->name) , "windows-1251") . '/';

                            if (file_exists($old_dir)){
                                rename($old_dir, $new_dir);
                            }
                        }
                    }

                    $respond_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                        mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
                        mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/interviews/' .
                        mb_convert_encoding($this->translit($updateRespondForm->name) , "windows-1251") . '/';
                    if (!file_exists($respond_dir)){
                        mkdir($respond_dir, 0777);
                    }

                    if ($updateRespondForm->updateRespond($model)){

                        $project->update_at = date('Y:m:d');
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

        $model = Respond::findOne($id);
        $descInterview = DescInterview::find()->where(['respond_id' => $model->id])->one();
        $interview = Interview::find()->where(['id' => $model->interview_id])->one();
        $responds = Respond::find()->where(['interview_id' => $interview->id])->all();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $user = User::find()->where(['id' => $project->user_id])->one();


        if ($interview->count_respond == $interview->count_positive){

            Yii::$app->session->setFlash('error', "Количество респондентов не должно быть меньше количества представителей сегмента!");
            return $this->redirect(['/respond/index', 'id' => $model->interview_id]);
        }

        if (count($responds) == 1){

            Yii::$app->session->setFlash('error', 'Удаление последнего респондента запрещено!');
            return $this->redirect(['/respond/index', 'id' => $model->interview_id]);
        }

        if (Yii::$app->request->isAjax){

            $project->update_at = date('Y:m:d');

            if ($project->save()) {

                if ($descInterview) {
                    $descInterview->delete();
                }

                $del_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                    mb_convert_encoding($this->translit($project->project_name), "windows-1251") . '/segments/' .
                    mb_convert_encoding($this->translit($segment->name), "windows-1251") . '/interviews/' .
                    mb_convert_encoding($this->translit($model->name), "windows-1251") . '/';

                if (file_exists($del_dir)) {
                    $this->delTree($del_dir);
                }


                if ($model->delete()) {
                    $interview->count_respond = $interview->count_respond - 1;
                    $interview->save();
                }

                return $this->renderList($id = $interview->id);
            }

        }
        return false;
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
        $model = $this->findModel($id);
        $descInterview = DescInterview::find()->where(['respond_id' => $model->id])->one();
        $interview = Interview::find()->where(['id' => $model->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $user = User::find()->where(['id' => $project->user_id])->one();


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