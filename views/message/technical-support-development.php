<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\User;
use app\models\MessageDevelopment;

$this->title = 'Сообщения';
$this->registerCssFile('@web/css/admin-message-view.css');
?>

<div class="message-technical-support">

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
                'action' => Url::to(['/admin/message/get-development-conversation-query', 'id' => $development->id]),
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

                <!--Блок для бесед со всеми пользователями-->
                <div class="containerForAllConversations">

                    <?php if ($allConversations) : ?>

                        <?php foreach ($allConversations as $conversation) : ?>

                            <?php if (User::isUserSimple($conversation->user->username)) : ?>

                                <?php if ($conversation->user_id != $user->id) : ?>

                                    <div class="container-user_messages" id="conversation-<?= $conversation->id;?>">

                                        <!--Проверка существования аватарки-->
                                        <?php if ($conversation->user->avatar_image) : ?>
                                            <?= Html::img('/web/upload/user-'.$conversation->user->id.'/avatar/'.$conversation->user->avatar_image, ['class' => 'user_picture']); ?>
                                        <?php else : ?>
                                            <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default']); ?>
                                        <?php endif; ?>

                                        <!--Кол-во непрочитанных сообщений от пользователя-->
                                        <?php if ($conversation->user->countUnreadMessagesDevelopmentFromUser) : ?>
                                            <div class="countUnreadMessagesSender active"><?= $conversation->user->countUnreadMessagesDevelopmentFromUser; ?></div>
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

                                <?php else : ?>

                                    <div class="container-user_messages active-message" id="conversation-<?= $conversation->id;?>">

                                        <!--Проверка существования аватарки-->
                                        <?php if ($conversation->user->avatar_image) : ?>
                                            <?= Html::img('/web/upload/user-'.$conversation->user->id.'/avatar/'.$conversation->user->avatar_image, ['class' => 'user_picture']); ?>
                                        <?php else : ?>
                                            <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default']); ?>
                                        <?php endif; ?>

                                        <!--Кол-во непрочитанных сообщений от пользователя-->
                                        <?php if ($conversation->user->countUnreadMessagesDevelopmentFromUser) : ?>
                                            <div class="countUnreadMessagesSender active"><?= $conversation->user->countUnreadMessagesDevelopmentFromUser; ?></div>
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

                                <?php endif; ?>

                            <?php elseif (User::isUserAdmin($conversation->user->username) || User::isUserMainAdmin($conversation->user->username)) : ?>

                                <?php if ($conversation->user_id != $user->id) : ?>

                                    <div class="container-user_messages" id="adminConversation-<?= $conversation->id;?>">

                                        <!--Проверка существования аватарки-->
                                        <?php if ($conversation->user->avatar_image) : ?>
                                            <?= Html::img('/web/upload/user-'.$conversation->user->id.'/avatar/'.$conversation->user->avatar_image, ['class' => 'user_picture']); ?>
                                        <?php else : ?>
                                            <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default']); ?>
                                        <?php endif; ?>

                                        <!--Кол-во непрочитанных сообщений от пользователя-->
                                        <?php if ($conversation->user->countUnreadMessagesDevelopmentFromUser) : ?>
                                            <div class="countUnreadMessagesSender active"><?= $conversation->user->countUnreadMessagesDevelopmentFromUser; ?></div>
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

                                <?php else : ?>

                                    <div class="container-user_messages active-message" id="adminConversation-<?= $conversation->id;?>">

                                        <!--Проверка существования аватарки-->
                                        <?php if ($conversation->user->avatar_image) : ?>
                                            <?= Html::img('/web/upload/user-'.$conversation->user->id.'/avatar/'.$conversation->user->avatar_image, ['class' => 'user_picture']); ?>
                                        <?php else : ?>
                                            <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default']); ?>
                                        <?php endif; ?>

                                        <!--Кол-во непрочитанных сообщений от пользователя-->
                                        <?php if ($conversation->user->countUnreadMessagesDevelopmentFromUser) : ?>
                                            <div class="countUnreadMessagesSender active"><?= $conversation->user->countUnreadMessagesDevelopmentFromUser; ?></div>
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

                                <?php endif; ?>

                            <?php endif; ?>

                        <?php endforeach; ?>

                    <?php else : ?>

                        <div class="text-center block_not_conversations">Нет доступных пользователей</div>

                    <?php endif; ?>

                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-8">

            <div class="button_open_close_list_users" style="">Открыть список пользователей</div>

            <div class="chat">

                <?php if ($messages) : ?>

                    <div class="data-chat" id="data-chat">

                        <?php if ($countMessages > $pagesMessages->pageSize) : ?>

                            <div class="pagination-messages">
                                <?= \yii\widgets\LinkPager::widget([
                                    'pagination' => $pagesMessages,
                                    'activePageCssClass' => 'pagination_active_page',
                                    'options' => ['class' => 'messages-pagination-list pagination'],
                                    'maxButtonCount' => 1,
                                ]); ?>
                            </div>

                            <div class="text-center block_for_link_next_page_masseges">
                                <?= Html::a('Посмотреть предыдущие сообщения', ['#'], ['class' => 'button_next_page_masseges'])?>
                            </div>

                        <?php endif; ?>

                        <?php $totalDateMessages = array(); // Массив общих дат сообщений ?>

                        <?php foreach ($messages as $i => $message) : ?>

                            <?php
                            // Вывод общих дат для сообщений
                            if (!in_array($message->dayAndDateRus, $totalDateMessages)) {
                                array_push($totalDateMessages, $message->dayAndDateRus);
                                echo '<div class="dayAndDayMessage">'.$message->dayAndDateRus.'</div>';
                            }
                            ?>

                            <?php if ($message->sender_id != $user->id) : ?>

                                <?php if ($message->status == MessageDevelopment::NO_READ_MESSAGE) : ?>

                                    <div class="message addressee-user unreadmessage" id="message_id-<?= $message->id;?>">

                                        <?php if ($development->avatar_image) : ?>
                                            <?= Html::img('/web/upload/user-'.$development->id.'/avatar/'.$development->avatar_image, ['class' => 'user_picture_message']); ?>
                                        <?php else : ?>
                                            <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default_message']); ?>
                                        <?php endif; ?>

                                        <div class="sender_data">
                                            <div class="sender_info">
                                                <div>Техническая поддержка</div>
                                                <div>
                                                    <?= Html::img('/images/icons/icon_double_check.png', ['class' => 'icon_read_message']); ?>
                                                    <?= date('H:i', $message['created_at']); ?>
                                                </div>
                                            </div>

                                            <div class="message-description">

                                                <?php if ($message->description) : ?>
                                                    <?= $message->description; ?>
                                                <?php endif; ?>

                                                <?php if ($message->files) : ?>
                                                    <div class="message-description-files">
                                                        <?php foreach ($message->files as $file) : ?>
                                                            <div>
                                                                <?= Html::a($file->file_name, ['/message/download', 'category' => $file->category, 'id' => $file->id], ['target' => '_blank', 'title' => $file->file_name]);?>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>

                                            </div>
                                        </div>

                                    </div>

                                <?php else : ?>

                                    <div class="message addressee-user" id="message_id-<?= $message->id;?>">

                                        <?php if ($development->avatar_image) : ?>
                                            <?= Html::img('/web/upload/user-'.$development->id.'/avatar/'.$development->avatar_image, ['class' => 'user_picture_message']); ?>
                                        <?php else : ?>
                                            <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default_message']); ?>
                                        <?php endif; ?>

                                        <div class="sender_data">
                                            <div class="sender_info">
                                                <div>Техническая поддержка</div>
                                                <div>
                                                    <?= Html::img('/images/icons/icon_double_check.png', ['class' => 'icon_read_message']); ?>
                                                    <?= date('H:i', $message['created_at']); ?>
                                                </div>
                                            </div>

                                            <div class="message-description">

                                                <?php if ($message->description) : ?>
                                                    <?= $message->description; ?>
                                                <?php endif; ?>

                                                <?php if ($message->files) : ?>
                                                    <div class="message-description-files">
                                                        <?php foreach ($message->files as $file) : ?>
                                                            <div>
                                                                <?= Html::a($file->file_name, ['/message/download', 'category' => $file->category, 'id' => $file->id], ['target' => '_blank', 'title' => $file->file_name]);?>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>

                                            </div>
                                        </div>

                                    </div>

                                <?php endif; ?>

                            <?php else : ?>

                                <?php if ($message->status == MessageDevelopment::NO_READ_MESSAGE) : ?>

                                    <div class="message addressee-development unreadmessage" id="message_id-<?= $message->id;?>">

                                        <?php if ($user->avatar_image) : ?>
                                            <?= Html::img('/web/upload/user-'.$user->id.'/avatar/'.$user->avatar_image, ['class' => 'user_picture_message']); ?>
                                        <?php else : ?>
                                            <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default_message']); ?>
                                        <?php endif; ?>

                                        <div class="sender_data">
                                            <div class="sender_info">
                                                <div class="interlocutor"><?= $user->second_name . ' ' . $user->first_name . ' ' . $user->middle_name; ?></div>
                                                <div>
                                                    <?= Html::img('/images/icons/icon_double_check.png', ['class' => 'icon_read_message']); ?>
                                                    <?= date('H:i', $message['created_at']); ?>
                                                </div>
                                            </div>

                                            <div class="message-description">

                                                <?php if ($message->description) : ?>
                                                    <?= $message->description; ?>
                                                <?php endif; ?>

                                                <?php if ($message->files) : ?>
                                                    <div class="message-description-files">
                                                        <?php foreach ($message->files as $file) : ?>
                                                            <div>
                                                                <?= Html::a($file->file_name, ['/message/download', 'category' => $file->category, 'id' => $file->id], ['target' => '_blank', 'title' => $file->file_name]);?>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>

                                            </div>
                                        </div>

                                    </div>

                                <?php else : ?>

                                    <div class="message addressee-development" id="message_id-<?= $message->id;?>">

                                        <?php if ($user->avatar_image) : ?>
                                            <?= Html::img('/web/upload/user-'.$user->id.'/avatar/'.$user->avatar_image, ['class' => 'user_picture_message']); ?>
                                        <?php else : ?>
                                            <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default_message']); ?>
                                        <?php endif; ?>

                                        <div class="sender_data">
                                            <div class="sender_info">
                                                <div class="interlocutor"><?= $user->second_name . ' ' . $user->first_name . ' ' . $user->middle_name; ?></div>
                                                <div>
                                                    <?= Html::img('/images/icons/icon_double_check.png', ['class' => 'icon_read_message']); ?>
                                                    <?= date('H:i', $message['created_at']); ?>
                                                </div>
                                            </div>

                                            <div class="message-description">

                                                <?php if ($message->description) : ?>
                                                    <?= $message->description; ?>
                                                <?php endif; ?>

                                                <?php if ($message->files) : ?>
                                                    <div class="message-description-files">
                                                        <?php foreach ($message->files as $file) : ?>
                                                            <div>
                                                                <?= Html::a($file->file_name, ['/message/download', 'category' => $file->category, 'id' => $file->id], ['target' => '_blank', 'title' => $file->file_name]);?>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>

                                            </div>
                                        </div>

                                    </div>

                                <?php endif; ?>

                            <?php endif; ?>

                        <?php endforeach; ?>

                    </div>

                    <div class="create-message">

                        <?php
                        $form = ActiveForm::begin([
                            'id' => 'create-message-development',
                            'action' => Url::to(['/message/send-message-development', 'id' => \Yii::$app->request->get('id')]),
                            'options' => ['enctype' => 'multipart/form-data', 'class' => 'g-py-15'],
                            'errorCssClass' => 'u-has-error-v1',
                            'successCssClass' => 'u-has-success-v1-1',
                        ]);
                        ?>

                        <div class="form-send-email">

                            <?= $form->field($formMessage, 'description')->label(false)->textarea([
                                'id' => 'input_send_message',
                                'rows' => 1,
                                'maxlength' => true,
                                'required' => true,
                                'class' => 'style_form_field_respond form-control',
                                'placeholder' => 'Напишите ваше сообщение',
                                'autocomplete' => 'off'
                            ]) ?>

                            <?= $form->field($formMessage, 'message_files[]', ['template' => "{label}\n{input}"])->fileInput(['id' => 'input_message_files', 'multiple' => true, 'accept' => 'text/plain, application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, image/x-png, image/jpeg'])->label(false) ?>

                            <?= Html::submitButton('Отправить', ['id' =>  'submit_send_message']); ?>

                            <?= Html::img('/images/icons/send_email_button.png', ['class' => 'send_message_button', 'title' => 'Отправить сообщение']); ?>

                            <?= Html::img('/images/icons/button_attach_files.png', ['class' => 'attach_files_button', 'title' => 'Прикрепить файлы']); ?>

                        </div>

                        <?php ActiveForm::end(); ?>

                        <!--Сюда загружаем названия загруженных файлов или сообшение о превышении кол-ва файлов-->
                        <div class="block_attach_files"></div>
                    </div>


                <?php else : // Если отсутствуют сообщения ?>

                    <div class="data-chat" id="data-chat">
                        <div class="block_not_exist_message">
                            У Вас нет пока общих сообщений с данным пользователем...
                        </div>
                    </div>

                    <div class="create-message">

                        <?php
                        $form = ActiveForm::begin([
                            'id' => 'create-message-development',
                            'action' => Url::to(['/message/send-message-development', 'id' => \Yii::$app->request->get('id')]),
                            'options' => ['enctype' => 'multipart/form-data', 'class' => 'g-py-15'],
                            'errorCssClass' => 'u-has-error-v1',
                            'successCssClass' => 'u-has-success-v1-1',
                        ]);
                        ?>

                        <div class="form-send-email">

                            <?= $form->field($formMessage, 'description')->label(false)->textarea([
                                'id' => 'input_send_message',
                                'rows' => 1,
                                'maxlength' => true,
                                'required' => true,
                                'class' => 'style_form_field_respond form-control',
                                'placeholder' => 'Напишите ваше сообщение',
                                'autocomplete' => 'off'
                            ]) ?>

                            <?= $form->field($formMessage, 'message_files[]', ['template' => "{label}\n{input}"])->fileInput(['id' => 'input_message_files', 'multiple' => true, 'accept' => 'text/plain, application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, image/x-png, image/jpeg'])->label(false) ?>

                            <?= Html::submitButton('Отправить', ['id' =>  'submit_send_message']); ?>

                            <?= Html::img('/images/icons/send_email_button.png', ['class' => 'send_message_button', 'title' => 'Отправить сообщение']); ?>

                            <?= Html::img('/images/icons/button_attach_files.png', ['class' => 'attach_files_button', 'title' => 'Прикрепить файлы']); ?>

                        </div>

                        <?php ActiveForm::end(); ?>

                        <!--Сюда загружаем названия загруженных файлов или сообшение о превышении кол-ва файлов-->
                        <div class="block_attach_files"></div>
                    </div>

                <?php endif; ?>

            </div>
        </div>

    </div>

</div>

<!--Подключение скриптов-->
<?php $this->registerJsFile('@web/js/message_technical_suport_development.js'); ?>
<?php $this->registerJsFile('@web/js/form_message_development.js'); ?>
