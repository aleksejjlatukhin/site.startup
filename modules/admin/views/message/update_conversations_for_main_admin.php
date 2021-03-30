<?php

use  yii\helpers\Html;

?>

<!--Блок беседы с техподдержкой-->
<div class="containerForTechnicalSupportConversation">

    <div class="container-user_messages" id="conversationTechnicalSupport-<?= $conversation_development->id;?>">

        <!--Проверка существования аватарки-->
        <?php if ($conversation_development->development->avatar_image) : ?>
            <?= Html::img('/web/upload/user-'.$conversation_development->development->id.'/avatar/'.$conversation_development->development->avatar_image, ['class' => 'user_picture']); ?>
        <?php else : ?>
            <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default']); ?>
        <?php endif; ?>

        <!--Кол-во непрочитанных сообщений от техподдержки-->
        <?php if ($main_admin->countUnreadMessagesFromDev) : ?>
            <div class="countUnreadMessagesSender active"><?= $main_admin->countUnreadMessagesFromDev; ?></div>
        <?php else : ?>
            <div class="countUnreadMessagesSender"></div>
        <?php endif; ?>

        <!--Проверка онлайн статуса-->
        <?php if ($main_admin->development->checkOnline === true) : ?>
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

<!--Блок для бесед с администраторами-->
<div class="containerForAllConversations">

    <div class="title_block_conversation">
        <div class="title">Администраторы</div>
    </div>

    <?php if ($allConversations) : ?>

        <?php foreach ($allConversations as $conversation) : ?>

            <div class="container-user_messages" id="adminConversation-<?= $conversation->id;?>">

                <!--Проверка существования аватарки-->
                <?php if ($conversation->admin->avatar_image) : ?>
                    <?= Html::img('/web/upload/user-'.$conversation->admin->id.'/avatar/'.$conversation->admin->avatar_image, ['class' => 'user_picture']); ?>
                <?php else : ?>
                    <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default']); ?>
                <?php endif; ?>

                <!--Кол-во непрочитанных сообщений от администратора-->
                <?php if ($conversation->admin->countUnreadMessagesMainAdminFromAdmin) : ?>
                    <div class="countUnreadMessagesSender active"><?= $conversation->admin->countUnreadMessagesMainAdminFromAdmin; ?></div>
                <?php else : ?>
                    <div class="countUnreadMessagesSender"></div>
                <?php endif; ?>

                <!--Проверка онлайн статуса-->
                <?php if ($conversation->admin->checkOnline === true) : ?>
                    <div class="checkStatusOnlineUser active"></div>
                <?php else : ?>
                    <div class="checkStatusOnlineUser"></div>
                <?php endif; ?>

                <div class="container_user_messages_text_content">

                    <div class="row block_top">

                        <div class="col-xs-8"><?= $conversation->admin->second_name.' '.$conversation->admin->first_name.' '.$conversation->admin->middle_name; ?></div>

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

        <div class="text-center block_not_conversations">Нет администраторов</div>

    <?php endif; ?>

</div>
