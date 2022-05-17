<?php


namespace app\modules\admin\controllers;

use app\models\Client;
use app\models\ClientActivation;
use app\models\ClientSettings;
use app\models\ClientUser;
use app\models\ConversationDevelopment;
use app\models\CustomerManager;
use app\models\User;
use app\modules\admin\models\form\FormCreateAdminCompany;
use app\modules\admin\models\form\FormCreateClient;
use yii\base\Exception;
use yii\data\Pagination;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\Response;
use Yii;

/**
 * Контроллер с методами для настройки и получения информации о клиентах (организациях)
 * Доступ только для главного админа Spaccel
 *
 * Class ClientsController
 * @package app\modules\admin\controllers
 */
class ClientsController extends AppAdminController
{

    //TODO: Написать консольную команду,
    // которая будет блокировать клиента (изменять его статус),
    // если время тарифа закончилось. Команда должна запускаться по крону.
    // Пока это не реализвано, изменять нужно в ручную в интерфейсе админки.

    /**
     * @param $action
     * @return bool
     * @throws BadRequestHttpException
     * @throws HttpException
     */
    public function beforeAction($action)
    {
        if (User::isUserMainAdmin(Yii::$app->user->identity['username'])) {
            return parent::beforeAction($action);
        }else{
            throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
        }
    }


    /**
     * Старница со списком клиентов (организаций)
     * @return string
     */
    public function actionIndex()
    {
        $clientSpaccel = Client::find()
            ->leftJoin('client_user', '`client_user`.`client_id` = `client`.`id`')
            ->leftJoin('user', '`user`.`id` = `client_user`.`user_id`')
            ->where(['user.role' => User::ROLE_MAIN_ADMIN])
            ->one();

        $countUsersOnPage = 20;
        $query = Client::find()->where(['!=', 'id', $clientSpaccel->getId()]);
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => $countUsersOnPage, ]);
        $pages->pageSizeParam = false; //убираем параметр $per-page
        $clients = $query->offset($pages->offset)->limit($countUsersOnPage)->all();

        return $this->render('index', [
            'pages' => $pages,
            'clients' => $clients,
            'clientSpaccel' => $clientSpaccel
        ]);
    }


    /**
     * Получить список активных
     * менеджеров по клиентам
     *
     * @param int $clientId
     * @return array|bool
     */
    public function actionGetListManagers($clientId)
    {
        $managers = User::findAll(['role' => User::ROLE_MANAGER, 'status' => User::STATUS_ACTIVE, 'confirm' => User::CONFIRM]);
        foreach ($managers as $manager) {
            $manager->username = $manager->second_name . ' ' . $manager->first_name . ' ' . $manager->middle_name;
        }

        $customerManager = CustomerManager::find()->where(['client_id' => $clientId])->orderBy(['created_at' => SORT_DESC])->one();
        if (!$customerManager) {
            $customerManager = new CustomerManager();
            $customerManager->setClientId($clientId);
        }

        if (Yii::$app->request->isAjax){
            $response = ['renderAjax' => $this->renderAjax('list-managers', ['customerManager' => $customerManager, 'managers' => $managers])];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * Назначить менеджера организации
     *
     * @return array|bool
     */
    public function actionAddManager()
    {
        if (Yii::$app->request->isAjax){
            if ($_POST['CustomerManager']) {
                $userId = $_POST['CustomerManager']['user_id'];
                $clientId = $_POST['CustomerManager']['client_id'];
                $customerManager = CustomerManager::find()->where(['client_id' => $clientId])->orderBy(['created_at' => SORT_DESC])->one();
                if ($customerManager) {
                    if ($customerManager->getUserId() != $userId) {
                        CustomerManager::addManager($clientId, $userId);
                        $response = [
                            'renderAjax' => $this->renderAjax('data_client', ['client' => Client::findOne($clientId)]),
                            'client_id' => $clientId,
                        ];
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        Yii::$app->response->data = $response;
                        return $response;
                    }
                    return false;
                } else {
                    CustomerManager::addManager($clientId, $userId);
                    $response = [
                        'renderAjax' => $this->renderAjax('data_client', ['client' => Client::findOne($clientId)]),
                        'client_id' => $clientId,
                    ];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
        return false;
    }


    /**
     * Создание нового клиента (организации)
     *
     * @return string|Response
     * @throws Exception
     */
    public function actionCreate()
    {
        $formCreateAdminCompany = new FormCreateAdminCompany();
        $formCreateClient = new FormCreateClient();
        $adminCompany = null;
        if ($formCreateAdminCompany->load(Yii::$app->request->post()) && $formCreateAdminCompany->validate()) {
            foreach ($formCreateAdminCompany->attributes as $k => $value) {
                $formCreateClient->adminCompany .= $k . 'abracadabraKey:' . $value . 'abracadabraValue';
            }
            return $this->render('create_2', [
                'formCreateClient' => $formCreateClient
            ]);
        }
        if ($formCreateClient->load(Yii::$app->request->post()) && $formCreateClient->validate()) {

            $attributesAdminCompany = array();
            $data = explode('abracadabraValue', $formCreateClient->adminCompany);
            foreach ($data as $attribute) {
                $result = explode('abracadabraKey:', $attribute);
                $attributesAdminCompany[$result[0]] = $result[1];
            }
            $formCreateAdminCompany->attributes = $attributesAdminCompany;

            if ($client = $formCreateClient->create()) {
                if ($admin = $formCreateAdminCompany->create()) {
                    if (ClientUser::createRecord($client->getId(), $admin->getId())) {
                        if (ClientSettings::createRecord(['client_id' => $client->getId(), 'admin_id' => $admin->getId()])) {
                            return $this->redirect('/admin/clients/index');
                        } else {
                            ConversationDevelopment::deleteAll(['user_id' => $admin->getId()]);
                            ClientUser::deleteAll(['client_id' => $client->getId(), 'user_id' => $admin->getId()]);
                            User::deleteAll(['id' => $admin->getId()]);
                            ClientActivation::deleteAll(['client_id' => $client->getId()]);
                            Client::deleteAll(['id' => $client->getId()]);
                        }
                    } else {
                        ConversationDevelopment::deleteAll(['user_id' => $admin->getId()]);
                        User::deleteAll(['id' => $admin->getId()]);
                        ClientActivation::deleteAll(['client_id' => $client->getId()]);
                        Client::deleteAll(['id' => $client->getId()]);
                    }
                } else {
                    ClientActivation::deleteAll(['client_id' => $client->getId()]);
                    Client::deleteAll(['id' => $client->getId()]);
                }
            }

            return $this->render('create_1', [
                'formCreateAdminCompany' => $formCreateAdminCompany
            ]);
        }
        return $this->render('create_1', [
            'formCreateAdminCompany' => $formCreateAdminCompany
        ]);
    }


    /**
     * Изменение статуса организации (клиента)
     *
     * @param $clientId
     * @return array|bool
     */
    public function actionChangeStatus($clientId)
    {
        if (Yii::$app->request->isAjax) {
            $client = Client::findOne($clientId);
            $status = $client->findClientActivation()->getStatus();
            $clientActivation = new ClientActivation();
            $clientActivation->setClientId($clientId);
            if ($status == ClientActivation::ACTIVE) {
                $clientActivation->setStatus(ClientActivation::NO_ACTIVE);
            } else {
                $clientActivation->setStatus(ClientActivation::ACTIVE);
            }
            if ($clientActivation->save()) {
                $response = [
                    'renderAjax' => $this->renderAjax('data_client', ['client' => $client]),
                    'client_id' => $clientId
                ];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;
            }
        }
        return false;
    }


    /**
     * @param int $id
     * @return string
     */
    public function actionView($id)
    {
        $client = Client::findById($id);
        $clientSettings = ClientSettings::findOne(['client_id' => $id]);

        return $this->render('view', [
            'client' => $client,
            'clientSettings' => $clientSettings,
        ]);
    }

}