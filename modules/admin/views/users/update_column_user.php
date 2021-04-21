<?php

use yii\helpers\Html;

?>

<!--Проверка существования аватарки-->
<?php if ($user->avatar_image) : ?>
    <?= Html::img('/web/upload/user-'.$user->id.'/avatar/'.$user->avatar_image, ['class' => 'user_picture']); ?>
<?php else : ?>
    <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default']); ?>
<?php endif; ?>

<!--Проверка онлайн статуса-->
<?php if ($user->checkOnline === true) : ?>
    <div class="checkStatusOnlineUser active"></div>
<?php else : ?>
    <div class="checkStatusOnlineUser"></div>
<?php endif; ?>

<div class="block-fio-and-date-last-visit">
    <div class="block-fio"><?= $user->second_name.' '.$user->first_name.' '.$user->middle_name; ?></div>
    <div class="block-date-last-visit">
        <?php if($user->checkOnline !== true && $user->checkOnline !== false) : ?>
            Пользователь был в сети <?= $user->checkOnline;?>
        <?php endif; ?>
    </div>
</div>
