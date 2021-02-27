<?php

namespace app\modules\admin\controllers;

use app\models\forms\AvatarForm;
use app\models\forms\PasswordChangeForm;
use app\models\forms\ProfileForm;
use app\models\Projects;
use Yii;
use app\models\User;
use yii\web\NotFoundHttpException;


class ProfileController extends AppAdminController
{

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\HttpException
     */
    public function beforeAction($action)
    {

        if (in_array($action->id, ['index'])) {

            $admin = User::findOne(Yii::$app->request->get('id'));

            if ($admin->id == Yii::$app->user->id || User::isUserDev(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

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
    public function actionIndex($id)
    {
        $user = User::findOne($id);
        $count_users = User::find()->where(['id_admin' => $id])->count();
        $countProjects = Projects::find()->with('user')
            ->leftJoin('user', '`user`.`id` = `projects`.`user_id`')
            ->where(['user.id_admin' => $id])->count();
        $profile = new ProfileForm($id);
        $passwordChangeForm = new PasswordChangeForm($user);
        $avatarForm = new AvatarForm($id);

        return $this->render('index', [
            'user' => $user,
            'count_users' => $count_users,
            'countProjects' => $countProjects,
            'profile' => $profile,
            'passwordChangeForm' => $passwordChangeForm,
            'avatarForm' => $avatarForm,
        ]);
    }


    /**
     * @param $id
     * @return array
     */
    public function actionUpdateProfile($id)
    {
        $model = new ProfileForm($id);
        $count_users = User::find()->where(['id_admin' => $id])->count();
        $countProjects = Projects::find()->with('user')
            ->leftJoin('user', '`user`.`id` = `projects`.`user_id`')
            ->where(['user.id_admin' => $id])->count();

        if ($model->load(Yii::$app->request->post())){

            if (Yii::$app->request->isAjax) {

                if ($model->validate()) {

                    if ($model->update()){

                        if ($model->checking_mail_sending) {

                            $user = User::findOne($id);

                            $response = [
                                'success' => true, 'user' => User::findOne($id),
                                'renderAjax' => $this->renderAjax('ajax_data_profile', [
                                    'user' => $user, 'count_users' => $count_users, 'countProjects' => $countProjects, 'profile' => new ProfileForm($id),
                                    'passwordChangeForm' => new PasswordChangeForm($user), 'avatarForm' => new AvatarForm($id),
                                ]),
                            ];
                            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            \Yii::$app->response->data = $response;
                            return $response;

                        }
                        else {

                            //Письмо с уведомлением не отправлено
                            $response = ['error_send_email' => true];
                            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            \Yii::$app->response->data = $response;
                            return $response;
                        }
                    }

                } else {

                    $response = [
                        'error_uniq_email' => false,
                        'error_match_username' => false,
                        'error_uniq_username' => false,
                    ];

                    if ($model->uniq_email === false) {
                        $response['error_uniq_email'] = true;
                    }

                    if ($model->uniq_username === false) {
                        $response['error_uniq_username'] = true;
                    }

                    if ($model->match_username === false) {
                        $response['error_match_username'] = true;
                    }

                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
    }


    /**
     * @param $id
     * @return array
     */
    public function actionChangePassword($id)
    {
        $user = User::findOne($id);
        $model = new PasswordChangeForm($user);

        if ($model->load(Yii::$app->request->post())){
            if (Yii::$app->request->isAjax) {
                if ($model->validate()) {
                    if ($model->changePassword()) {

                        $response = ['success' => true];
                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;
                    }

                } else {

                    if (!$model->validate(['currentPassword'])) {

                        $response = ['errorCurrentPassword' => 'true'];
                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;
                    }
                }
            }
        }
    }


    /**
     * @param $id
     * @return array|bool
     * @throws \Throwable
     * @throws \yii\base\Exception
     * @throws \yii\db\StaleObjectException
     */
    public function actionLoadAvatarImage ($id)
    {
        $avatarForm = new AvatarForm($id);
        $count_users = User::find()->where(['id_admin' => $id])->count();
        $countProjects = Projects::find()->with('user')
            ->leftJoin('user', '`user`.`id` = `projects`.`user_id`')
            ->where(['user.id_admin' => $id])->count();

        if (Yii::$app->request->isAjax) {

            if (isset($_POST['imageMin'])) {

                if ($avatarForm->loadMinImage()) {

                    $user = User::findOne($id);

                    $response = [
                        'success' => true, 'user' => $user,
                        'renderAjax' => $this->renderAjax('ajax_data_profile', [
                            'user' => $user, 'count_users' => $count_users, 'countProjects' => $countProjects, 'profile' => new ProfileForm($id),
                            'passwordChangeForm' => new PasswordChangeForm($user), 'avatarForm' => new AvatarForm($id),
                        ]),
                    ];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }

            } else {

                if ($result = $avatarForm->loadMaxImage()) {

                    $response = $result;
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }
                return false;
            }
        }
        return false;
    }


    /**
     * @param $id
     * @return array|bool
     */
    public function actionGetDataAvatar ($id)
    {
        $user = User::findOne($id);

        if (Yii::$app->request->isAjax) {

            $response = ['path_max' => '/web/upload/user-' . $user->id . '/avatar/' . $user->avatar_max_image,];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;

        }
        return false;
    }

    public function actionDeleteUnusedImage ($id)
    {
        $avatarForm = new AvatarForm($id);

        if (Yii::$app->request->isAjax) {
            if (isset($_POST['imageMax'])) {
                if ($avatarForm->deleteUnusedImage()) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param $id
     * @return array|bool
     */
    public function actionDeleteAvatar ($id)
    {
        $avatarForm = new AvatarForm($id);
        $count_users = User::find()->where(['id_admin' => $id])->count();
        $countProjects = Projects::find()->with('user')
            ->leftJoin('user', '`user`.`id` = `projects`.`user_id`')
            ->where(['user.id_admin' => $id])->count();

        if (Yii::$app->request->isAjax) {

            if ($avatarForm->deleteOldAvatarImages()) {

                $user = User::findOne($id);

                $response = [
                    'success' => true, 'user' => $user,
                    'renderAjax' => $this->renderAjax('ajax_data_profile', [
                        'user' => $user, 'count_users' => $count_users, 'countProjects' => $countProjects, 'profile' => new ProfileForm($id),
                        'passwordChangeForm' => new PasswordChangeForm($user), 'avatarForm' => new AvatarForm($id),
                    ]),
                ];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }
        }
        return false;
    }


    /**
     * @param $id
     * @return User|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
