<?php

use yii\helpers\Html;

?>

<div class="title_block_conversation">
    <div class="title">Проектанты</div>
</div>

<?php if ($userConversations) : ?>

    <?php foreach ($userConversations as $conversation) : ?>

        <div class="container-user_messages" id="conversation-<?= $conversation->id;?>">

            <!--Проверка существования аватарки-->
            <?php if ($conversation->user->avatar_image) : ?>
                <?= Html::img('/web/upload/user-'.$conversation->user->id.'/avatar/'.$conversation->user->avatar_image, ['class' => 'user_picture']); ?>
            <?php else : ?>
                <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default']); ?>
            <?php endif; ?>

            <!--Кол-во непрочитанных сообщений от проектанта-->
            <?php if ($conversation->user->getCountUnreadMessagesUserFromExpert($expert->id)) : ?>
                <div class="countUnreadMessagesSender active"><?= $conversation->user->getCountUnreadMessagesUserFromExpert($expert->id); ?></div>
            <?php else : ?>
                <div class="countUnreadMessagesSender"></div>
            <?php endif; ?>

            <!--Проверка онлайн статуса-->
            <?php if ($conversation->user->checkOnline === true) : ?>
                <div class="checkStatusOnlineUser active"></div>
            <?php else : ?>
                <div class="checkStatusOnlineUser"></div>
            <?php endif; ?>

            <div class="container_user_messages_text_content">

                <div class="row block_top">

                    <div class="col-xs-8"><?= $conversation->user->second_name.' '.$conversation->user->first_name.' '.$conversation->user->middle_name; ?></div>

                    <div class="col-xs-4 text-right">
                        <?php if ($conversation->lastMessage) : ?>
                            <?= date('d.m.y H:i', $conversation->lastMessage->created_at); ?>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($conversation->lastMessage) : ?>
                    <div class="block_bottom_exist_message">

                        <?php if ($conversation->lastMessage->sender->avatar_image) : ?>
                            <?= Html::img('/web/upload/user-'.$conversation->lastMessage->sender->id.'/avatar/'.$conversation->lastMessage->sender->avatar_image, ['class' => 'icon_sender_last_message']); ?>
                        <?php else : ?>
                            <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'icon_sender_last_message_default']); ?>
                        <?php endif; ?>

                        <div>
                            <?php if ($conversation->lastMessage->description) : ?>
                                <?= $conversation->lastMessage->description; ?>
                            <?php else : ?>
                                ...
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="block_bottom_not_exist_message">Нет сообщений</div>
                <?php endif; ?>

            </div>
        </div>

    <?php endforeach; ?>

<?php else : ?>

    <div class="text-center block_not_conversations">Нет проектантов</div>

<?php endif; ?>
