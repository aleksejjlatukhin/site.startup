<?php

use yii\helpers\Html;

?>


<!--Проверка существования аватарки-->
<?php if ($development->avatar_image) : ?>
    <?= Html::img('/web/upload/user-'.$development->id.'/avatar/'.$development->avatar_image, ['class' => 'user_picture']); ?>
<?php else : ?>
    <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default']); ?>
<?php endif; ?>

<!--Кол-во непрочитанных сообщений от Техподдержки-->
<?php if ($user->countUnreadMessagesFromDev) : ?>
    <div class="countUnreadMessagesSender active"><?= $user->countUnreadMessagesFromDev; ?></div>
<?php else : ?>
    <div class="countUnreadMessagesSender"></div>
<?php endif; ?>

<!--Проверка онлайн статуса-->
<?php if ($development->checkOnline === true) : ?>
    <div class="checkStatusOnlineUser active"></div>
<?php else : ?>
    <div class="checkStatusOnlineUser"></div>
<?php endif; ?>

<div class="container_user_messages_text_content">

    <div class="row block_top">

        <div class="col-xs-8">Техническая поддержка</div>

        <div class="col-xs-4 text-right">
            <?php if ($conversation_development->lastMessage) : ?>
                <?= date('d.m.y H:i', $conversation_development->lastMessage->created_at); ?>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($conversation_development->lastMessage) : ?>
        <div class="block_bottom_exist_message">

            <?php if ($conversation_development->lastMessage->sender->avatar_image) : ?>
                <?= Html::img('/web/upload/user-'.$conversation_development->lastMessage->sender->id.'/avatar/'.$conversation_development->lastMessage->sender->avatar_image, ['class' => 'icon_sender_last_message']); ?>
            <?php else : ?>
                <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'icon_sender_last_message_default']); ?>
            <?php endif; ?>

            <div>
                <?php if ($conversation_development->lastMessage->description) : ?>
                    <?= $conversation_development->lastMessage->description; ?>
                <?php else : ?>
                    ...
                <?php endif; ?>
            </div>
        </div>
    <?php else : ?>
        <div class="block_bottom_not_exist_message">Нет сообщений</div>
    <?php endif; ?>

</div>
