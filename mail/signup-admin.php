<?php

use yii\helpers\Html;

echo 'На сайте Spaccel.ru был зарегистрирован новый пользователь: '. $user->getUsername() . '(' . $user->getTextRole() . ')';

