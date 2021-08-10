<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Сообщения';
$this->registerCssFile('@web/css/admin-message-index.css');
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

    <div class="row message_menu">

        <div class="col-sm-6 col-lg-4 search-block">

            <?php $form = ActiveForm::begin([
                'id' => 'search_user_conversation',
                'action' => Url::to(['/expert/message/get-conversation-query', 'id' => $expert->id]),
                'options' => ['class' => 'g-py-15'],
                'errorCssClass' => 'u-has-error-v1',
                'successCssClass' => 'u-has-success-v1-1',
            ]); ?>

            <?= $form->field($searchForm, 'search', ['template' => '{input}'])
                ->textInput([
                    'id' => 'search_conversation',
                    'placeholder' => 'Поиск',
                    'class' => 'style_form_field_respond',
                    'autocomplete' => 'off'])
                ->label(false);
            ?>

            <?php ActiveForm::end(); ?>

            <!--Беседы полученные в запросе поиска (по умолчанию это все доступные пользователи)-->
            <div class="conversations_query" id="conversations_query">
                <!--Сюда добавляем результат поиска-->
            </div>

        </div>

        <div class="col-sm-6 col-lg-8">

        </div>

    </div>

    <div class="row all_content_messages">

        <div class="col-sm-6 col-lg-4 conversation-list-menu">

            <div id="conversation-list-menu">

                <!--Блок беседы с главным админом и техподдержкой-->
                <div class="containerAdminMainConversation">

                    <div class="container-user_messages" id="adminMainConversation-<?= $conversationAdminMain->id;?>">

                        <!--Проверка существования аватарки-->
                        <?php if ($conversationAdminMain->user->avatar_image) : ?>
                            <?= Html::img('/web/upload/user-'.$conversationAdminMain->user->id.'/avatar/'.$conversationAdminMain->user->avatar_image, ['class' => 'user_picture']); ?>
                        <?php else : ?>
                            <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default']); ?>
                        <?php endif; ?>

                        <!--Кол-во непрочитанных сообщений от главного админа-->
                        <?php if ($expert->countUnreadMessagesExpertFromMainAdmin) : ?>
                            <div class="countUnreadMessagesSender active"><?= $expert->countUnreadMessagesExpertFromMainAdmin; ?></div>
                        <?php else : ?>
                            <div class="countUnreadMessagesSender"></div>
                        <?php endif; ?>

                        <!--Проверка онлайн статуса-->
                        <?php if ($expert->mainAdmin->checkOnline === true) : ?>
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
                        <?php if ($expert->countUnreadMessagesFromDev) : ?>
                            <div class="countUnreadMessagesSender active"><?= $expert->countUnreadMessagesFromDev; ?></div>
                        <?php else : ?>
                            <div class="countUnreadMessagesSender"></div>
                        <?php endif; ?>

                        <!--Проверка онлайн статуса-->
                        <?php if ($expert->development->checkOnline === true) : ?>
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

                <!--Блок для бесед с трекерами-->
                <div class="containerForAllAdminsConversations">

                    <div class="title_block_conversation">
                        <div class="title">Трекеры</div>
                    </div>

                    <?php if ($adminConversations) : ?>

                        <?php foreach ($adminConversations as $conversation) : ?>

                            <div class="container-user_messages" id="adminConversation-<?= $conversation->id;?>">

                                <!--Проверка существования аватарки-->
                                <?php if ($conversation->user->avatar_image) : ?>
                                    <?= Html::img('/web/upload/user-'.$conversation->user->id.'/avatar/'.$conversation->user->avatar_image, ['class' => 'user_picture']); ?>
                                <?php else : ?>
                                    <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default']); ?>
                                <?php endif; ?>

                                <!--Кол-во непрочитанных сообщений от трекера-->
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

                        <div class="text-center block_not_conversations">Нет трекеров</div>

                    <?php endif; ?>

                </div>


                <!--Блок для бесед с проектантами-->
                <div class="containerForAllUsersConversations">

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
<?php $this->registerJsFile('@web/js/expert_message_index.js'); ?>
