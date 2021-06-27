<?php

namespace app\controllers;

use app\models\forms\AvatarForm;
use app\models\forms\PasswordChangeForm;
use app\models\forms\ProfileForm;
use app\models\Projects;
use app\models\Roadmap;
use app\models\Segments;
use Throwable;
use Yii;
use app\models\User;
use yii\base\Exception;
use yii\db\StaleObjectException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;


class ProfileController extends AppUserPartController
{
    public $layout = 'profile';


    /**
     * @param $action
     * @return bool
     * @throws HttpException
     */
    public function beforeAction($action)
    {

        if (in_array($action->id, ['index']) || in_array($action->id, ['result']) || in_array($action->id, ['roadmap'])
            || in_array($action->id, ['report']) || in_array($action->id,['presentation'])) {

            $user = User::findOne(Yii::$app->request->get());
            if ((Yii::$app->user->id == $user->id) || User::isUserDev(Yii::$app->user->identity['username'])
                || User::isUserAdmin(Yii::$app->user->identity['username']) || User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                return parent::beforeAction($action);

            }else {
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }
        }else{
            return parent::beforeAction($action);
        }
    }


    /**
     * Lists all User models.
     * @param $id
     * @return mixed
     */
    public function actionIndex($id)
    {
        $user = User::findOne($id);
        $profile = new ProfileForm($id);
        $passwordChangeForm = new PasswordChangeForm($user);
        $avatarForm = new AvatarForm($id);

        return $this->render('index', [
            'user' => $user,
            'profile' => $profile,
            'passwordChangeForm' => $passwordChangeForm,
            'avatarForm' => $avatarForm,
        ]);
    }


    /**
     * @param $id
     * @return array|bool
     */
    public function actionGetUserIsOnline($id)
    {
        $user = User::findOne($id);

        if (Yii::$app->request->isAjax) {

            if ($user->checkOnline === true) {

                $response = ['user_online' => true, 'message' => 'Пользователь сейчас Online'];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;
            } elseif ($user->checkOnline !== true && $user->checkOnline !== false) {

                $response = ['user_logout' => true, 'message' => 'Пользователь был в сети ' . $user->checkOnline];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;
            }
        }
        return false;
    }


    /**
     * @param $id
     * @return array
     */
    public function actionUpdateProfile($id)
    {
        $model = new ProfileForm($id);

        if ($model->load(Yii::$app->request->post())){

            if (Yii::$app->request->isAjax) {

                if ($model->validate()) {

                    if ($model->update()){

                        if ($model->checking_mail_sending) {

                            $user = User::findOne($id);

                            $response = [
                                'success' => true, 'user' => User::findOne($id),
                                'renderAjax' => $this->renderAjax('ajax_data_profile', [
                                    'user' => $user, 'profile' => new ProfileForm($id),
                                    'passwordChangeForm' => new PasswordChangeForm($user), 'avatarForm' => new AvatarForm($id),
                                ]),
                            ];
                            Yii::$app->response->format = Response::FORMAT_JSON;
                            Yii::$app->response->data = $response;
                            return $response;

                        }
                        else {

                            //Письмо с уведомлением не отправлено
                            $response = ['error_send_email' => true];
                            Yii::$app->response->format = Response::FORMAT_JSON;
                            Yii::$app->response->data = $response;
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

                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
    }


    public function actionChangePassword($id)
    {
        $user = User::findOne($id);
        $model = new PasswordChangeForm($user);

        if ($model->load(Yii::$app->request->post())){
            if (Yii::$app->request->isAjax) {
                if ($model->validate()) {
                    if ($model->changePassword()) {

                        $response = ['success' => true];
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        Yii::$app->response->data = $response;
                        return $response;
                    }

                } else {

                    if (!$model->validate(['currentPassword'])) {

                        $response = ['errorCurrentPassword' => 'true'];
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        Yii::$app->response->data = $response;
                        return $response;
                    }
                }
            }
        }
    }


    /**
     * @param $id
     * @return array|bool
     * @throws Throwable
     * @throws Exception
     * @throws StaleObjectException
     */
    public function actionLoadAvatarImage ($id)
    {
        $avatarForm = new AvatarForm($id);

        if (Yii::$app->request->isAjax) {

            if (isset($_POST['imageMin'])) {

                if ($avatarForm->loadMinImage()) {

                    $user = User::findOne($id);

                    $response = [
                        'success' => true, 'user' => $user,
                        'renderAjax' => $this->renderAjax('ajax_data_profile', [
                            'user' => $user, 'profile' => new ProfileForm($id),
                            'passwordChangeForm' => new PasswordChangeForm($user), 'avatarForm' => new AvatarForm($id),
                        ]),
                    ];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }

            } else {

                if ($result = $avatarForm->loadMaxImage()) {

                    $response = $result;
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
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
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;

        }
        return false;
    }

    /**
     * @param $id
     * @return bool
     */
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

        if (Yii::$app->request->isAjax) {

            if ($avatarForm->deleteOldAvatarImages()) {

                $user = User::findOne($id);

                $response = [
                    'success' => true, 'user' => $user,
                    'renderAjax' => $this->renderAjax('ajax_data_profile', [
                        'user' => $user, 'profile' => new ProfileForm($id),
                        'passwordChangeForm' => new PasswordChangeForm($user), 'avatarForm' => new AvatarForm($id),
                    ]),
                ];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;
            }
        }
        return false;
    }


    /**
     * @param $id
     * @return string
     */
    public function actionResult ($id)
    {
        $user = User::findOne($id);
        $projects = Projects::findAll(['user_id' => $id]);

        return $this->render('result', [
            'user' => $user,
            'projects' => $projects,
        ]);
    }


    /**
     * @param $id
     * @return array|bool
     */
    public function actionGetDataProjects ($id)
    {
        $projects = Projects::findAll(['user_id' => $id]);

        if(Yii::$app->request->isAjax) {

            $response = ['projects' => $projects];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @param $id
     * @return array|bool
     */
    public function actionGetResultProject ($id)
    {
        $project = Projects::findOne($id);

        if(Yii::$app->request->isAjax) {

            $response = ['renderAjax' => $this->renderAjax('_result_ajax', ['project' => $project])];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @param $id
     * @return string
     */
    public function actionRoadmap ($id)
    {
        $user = User::findOne($id);
        $projects = Projects::findAll(['user_id' => $id]);

        return $this->render('roadmap', [
            'user' => $user,
            'projects' => $projects,
        ]);
    }


    /**
     * @param $id
     * @return array|bool
     */
    public function actionGetRoadmapProject ($id)
    {
        $project = Projects::findOne($id);
        $roadmaps = [];

        foreach ($project->segments as $i => $segment){
            $roadmaps[$i] = new Roadmap($segment->id);
        }

        if(Yii::$app->request->isAjax) {

            $response = [
                'renderAjax' => $this->renderAjax('_roadmap_ajax', ['roadmaps' => $roadmaps]),
                'project' => $project,
            ];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @param $id
     * @return string
     */
    public function actionReport ($id)
    {
        $user = User::findOne($id);
        $projects = Projects::findAll(['user_id' => $id]);

        return $this->render('report', [
            'user' => $user,
            'projects' => $projects,
        ]);
    }


    /**
     * @param $id
     * @return array|bool
     */
    public function actionGetReportProject ($id)
    {
        $project = Projects::findOne($id);
        $segments = Segments::findAll(['project_id' => $id]);

        foreach ($segments as $s => $segment) {

            $segment->propertyContainer->addProperty('title', 'Сегмент ' . ($s+1));

            foreach ($segment->problems as $p => $problem) {

                $problem->propertyContainer->addProperty('title', 'ГПС ' . ($s+1) . '.' . ($p+1));

                foreach ($problem->gcps as $g => $gcp) {

                    $gcp->propertyContainer->addProperty('title', 'ГЦП ' . ($s+1) . '.' . ($p+1) . '.' . ($g+1));

                    foreach ($gcp->mvps as $m => $mvp) {

                        $mvp->propertyContainer->addProperty('title', 'MVP ' . ($s+1) . '.' . ($p+1) . '.' . ($g+1) . '.' . ($m+1));
                    }
                }
            }
        }

        if(Yii::$app->request->isAjax) {

            $response = [
                'renderAjax' => $this->renderAjax('_report_ajax', ['segments' => $segments]),
                'project' => $project,
            ];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @param $id
     * @return string
     */
    public function actionPresentation ($id)
    {
        $user = User::findOne($id);
        $projects = Projects::findAll(['user_id' => $id]);

        return $this->render('presentation', [
            'user' => $user,
            'projects' => $projects,
        ]);
    }


    /**
     * @param $id
     * @return array|bool
     */
    public function actionGetPresentationProject ($id)
    {
        $project = Projects::findOne($id);

        if(Yii::$app->request->isAjax) {

            $response = [
                'renderAjax' => $this->renderAjax('_presentation_ajax', ['project' => $project]),
                'project' => $project,
            ];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
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
