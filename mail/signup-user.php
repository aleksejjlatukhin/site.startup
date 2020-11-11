<?php

use yii\helpers\Html;

echo '<p>Добрый день! '.Html::encode($user->first_name).', Вы зарегистрировались на сайте Spaccel.ru. </p>';
echo '<p>Ожидайте активации Вашего профиля. Мы известим Вас об этом в новом письме.</p>';
