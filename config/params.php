<?php

return [
    //'bsVersion' => '4.x', // this will set globally `bsVersion` to Bootstrap 4.x for all Krajee Extensions

    'adminEmail' => 'spaccel@mail.ru',
    //'senderEmail' => 'noreply@example.com',
    //'senderName' => 'Example.com mailer',
    'supportEmail' => 'fedotov.michail@mail.ru', // автоматическая отправка почты с данного емайл
    //'supportEmail' => 'aleksejj.latukhin@mail.ru',
    'secretKeyExpire' => 60 * 60,                       // время хранения секретного ключа
    'emailActivation' => true,                   // подтверждение нового пользователя по email
];
