<?php

use yii\helpers\Html;

?>

<!--Проверка существования аватарки-->
<?php if ($conversationAdminMain->user->avatar_image) : ?>
    <?= Html::img('/web/upload/user-'.$conversationAdminMain->user->id.'/avatar/'.$conversationAdminMain->user->avatar_image, ['class' => 'user_picture']); ?>
<?php else : ?>
    <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default']); ?>
<?php endif; ?>

<!--Кол-во непрочитанных сообщений от главного админа-->
<?php if ($manager->countUnreadMessagesFromMainAdmin) : ?>
    <div class="countUnreadMessagesSender active"><?= $manager->countUnreadMessagesFromMainAdmin; ?></div>
<?php else : ?>
    <div class="countUnreadMessagesSender"></div>
<?php endif; ?>

<!--Проверка онлайн статуса-->
<?php if ($manager->mainAdmin->checkOnline === true) : ?>
    <div class="checkStatusOnlineUser active"></div>
<?php else : ?>
    <div class="checkStatusOnlineUser"></div>
<?php endif; ?>

<div class="container_user_messages_text_content">

    <div class="row block_top">

        <div class="col-xs-8">Главный администратор</div>

        <div class="col-xs-4 text-right">
            <?php if ($conversationAdminMain->lastMessage) : ?>
                <?= date('d.m.y H:i', $conversationAdminMain->lastMessage->created_at); ?>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($conversationAdminMain->lastMessage) : ?>
        <div class="block_bottom_exist_message">

            <?php if ($conversationAdminMain->lastMessage->sender->avatar_image) : ?>
                <?= Html::img('/web/upload/user-'.$conversationAdminMain->lastMessage->sender->id.'/avatar/'.$conversationAdminMain->lastMessage->sender->avatar_image, ['class' => 'icon_sender_last_message']); ?>
            <?php else : ?>
                <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'icon_sender_last_message_default']); ?>
            <?php endif; ?>

            <div>
                <?php if ($conversationAdminMain->lastMessage->description) : ?>
                    <?= $conversationAdminMain->lastMessage->description; ?>
                <?php else : ?>
                    ...
                <?php endif; ?>
            </div>
        </div>
    <?php else : ?>
        <div class="block_bottom_not_exist_message">Нет сообщений</div>
    <?php endif; ?>

</div>
