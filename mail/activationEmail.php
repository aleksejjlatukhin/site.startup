<?php

use yii\helpers\Html;

echo 'Добрый день, '.Html::encode($user->getUsername()).'.';
echo 'Для подтверждения регистрации на сайте Spaccel.ru перейдите по этой ' .
    Html::a('ссылке.', Yii::$app->urlManager->createAbsoluteUrl(
        [
            '/site/activate-account',
            'key' => $user->secret_key
        ]
    ));