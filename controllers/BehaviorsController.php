<?php


namespace app\controllers;

use app\models\Projects;
use app\models\User;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;


class BehaviorsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [

                'class' => AccessControl::class,

                /*Вызов исключения в случае отсутствия доступа*/
                /*'denyCallback' => function ($rule, $action) {
                    throw new \Exception('Нет доступа.');
                },*/

                'rules' => [

                    [
                        'allow' => true,
                        'controllers' => ['site'],
                        'actions' => ['singup', 'login', 'index', 'target-segment', 'segment-problems', 'problem-confirmation',
                            'value-proposition', 'offer-confirmation', 'development-mvp', 'mvp-confirmation', 'business-model','send-email',
                            'reset-password'],
                        'verbs' => ['GET', 'POST'],
                        'roles' => ['?']
                    ],

                    /*[
                        'allow' => true,
                        'controllers' => ['site'],
                        'actions' => ['logout'],
                        'verbs' => ['POST'],
                        'roles' => ['@']
                    ],*/

                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ]

            ]

        ];
    }
}