<?php

namespace app\modules\admin\controllers;

use app\models\Client;
use app\models\ClientSettings;
use app\models\ClientUser;
use app\models\Projects;
use app\models\SortForm;
use app\models\User;
use Yii;
use yii\data\Pagination;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\Response;

class ProjectsController extends AppAdminController
{

    /**
     * @param $action
     * @return bool
     * @throws BadRequestHttpException
     * @throws HttpException
     */
    public function beforeAction($action)
    {
        if ($action->id == 'index') {

            if (User::isUserDev(Yii::$app->user->identity['username']) || User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif ($action->id == 'group') {

            $user = User::findOne(Yii::$app->request->get());
            /** @var ClientUser $clientUser */
            $clientUser = $user->clientUser;
            $clientSettings = ClientSettings::findOne(['client_id' => $clientUser->getClientId()]);

            if ($clientSettings->getAdminId() == Yii::$app->user->getId()) {
                if ($user->id == Yii::$app->user->id || User::isUserDev(Yii::$app->user->identity['username'])
                    || User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                    return parent::beforeAction($action);

                }else{
                    throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
                }
            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }else{
            return parent::beforeAction($action);
        }


    }

    /**
     * Страница сводной таблицы по проетам,
     * которые относятся к организации
     *
     * @return string
     */
    public function actionIndex ()
    {
        $sortModel = new SortForm();
        $show_count_projects = ['10' => 'по 10 проектов', '20' => 'по 20 проектов', '30' => 'по 30 проектов'];

        return $this->render('index', [
            'sortModel' => $sortModel,
            'show_count_projects' => $show_count_projects,
        ]);
    }


    /**
     * Страница сводной таблицы по проектам,
     * которые курирует трекер
     *
     * @param $id
     * @return string
     */
    public function actionGroup ($id)
    {
        $sortModel = new SortForm();
        $show_count_projects = ['10' => 'по 10 проектов', '20' => 'по 20 проектов', '30' => 'по 30 проектов'];

        return $this->render('index', [
            'sortModel' => $sortModel,
            'show_count_projects' => $show_count_projects,
        ]);
    }


    /**
     * Получение сводной таблицы по проектам
     *
     * @param $id
     * @param $page
     * @param $per_page
     * @return array|bool
     */
    public function actionGetResultProjects ($id, $page, $per_page)
    {
        if ($id == 'all_projects') {
            // вывести все проекты организации
            $user = User::findOne(Yii::$app->user->getId());
            /**
             * @var ClientUser $clientUser
             * @var Client $client
             */
            $clientUser = $user->clientUser;
            $client = $clientUser->client;
            $query = Projects::find()->with('user')
                ->leftJoin('user', '`user`.`id` = `projects`.`user_id`')
                ->leftJoin('client_user', '`client_user`.`user_id` = `user`.`id`')
                ->where(['client_user.client_id' => $client->getId()])
                ->orderBy(['id' => SORT_DESC]);

        } else {
            // вывести проекты, которые курирует трекер
            $query = Projects::find()
                ->leftJoin('user', '`user`.`id` = `projects`.`user_id`')
                ->where(['user.id_admin' => $id])->orderBy(['id' => SORT_DESC]);
        }

        $pages = new Pagination(['totalCount' => $query->count(), 'page' => ($page - 1), 'pageSize' => $per_page, ]);
        $pages->pageSizeParam = false; //убираем параметр $per-page
        $projects = $query->offset($pages->offset)->limit($per_page)->all();

        if(Yii::$app->request->isAjax) {

            $response = ['renderAjax' => $this->renderAjax('_index_ajax', ['projects' => $projects, 'pages' => $pages])];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }
}