<?php

use yii\helpers\Html;

echo 'Добрый день! '.Html::encode($user->first_name).', Вами был отправлен запрос для восстановления пароля на сайте Spaccel.ru. <br>';
echo Html::a('Для смены пароля перейдите по этой ссылке.',
    Yii::$app->urlManager->createAbsoluteUrl(
        [
            '/site/reset-password',
            'key' => $user->secret_key
        ]
    ));
