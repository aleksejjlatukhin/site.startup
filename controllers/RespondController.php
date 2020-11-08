<?php

namespace app\controllers;

use app\models\DescInterview;
use app\models\Interview;
use app\models\Projects;
use app\models\Segment;
use app\models\forms\UpdateRespondForm;
use app\models\User;
use Yii;
use app\models\Respond;
use yii\web\NotFoundHttpException;

class RespondController extends AppController
{

    public function beforeAction($action)
    {

        if (in_array($action->id, ['update']) || in_array($action->id, ['delete'])){

            $respond = Respond::findOne(Yii::$app->request->get());
            $interview = Interview::find()->where(['id' => $respond->interview_id])->one();
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){

            $interview = Interview::findOne(Yii::$app->request->get('id'));
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if ($project->user_id == Yii::$app->user->id || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

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
     * @return array
     */
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

    /**
     * @param $id
     * @return array
     */
    public function actionCreate($id)
    {
        $models = Respond::find()->where(['interview_id' => $id])->all();
        $interview = Interview::findOne($id);
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $limit_count_respond = Respond::LIMIT_COUNT;

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

                if (count($models) < $limit_count_respond) {

                    if ($kol == 0) {
                        if ($newRespond->save()) {
                            $interview->count_respond = $interview->count_respond + 1;
                            $interview->save();

                            $project->updated_at = time();
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
                } else {
                    $response = ['limit_count_respond' => true];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
    }

    /**
     * @param $id
     * @return Respond|array
     * @throws NotFoundHttpException
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

                        $project->updated_at = time();

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



    public function actionDelete ($id) {

        $model = Respond::findOne($id);
        $descInterview = DescInterview::find()->where(['respond_id' => $model->id])->one();
        $interview = Interview::find()->where(['id' => $model->interview_id])->one();
        $responds = Respond::find()->where(['interview_id' => $interview->id])->all();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $user = User::find()->where(['id' => $project->user_id])->one();

        if (Yii::$app->request->isAjax){

            if (count($responds) == 1){

                $response = ['zero_value_responds' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }

            if ($interview->count_respond == $interview->count_positive){

                $response = ['number_less_than_allowed' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }

            $project->updated_at = time();

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

                $response = ['success' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }

        }
        return false;
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