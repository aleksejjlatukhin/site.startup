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

        if (in_array($action->id, ['index'])) {

            $user = User::findOne(Yii::$app->request->get());
            if ((Yii::$app->user->id == $user->id) || User::isUserDev(Yii::$app->user->identity['username'])
                || User::isUserAdmin(Yii::$app->user->identity['username']) || User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                return parent::beforeAction($action);

            }else {
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }
        }elseif (in_array($action->id, ['update-profile']) || in_array($action->id, ['change-password'])) {

            $user = User::findOne(Yii::$app->request->get());
            if ((Yii::$app->user->id == $user->id) || User::isUserDev(Yii::$app->user->identity['username'])) {

                return parent::beforeAction($action);

            }else {
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
    public function actionIndex($id)
    {
        if (User::isUserDev(Yii::$app->user->identity['username']) || User::isUserAdmin(Yii::$app->user->identity['username'])
            || User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

            $this->layout = '@app/modules/admin/views/layouts/main';
        }

        $user = User::findOne($id);

        if ((($user->role == User::ROLE_ADMIN) || ($user->role == User::ROLE_MAIN_ADMIN)  || ($user->role == User::ROLE_DEV))) {

            throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
        }

        if (!empty($user->projects)){

            $project_updated_at = [];

            foreach ($user->projects as $project) {

                $project_updated_at[] = $project->updated_at;
            }

            if (max($project_updated_at) > $user->updated_at){

                $user->updated_at = max($project_updated_at);
                $user->save();
            }

        }

        return $this->render('index', [
            'user' => $user,
        ]);
    }


    public function actionUpdateProfile($id)
    {
        if (User::isUserDev(Yii::$app->user->identity['username'])) {

            $this->layout = '@app/modules/admin/views/layouts/main';
        }

        $user = User::findOne($id);

        if ((($user->role == User::ROLE_ADMIN) || ($user->role == User::ROLE_MAIN_ADMIN)  || ($user->role == User::ROLE_DEV))) {

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

                return $this->redirect(['index', 'id' => $user->id]);
            }
        }

        return $this->render('update-profile', [
            'user' => $user,
            'model' => $model,
        ]);

    }

    public function actionChangePassword($id)
    {
        if (User::isUserDev(Yii::$app->user->identity['username'])) {

            $this->layout = '@app/modules/admin/views/layouts/main';
        }

        $user = User::findOne($id);

        if ((($user->role == User::ROLE_ADMIN) || ($user->role == User::ROLE_MAIN_ADMIN)  || ($user->role == User::ROLE_DEV))) {

            throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
        }

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


    public function actionNotFound($id)
    {
        if (User::isUserDev(Yii::$app->user->identity['username'])) {

            $this->layout = '@app/modules/admin/views/layouts/main';
        }

        if (!User::isActiveStatus(Yii::$app->user->identity['username'])){

            return $this->redirect(['/profile/index', 'id' => Yii::$app->user->id]);
        }

        if (User::isUserAdmin(Yii::$app->user->identity['username']) || User::isUserMainAdmin(Yii::$app->user->identity['username'])){

            return $this->redirect(['/profile/index', 'id' => $id]);
        }

        $user = User::find()->where(['id' => $id])->one();

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
