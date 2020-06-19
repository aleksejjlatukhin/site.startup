<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'defaultRoute' => 'site/index',

    'modules' => [
        //Подключение модуля админки
        'admin' => [
            'class' => 'app\modules\admin\Module',
            'layout' => 'main',
        ],

        //Поключение kartik yii2-grid
        'gridview' =>  [
            'class' => '\kartik\grid\Module',
            'downloadAction' => 'gridview/export/download',
            // enter optional module parameters below - only if you need to
            // use your own export download action or custom translation
            // message source
            // 'downloadAction' => 'gridview/export/download',
            // 'i18n' => []
        ]
    ],

    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],

    'components' => [
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'nullDisplay' => '',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'PAzdKSPUM_BGQuOiTVMXI0sK3hiFNUda',
            'baseUrl' => '',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['site/login'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.

            //'useFileTransport' => true, /*Письмо отправляется в дирректорию runtime/mail/ */

            'useFileTransport' => false,/*Письмо отправляется на реальный адрес*/
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.mail.ru',
                'username' => 'fedotov.michail@mail.ru',
                'password' => 'PRUFieutu37%',
                //'username' => 'spaccel@mail.ru',
                //'password' => 'InfoSpa20',
                //'username' => 'aleksejj.latukhin@mail.ru',
                //'password' => 'IsSIX2sa%ra2',
                'port' => '465',
                'encryption' => 'ssl',
            ],

        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'target-segment' => 'site/target-segment',
                'segment-problems' => 'site/segment-problems',
                'problem-confirmation' => 'site/problem-confirmation',
                'value-proposition' => 'site/value-proposition',
                'offer-confirmation' => 'site/offer-confirmation',
                'development-mvp' => 'site/development-mvp',
                'mvp-confirmation' => 'site/mvp-confirmation',
                'business-model' => 'site/business-model',
                'send-email' => 'site/send-email',
                'update-profile' => 'profile/update-profile',
                'change-password' => 'profile/change-password',
                'profile' => 'profile/index',
                /*'projects' => 'projects/index',*/
                'admin' => 'admin/default/index',
                '<action:\w+>' => 'site/<action>',
            ],
        ],

    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
