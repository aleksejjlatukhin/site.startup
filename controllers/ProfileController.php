<?php

namespace app\controllers;

use app\models\ConfirmMvp;
use app\models\Gcp;
use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Mvp;
use app\models\PasswordChangeForm;
use app\models\ProfileForm;
use app\models\Projects;
use app\models\Segment;
use Yii;
use app\models\User;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProfileController implements the CRUD actions for User model.
 */
class ProfileController extends AppController
{
    public $layout = 'profile';


    public function beforeAction($action)
    {

        if (in_array($action->id, ['project']) || in_array($action->id, ['roadmap']) || in_array($action->id, ['prefiles'])){

            $model = Projects::findOne(Yii::$app->request->get());

            /*Ограничение доступа к проэктам пользователя*/
            if ($model->user_id == Yii::$app->user->id){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }
        }else{
            return parent::beforeAction($action);
        }
    }


    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $user = User::find()->where(['id' => \Yii::$app->user->id])->one();

        if (!empty($user->projects)){

            $project_update_at = [];

            foreach ($user->projects as $project) {

                $project_update_at[] = strtotime($project->update_at);
            }

            if (max($project_update_at) > $user->updated_at){

                $user->updated_at = max($project_update_at);
                $user->save();
            }

        }

        return $this->render('index', [
            'user' => $user,
        ]);
    }


    public function actionUpdateProfile()
    {

        $user = User::find()->where(['id' => \Yii::$app->user->id])->one();
        $model = new ProfileForm();

        $model->second_name = $user->second_name;
        $model->first_name = $user->first_name;
        $model->middle_name = $user->middle_name;
        $model->telephone = $user->telephone;
        $model->username = $user->username;
        $model->email = $user->email;

        if ($model->load(Yii::$app->request->post()) && $model->validate()){

            if ($model->update()){

                return $this->redirect(['index']);
            }
        }

        return $this->render('update-profile', [
            'user' => $user,
            'model' => $model,
        ]);

    }

    public function actionChangePassword()
    {
        $user = User::find()->where(['id' => \Yii::$app->user->id])->one();
        $model = new PasswordChangeForm($user, []);

        if ($model->load(Yii::$app->request->post()) && $model->validate()){

            if ($model->changePassword()){

                return $this->redirect(['index']);
            }
        }

        return $this->render('change-password', [
            'user' => $user,
            'model' => $model,
        ]);
    }



    public function actionProject($id)
    {
        $user = User::find()->where(['id' => \Yii::$app->user->id])->one();

        $model = Projects::findOne($id);
        $segments = Segment::find()->where(['project_id' => $model->id])->all();
        $problems = [];
        $offers = [];
        $mvProducts = [];
        $confirmMvps = [];
        foreach ($segments as $segment){
            $generationProblems = GenerationProblem::find()->where(['interview_id' => $segment->interview->id])->all();
            foreach ($generationProblems as $k => $generationProblem){
                $problems[] = $generationProblem;
                $gcps = Gcp::find()->where(['confirm_problem_id' => $generationProblem->confirm->id])->all();
                foreach ($gcps as $gcp){
                    $offers[] = $gcp;
                    $mvps = Mvp::find()->where(['confirm_gcp_id' => $gcp->confirm->id])->all();
                    foreach ($mvps as $mvp){
                        $mvProducts[] = $mvp;
                        $confMvp = ConfirmMvp::find()->where(['mvp_id' => $mvp->id])->one();
                        $confirmMvps[] = $confMvp;
                    }
                }
            }
        }


        return $this->render('project', [
            'user' => $user,
            'model' => $model,
            'segments' => $segments,
            'generationProblems' => $generationProblems,
            'problems' => $problems,
            'offers' => $offers,
            'mvProducts' => $mvProducts,
            'confirmMvps' => $confirmMvps,

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
        $user = User::find()->where(['id' => \Yii::$app->user->id])->one();

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
                        if (!empty($problem)){

                            $gps[] = $problem;

                            if ($model->fact_gps !== $problem->date_gps){
                                $model->fact_gps = $problem->date_gps;
                                $model->save();
                            }
                        }
                    }
                    if ($problem->date_confirm !== null){
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

                    if ($offer->date_confirm !== null){
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
            'user' => $user,
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


    public function actionPrefiles($id)
    {
        $user = User::find()->where(['id' => \Yii::$app->user->id])->one();

        $model = Projects::findOne($id);

        return $this->render('prefiles', [
            'model' => $model,
            'user' => $user,
        ]);
    }


    public function actionNotFound()
    {
        $user = User::find()->where(['id' => \Yii::$app->user->id])->one();

        return $this->render('not-found', [
            'user' => $user,
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
