<?php

use yii\helpers\Html;

$this->title = 'Сообщения';
$this->registerCssFile('@web/css/message-index.css');
?>

<div class="message-index">

    <!--Preloader begin-->
    <div id="preloader">
        <div id="cont">
            <div class="round"></div>
            <div class="round"></div>
            <div class="round"></div>
            <div class="round"></div>
        </div>
        <div id="loading">Loading</div>
    </div>
    <!--Preloader end-->

    <div class="row profile_menu">

        <div class="link_open_and_close_menu_profile">Открыть меню профиля</div>

        <?= Html::a('Данные пользователя', ['/profile/index', 'id' => $user->id], [
            'class' => 'link_in_the_header',
        ]) ?>

        <?= Html::a('Сводные таблицы', ['/profile/result', 'id' => $user->id], [
            'class' => 'link_in_the_header',
        ]) ?>

        <?= Html::a('Дорожные карты', ['/profile/roadmap', 'id' => $user->id], [
            'class' => 'link_in_the_header',
        ]) ?>

        <?= Html::a('Протоколы', ['/profile/report', 'id' => $user->id], [
            'class' => 'link_in_the_header',
        ]) ?>

        <?= Html::a('Презентации', ['/profile/presentation', 'id' => $user->id], [
            'class' => 'link_in_the_header',
        ]) ?>

    </div>
    
    <div class="row">
        <div class="col-sm-6 col-lg-4 hide_block_menu_profile">

            <?= Html::a('Данные пользователя', ['/profile/index', 'id' => $user->id], [
                'class' => 'link_in_the_header',
            ]) ?>

            <?= Html::a('Сводные таблицы', ['/profile/result', 'id' => $user->id], [
                'class' => 'link_in_the_header',
            ]) ?>

            <?= Html::a('Дорожные карты', ['/profile/roadmap', 'id' => $user->id], [
                'class' => 'link_in_the_header',
            ]) ?>

            <?= Html::a('Протоколы', ['/profile/report', 'id' => $user->id], [
                'class' => 'link_in_the_header',
            ]) ?>

            <?= Html::a('Презентации', ['/profile/presentation', 'id' => $user->id], [
                'class' => 'link_in_the_header',
            ]) ?>

        </div>
    </div>

    <div class="row all_content_messages">

        <div class="col-sm-6 col-lg-4 conversation-list-menu">

            <div id="conversation-list-menu">

                <!--Блок беседы с трекером и техподдержкой-->
                <div class="containerAdminConversation">

                    <div class="container-user_messages" id="adminConversation-<?= $conversation_admin->id;?>">

                        <!--Проверка существования аватарки-->
                        <?php if ($admin->avatar_image) : ?>
                            <?= Html::img('/web/upload/user-'.$admin->id.'/avatar/'.$admin->avatar_image, ['class' => 'user_picture']); ?>
                        <?php else : ?>
                            <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default']); ?>
                        <?php endif; ?>

                        <!--Кол-во непрочитанных сообщений от Трекера-->
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

                                <div class="col-xs-8">Трекер</div>

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
                    </div>

                    <div class="container-user_messages" id="conversationTechnicalSupport-<?= $conversation_development->id;?>">

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
                    </div>
                </div>

                <!--Блок бесед с экспертами-->
                <div class="containerExpertConversations">

                    <div class="title_block_conversation">
                        <div class="title">Эксперты</div>
                    </div>

                    <?php if ($conversationsExpert) : ?>

                        <?php foreach ($conversationsExpert as $conversation) : ?>

                            <div class="container-user_messages" id="expertConversation-<?= $conversation->id;?>">

                                <!--Проверка существования аватарки-->
                                <?php if ($conversation->expert->avatar_image) : ?>
                                    <?= Html::img('/web/upload/user-'.$conversation->expert->id.'/avatar/'.$conversation->expert->avatar_image, ['class' => 'user_picture']); ?>
                                <?php else : ?>
                                    <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default']); ?>
                                <?php endif; ?>

                                <!--Кол-во непрочитанных сообщений от эксперта-->
                                <?php if ($user->getCountUnreadMessagesExpertFromUser($conversation->expert->id)) : ?>
                                    <div class="countUnreadMessagesSender active"><?= $user->getCountUnreadMessagesExpertFromUser($conversation->expert->id); ?></div>
                                <?php else : ?>
                                    <div class="countUnreadMessagesSender"></div>
                                <?php endif; ?>

                                <!--Проверка онлайн статуса-->
                                <?php if ($conversation->expert->checkOnline === true) : ?>
                                    <div class="checkStatusOnlineUser active"></div>
                                <?php else : ?>
                                    <div class="checkStatusOnlineUser"></div>
                                <?php endif; ?>

                                <div class="container_user_messages_text_content">

                                    <div class="row block_top">

                                        <div class="col-xs-8"><?= $conversation->expert->second_name.' '.$conversation->expert->first_name.' '.$conversation->expert->middle_name; ?></div>

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

                        <div class="text-center block_not_conversations">Нет экспертов</div>

                    <?php endif; ?>

                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-8">
            <div class="message_index_block_right_info">
                Выберите пользователя (перейдите к беседе с пользователем)
            </div>
        </div>
        
    </div>

</div>

<!--Подключение скриптов-->
<?php $this->registerJsFile('@web/js/message_index.js'); ?>
