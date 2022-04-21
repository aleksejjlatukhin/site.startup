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
        $currentUser = User::findOne(Yii::$app->user->getId());
        /** @var ClientUser $currentClientUser */
        $currentClientUser = $currentUser->clientUser;

        if ($action->id == 'index') {

            if (User::isUserDev($currentUser->getUsername()) || User::isUserMainAdmin($currentUser->getUsername())) {

                return parent::beforeAction($action);

            }else{

                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['group'])) {

            $user = User::findOne(Yii::$app->request->get('id'));

            if ($user->getId() == $currentUser->getId()) {

                return parent::beforeAction($action);
            }
            elseif (User::isUserDev($currentUser->getUsername()) || User::isUserMainAdmin($currentUser->getUsername())) {

                /** @var ClientUser $modelClientUser */
                $modelClientUser = $user->clientUser;

                if ($currentClientUser->getClientId() == $modelClientUser->getClientId()) {

                    return parent::beforeAction($action);

                } else {

                    throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
                }
            } else{

                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }
        } elseif (in_array($action->id, ['client'])) {

            $client = Client::findOne(Yii::$app->request->get('id'));

            if (User::isUserDev($currentUser->getUsername()) || User::isUserMainAdmin($currentUser->getUsername())) {

                if ($currentClientUser->getClientId() == $client->getId()) {

                    return parent::beforeAction($action);

                } else {

                    throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
                }
            } else{

                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }
        } else{

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
        $pageClientProjects = false;
        $sortModel = new SortForm();
        $show_count_projects = ['10' => 'по 10 проектов', '20' => 'по 20 проектов', '30' => 'по 30 проектов'];

        return $this->render('index', [
            'sortModel' => $sortModel,
            'show_count_projects' => $show_count_projects,
            'pageClientProjects' => $pageClientProjects
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
        $pageClientProjects = false;
        $sortModel = new SortForm();
        $show_count_projects = ['10' => 'по 10 проектов', '20' => 'по 20 проектов', '30' => 'по 30 проектов'];

        return $this->render('index', [
            'sortModel' => $sortModel,
            'show_count_projects' => $show_count_projects,
            'pageClientProjects' => $pageClientProjects
        ]);
    }


    /**
     * Страница сводной таблицы по проектам,
     * которые относятся к организации с указанным id
     *
     * @param int $id
     * @return string
     */
    public function actionClient ($id)
    {
        $pageClientProjects = true;
        $sortModel = new SortForm();
        $show_count_projects = ['10' => 'по 10 проектов', '20' => 'по 20 проектов', '30' => 'по 30 проектов'];

        return $this->render('index', [
            'sortModel' => $sortModel,
            'show_count_projects' => $show_count_projects,
            'pageClientProjects' => $pageClientProjects
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


    /**
     * Получение сводной таблицы по проектам,
     * которые относятся к организации с указанным id
     *
     * @param $id
     * @param $page
     * @param $per_page
     * @return array|bool
     */
    public function actionGetResultClientProjects ($id, $page, $per_page)
    {
        $query = Projects::find()
            ->leftJoin('user', '`user`.`id` = `projects`.`user_id`')
            ->leftJoin('client_user', '`client_user`.`user_id` = `user`.`id`')
            ->where(['client_user.client_id' => $id])->orderBy(['id' => SORT_DESC]);

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