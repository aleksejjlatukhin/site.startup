<?php

use yii\helpers\Html;

?>

<!--Блок беседы с главным админом и техподдержкой-->
<div class="containerAdminMainConversation">

    <div class="container-user_messages" id="adminMainConversation-<?= $conversationAdminMain->id;?>">

        <!--Проверка существования аватарки-->
        <?php if ($conversationAdminMain->mainAdmin->avatar_image) : ?>
            <?= Html::img('/web/upload/user-'.$conversationAdminMain->mainAdmin->id.'/avatar/'.$conversationAdminMain->mainAdmin->avatar_image, ['class' => 'user_picture']); ?>
        <?php else : ?>
            <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default']); ?>
        <?php endif; ?>

        <!--Кол-во непрочитанных сообщений от главного админа-->
        <?php if ($admin->countUnreadMessagesFromMainAdmin) : ?>
            <div class="countUnreadMessagesSender active"><?= $admin->countUnreadMessagesFromMainAdmin; ?></div>
        <?php else : ?>
            <div class="countUnreadMessagesSender"></div>
        <?php endif; ?>

        <!--Проверка онлайн статуса-->
        <?php if ($admin->mainAdmin->checkOnline === true) : ?>
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
    </div>

    <div class="container-user_messages" id="conversationTechnicalSupport-<?= $conversation_development->id;?>">

        <!--Проверка существования аватарки-->
        <?php if ($conversation_development->development->avatar_image) : ?>
            <?= Html::img('/web/upload/user-'.$conversation_development->development->id.'/avatar/'.$conversation_development->development->avatar_image, ['class' => 'user_picture']); ?>
        <?php else : ?>
            <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default']); ?>
        <?php endif; ?>

        <!--Кол-во непрочитанных сообщений от техподдержки-->
        <?php if ($admin->countUnreadMessagesFromDev) : ?>
            <div class="countUnreadMessagesSender active"><?= $admin->countUnreadMessagesFromDev; ?></div>
        <?php else : ?>
            <div class="countUnreadMessagesSender"></div>
        <?php endif; ?>

        <!--Проверка онлайн статуса-->
        <?php if ($admin->development->checkOnline === true) : ?>
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
    </div>
</div>

<!--Блок для загрузки бесед с пользователями-->
<div class="containerForAllConversations">

    <div class="title_block_conversation">
        <div class="title">Пользователи сервиса</div>
    </div>

    <?php if ($allConversations) : ?>

        <?php foreach ($allConversations as $conversation) : ?>

            <div class="container-user_messages" id="conversation-<?= $conversation->id;?>">

                <!--Проверка существования аватарки-->
                <?php if ($conversation->user->avatar_image) : ?>
                    <?= Html::img('/web/upload/user-'.$conversation->user->id.'/avatar/'.$conversation->user->avatar_image, ['class' => 'user_picture']); ?>
                <?php else : ?>
                    <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default']); ?>
                <?php endif; ?>

                <!--Кол-во непрочитанных сообщений от пользователя-->
                <?php if ($conversation->user->countUnreadMessagesFromUser) : ?>
                    <div class="countUnreadMessagesSender active"><?= $conversation->user->countUnreadMessagesFromUser; ?></div>
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

        <div class="text-center block_not_conversations">Нет сообщений</div>

    <?php endif; ?>

</div>
