<?php


namespace app\modules\admin\controllers;


use app\models\Client;

class ClientsController extends AppAdminController
{

    //public $layout = '@app/modules/admin/views/layouts/users';

    public function actionIndex()
    {
        $clients = Client::find()->all();
        return $this->render('index', [
            'clients' => $clients
        ]);
    }
}