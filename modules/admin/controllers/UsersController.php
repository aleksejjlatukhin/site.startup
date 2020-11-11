<?php


namespace app\modules\admin\controllers;


use app\models\ConfirmMvp;
use app\models\ConversationAdmin;
use app\models\Gcp;
use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Mvp;
use app\models\PasswordChangeForm;
use app\models\ProfileForm;
use app\models\Projects;
use app\models\Segment;
use app\models\User;
use yii\data\ActiveDataProvider;
use Yii;

class UsersController extends AppAdminController
{

    public function beforeAction($action)
    {
        if ($action->id == 'index') {

            if (User::isUserDev(Yii::$app->user->identity['username']) || User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif ($action->id == 'admins') {

            if (User::isUserDev(Yii::$app->user->identity['username']) || User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif ($action->id == 'status-update') {

            if (User::isUserDev(Yii::$app->user->identity['username']) || User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                if ($action->id == 'status-update') {
                    // ОТКЛЮЧАЕМ CSRF
                    $this->enableCsrfValidation = false;
                }

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif ($action->id == 'add-admin') {

            if (User::isUserDev(Yii::$app->user->identity['username']) || User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                if ($action->id == 'add-admin') {
                    // ОТКЛЮЧАЕМ CSRF
                    $this->enableCsrfValidation = false;
                }

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif ($action->id == 'group') {

            $user = User::findOne(Yii::$app->request->get());

            if ($user->id == Yii::$app->user->id || User::isUserDev(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif ($action->id == 'profile') {

            $user = User::findOne(Yii::$app->request->get());
            $admin = User::findOne(['id' => $user->id_admin]);

            if ($admin->id == Yii::$app->user->id || User::isUserDev(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (($action->id == 'project') || ($action->id == 'roadmap') || ($action->id == 'prefiles')) {

            $model = Projects::findOne(Yii::$app->request->get());
            $user = User::find()->where(['id' => $model->user_id])->one();
            $admin = User::findOne(['id' => $user->id_admin]);

            if ($admin->id == Yii::$app->user->id || User::isUserDev(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif ($action->id == 'profile-admin') {

            $admin = User::findOne(Yii::$app->request->get());

            if ($admin->id == Yii::$app->user->id || User::isUserDev(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (($action->id == 'update-profile') || ($action->id == 'change-password')) {

            $admin = User::findOne(Yii::$app->request->get());

            if ($admin->id == Yii::$app->user->id || User::isUserDev(Yii::$app->user->identity['username'])) {

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }else{
            return parent::beforeAction($action);
        }


    }



    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find()->where(['role' => User::ROLE_USER, 'confirm' => User::CONFIRM]),
            'pagination' => [
                'pageSize' => 5,
            ],
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
            ],
        ]);

        $users = User::find()->where(['role' => User::ROLE_USER, 'confirm' => User::CONFIRM])->all();
        foreach ($users as $user){

            if (!empty($user->projects)){

                $project_update_at = [];

                foreach ($user->projects as $project) {

                    $project_updated_at[] = $project->updated_at;
                }

                if (max($project_updated_at) > $user->updated_at){

                    $user->updated_at = max($project_updated_at);
                    $user->save();
                }

            }
        }


        $admins = User::find()->where(['role' => User::ROLE_ADMIN, 'confirm' => User::CONFIRM, 'status' => User::STATUS_ACTIVE])->all();

        foreach ($admins as $admin) {
            $admin->username = $admin->second_name . ' ' . $admin->first_name . ' ' . $admin->middle_name;
        }

        return $this->render('index',[
            'dataProvider' => $dataProvider,
            'admins' => $admins,
            'users' => $users,
        ]);
    }


    public function actionAdmins()
    {
        $dataProvider = new ActiveDataProvider(['query' => User::find()->where(['role' => User::ROLE_ADMIN, 'confirm' => User::CONFIRM])]);

        return $this->render('admins',[
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionStatusUpdate ($id, $status)
    {

        $model = User::findOne($id);

        if ($status == 'active'){
            $model->status = User::STATUS_ACTIVE;

        }elseif ($status == 'delete'){
            $model->status = User::STATUS_DELETED;
        }

        if (Yii::$app->request->isAjax){

            if ($model->save()){

                if(($model->status == User::STATUS_ACTIVE) && ($model->role == User::ROLE_ADMIN)) {

                    //Создание беседы между админом и главным админом
                    $model->createConversationMainAdmin();

                } elseif(($model->status == User::STATUS_ACTIVE) && ($model->role == User::ROLE_USER)) {

                    //Создание беседы между админом и проектантом
                    $model->createConversationAdmin($model);
                }

                //Отправка письма на почту пользователю при изменении его статуса
                $model->sendEmailUserStatus();

                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $model;
                return $model;

            }
        }
        return false;
    }



    public function actionAddAdmin ($id, $admin)
    {

        $model = User::findOne($id);

        $_admin_replace = User::findOne(['role' => User::ROLE_ADMIN, 'status' => User::STATUS_ACTIVE, 'confirm' => User::CONFIRM]);

        $_admin = User::findOne([
            'id' => $admin,
        ]);

        if ($_admin) {

            $model->id_admin = $admin;
        }else {
            $model->id_admin = $_admin_replace->id;
        }

        if (Yii::$app->request->isAjax){

            if ($model->save()){

                $conversation = ConversationAdmin::findOne([
                    'user_id' => $model->id,
                    ]);

                if ($conversation) {

                    $conversation->admin_id = $model->id_admin;
                    $conversation->save();
                }

                $response = [
                    'model' => $model,
                    'admin' => $_admin,
                    'admin_replace' => $_admin_replace,
                ];

                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;

            }
        }
        return false;
    }


    public function actionGroup($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find()->where(['role' => User::ROLE_USER, 'confirm' => User::CONFIRM, 'id_admin' => $id]),
            'pagination' => [
                'pageSize' => 5,
            ],
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
            ],
        ]);

        $users = User::find()->where(['role' => User::ROLE_USER, 'confirm' => User::CONFIRM, 'id_admin' => Yii::$app->user->id])->all();
        foreach ($users as $user){

            if (!empty($user->projects)){

                $projects_updated_at = [];

                foreach ($user->projects as $project) {

                    $projects_updated_at[] = $project->updated_at;
                }

                if (max($projects_updated_at) > $user->updated_at){

                    $user->updated_at = max($projects_updated_at);
                    $user->save();
                }

            }
        }


        return $this->render('group',[
            'dataProvider' => $dataProvider,

        ]);
    }



    public function actionProfile($id)
    {
        $user = User::findOne($id);

        return $this->render('profile',[

            'user' => $user,
        ]);
    }


    public function actionProject($id)
    {

        $model = Projects::findOne($id);
        $user = User::find()->where(['id' => $model->user_id])->one();
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

        $project = Projects::findOne($id);

        $user = User::find()->where(['id' => $project->user_id])->one();

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

                        $gps[] = $problem;

                        if (!empty($problem)){
                            if ($model->fact_gps !== $problem->date_gps){
                                $model->fact_gps = $problem->date_gps;
                                $model->save();
                            }
                        }
                    }
                    if ($problem->exist_confirm === 1){
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

                    if ($offer->exist_confirm === 1){
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

        $model = Projects::findOne($id);
        $user = User::find()->where(['id' => $model->user_id])->one();

        return $this->render('prefiles', [
            'model' => $model,
            'user' => $user,
        ]);
    }


    public function actionProfileAdmin ($id)
    {
        $admin = User::findOne($id);

        if (!(($admin->role == User::ROLE_ADMIN) || ($admin->role == User::ROLE_MAIN_ADMIN)  || ($admin->role == User::ROLE_DEV))) {

            throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
        }

        $users = User::find()->where(['id_admin' => $id])->all();

        $countProjects = 0;

        foreach ($users as $user) {

            $countProjects += count($user->projects);
        }


        return $this->render('profile-admin',[

            'admin' => $admin,
            'users' => $users,
            'countProjects' => $countProjects,
        ]);
    }



    public function actionUpdateProfile($id)
    {

        $user = User::findOne($id);

        if (!(($user->role == User::ROLE_ADMIN) || ($user->role == User::ROLE_MAIN_ADMIN)  || ($user->role == User::ROLE_DEV))) {

            throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
        }

        $model = new ProfileForm();

        $model->second_name = $user->second_name;
        $model->first_name = $user->first_name;
        $model->middle_name = $user->middle_name;
        $model->telephone = $user->telephone;
        $model->username = $user->username;
        $model->email = $user->email;

        if ($model->load(Yii::$app->request->post()) && $model->validate()){

            if ($model->update()){

                return $this->redirect(['/admin/users/profile-admin', 'id' => $id]);
            }
        }

        return $this->render('update-profile', [
            'user' => $user,
            'model' => $model,
        ]);

    }

    public function actionChangePassword($id)
    {

        $user = User::findOne($id);

        if (!(($user->role == User::ROLE_ADMIN) || ($user->role == User::ROLE_MAIN_ADMIN)  || ($user->role == User::ROLE_DEV))) {

            throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
        }

        $model = new PasswordChangeForm($user, []);

        if ($model->load(Yii::$app->request->post()) && $model->validate()){

            if ($model->changePassword()){

                return $this->redirect(['/admin/users/profile-admin', 'id' => $id]);
            }
        }

        return $this->render('change-password', [
            'user' => $user,
            'model' => $model,
        ]);
    }

}