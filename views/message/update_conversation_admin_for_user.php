<?php

use yii\helpers\Html;

?>


<!--Проверка существования аватарки-->
<?php if ($admin->avatar_image) : ?>
    <?= Html::img('/web/upload/user-'.$admin->id.'/avatar/'.$admin->avatar_image, ['class' => 'user_picture']); ?>
<?php else : ?>
    <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default']); ?>
<?php endif; ?>

<!--Кол-во непрочитанных сообщений от Админа-->
<?php if ($user->countUnreadMessagesFromAdmin) : ?>
    <div class="countUnreadMessagesSender active"><?= $user->countUnreadMessagesFromAdmin; ?></div>
<?php else : ?>
    <div class="countUnreadMessagesSender"></div>
<?php endif; ?>

<!--Проверка онлайн статуса-->
<?php if ($admin->checkOnline === true) : ?>
    <div class="checkStatusOnlineUser active"></div>
<?php else : ?>
    <div class="checkStatusOnlineUser"></div>
<?php endif; ?>

<div class="container_user_messages_text_content">

    <div class="row block_top">

        <div class="col-xs-8">Администратор</div>

        <div class="col-xs-4 text-right">
            <?php if ($conversation_admin->lastMessage) : ?>
                <?= date('d.m.y H:i', $conversation_admin->lastMessage->created_at); ?>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($conversation_admin->lastMessage) : ?>
        <div class="block_bottom_exist_message">

            <?php if ($conversation_admin->lastMessage->sender->avatar_image) : ?>
                <?= Html::img('/web/upload/user-'.$conversation_admin->lastMessage->sender->id.'/avatar/'.$conversation_admin->lastMessage->sender->avatar_image, ['class' => 'icon_sender_last_message']); ?>
            <?php else : ?>
                <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'icon_sender_last_message_default']); ?>
            <?php endif; ?>

            <div>
                <?php if ($conversation_admin->lastMessage->description) : ?>
                    <?= $conversation_admin->lastMessage->description; ?>
                <?php else : ?>
                    ...
                <?php endif; ?>
            </div>
        </div>
    <?php else : ?>
        <div class="block_bottom_not_exist_message">Нет сообщений</div>
    <?php endif; ?>

</div>
