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

                            <?php elseif (User::isUserAdmin($conversation->user->username)) : ?>

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

                            <?php elseif (User::isUserMainAdmin($conversation->user->username)) : ?>

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

                            <?php elseif (User::isUserExpert($conversation->user->username)) : ?>

                                <div class="container-user_messages" id="expertConversation-<?= $conversation->id;?>">

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

                            <?php if ($message->sender_id != $main_admin->id) : ?>

                                <?php if ($message->status == MessageDevelopment::NO_READ_MESSAGE) : ?>

                                    <div class="message addressee-main_admin unreadmessage" id="message_id-<?= $message->id;?>">

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
                                                                <?= Html::a($file->file_name, ['/admin/message/download', 'category' => $file->category, 'id' => $file->id], ['target' => '_blank', 'title' => $file->file_name]);?>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>

                                            </div>
                                        </div>

                                    </div>

                                <?php else : ?>

                                    <div class="message addressee-main_admin" id="message_id-<?= $message->id;?>">

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
                                                                <?= Html::a($file->file_name, ['/admin/message/download', 'category' => $file->category, 'id' => $file->id], ['target' => '_blank', 'title' => $file->file_name]);?>
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

                                        <?php if ($main_admin->avatar_image) : ?>
                                            <?= Html::img('/web/upload/user-'.$main_admin->id.'/avatar/'.$main_admin->avatar_image, ['class' => 'user_picture_message']); ?>
                                        <?php else : ?>
                                            <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default_message']); ?>
                                        <?php endif; ?>

                                        <div class="sender_data">
                                            <div class="sender_info">
                                                <div class="interlocutor"><?= $main_admin->second_name . ' ' . $main_admin->first_name . ' ' . $main_admin->middle_name; ?></div>
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
                                                                <?= Html::a($file->file_name, ['/admin/message/download', 'category' => $file->category, 'id' => $file->id], ['target' => '_blank', 'title' => $file->file_name]);?>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>

                                            </div>
                                        </div>

                                    </div>

                                <?php else : ?>

                                    <div class="message addressee-development" id="message_id-<?= $message->id;?>">

                                        <?php if ($main_admin->avatar_image) : ?>
                                            <?= Html::img('/web/upload/user-'.$main_admin->id.'/avatar/'.$main_admin->avatar_image, ['class' => 'user_picture_message']); ?>
                                        <?php else : ?>
                                            <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default_message']); ?>
                                        <?php endif; ?>

                                        <div class="sender_data">
                                            <div class="sender_info">
                                                <div class="interlocutor"><?= $main_admin->second_name . ' ' . $main_admin->first_name . ' ' . $main_admin->middle_name; ?></div>
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
                                                                <?= Html::a($file->file_name, ['/admin/message/download', 'category' => $file->category, 'id' => $file->id], ['target' => '_blank', 'title' => $file->file_name]);?>
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
                            'action' => Url::to(['/admin/message/send-message-development', 'id' => \Yii::$app->request->get('id')]),
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
                            'action' => Url::to(['/admin/message/send-message-development', 'id' => \Yii::$app->request->get('id')]),
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
<script>
    //Установка Simple ScrollBar для блока выбора беседы
    const simpleBarConversations = new SimpleBar(document.getElementById('conversation-list-menu'));
    //Установка Simple ScrollBar для блока сообщений
    const simpleBarDataChatDevelopment = new SimpleBar(document.getElementById('data-chat'));
    // Получаем id беседы техподдержки с главным админом
    var conversation_id = window.location.search.split('=')[1];


    var body = $('body');
    var id_page = window.location.search.split('=')[1];


    // Прокрутка во время работы прелоадера
    window.addEventListener('DOMContentLoaded', function() {
        // Прокрутка до блока активной беседы
        var linkAllConversation = $('.containerForAllConversations').find('#adminConversation-'+id_page);
        simpleBarConversations.getScrollElement().scrollTop = $(linkAllConversation).offset().top - 211;
        // Прокрутка блока сообщений
        var unreadmessage = $(body).find('.addressee-development.unreadmessage:first');
        if ($(unreadmessage).length) // Первое непрочитанное сообщение для пользователя
            simpleBarDataChatDevelopment.getScrollElement().scrollTop = $(unreadmessage).offset().top - $(unreadmessage).height() - $('.data-chat').height();
        else
            simpleBarDataChatDevelopment.getScrollElement().scrollTop = simpleBarDataChatDevelopment.getScrollElement().scrollHeight;
    });


    // Установка прелоадера
    $(function () {
        var step = '',
            block_loading = $('#loading'),
            text = $(block_loading).text();

        function changeStep () {
            $(block_loading).text(text + step);
            if (step === '...') step = '';
            else step += '.';
        }

        var interval = setInterval(changeStep, 500);

        $(document).ready(function () {
            setTimeout(function () {
                clearInterval(interval);
                $('#preloader').fadeOut('500','swing');
            }, 3000);
        });
    });


    //Открытие и закрытие списка поиска пользователей
    $(body).on('click', '#search_conversation', function(e){

        if ($(this).css('border-bottom-width') === '1px') {
            $(this).css({
                'border-bottom-width': '0',
                'border-radius': '12px 12px 0 0',
                'box-shadow': 'inset rgba(0,0,0,.6) 0 1px 3px',
            });
        } else {
            $(this).css({
                'border-bottom-width': '1px',
                'border-radius': '12px',
                'box-shadow': 'inset rgba(0,0,0,.6) 0 -1px 3px',
            });
        }

        // Скрываем и показываем блок с результатом поиска
        $('.conversations_query').toggle('display');
        // Если поле поиска ещё пусто, то выводим всех пользователей поиска
        if ($(this).val() === '') {
            $(this).val(' '); $('form#search_user_conversation').trigger('input'); $(this).val('');
        }

        e.preventDefault();
        return false;
    });


    // Отслеживаем клик вне поля поиска
    $(document).mouseup(function (e){ // событие клика по веб-документу

        var search = $('#search_conversation'); // поле поиска
        var conversations_query = $('.conversations_query'); // блок вывода поиска

        //если клик был не полю поиска и не по его дочерним элементам и не по блоку результата поиска
        if (!search.is(e.target) && search.has(e.target).length === 0 && !conversations_query.is(e.target) && conversations_query.has(e.target).length === 0) {

            $(search).css({'border-width': '1px', 'border-radius': '12px', 'box-shadow': 'inset rgba(0,0,0,.6) 0 -1px 3px'}); // возвращаем стили для поля ввода
            if ($(conversations_query).css('display') === 'block') $(conversations_query).toggle('display'); // скрываем блок вывода поиска
        }
    });


    // Отслеживаем ввод в строку поиск и выводим найденные беседы
    $(body).on('input', 'form#search_user_conversation', function(e) {

        var conversations_query = $('.conversations_query'); // блок вывода поиска
        var input = $('input#search_conversation');
        if ($(conversations_query).css('display') === 'none') {
            $(conversations_query).toggle('display'); // показываем блок вывода поиска
            $(input).css({
                'border-bottom-width': '0',
                'border-radius': '12px 12px 0 0',
                'box-shadow': 'inset rgba(0,0,0,.6) 0 1px 3px',
            });
        }

        var data = $(this).serialize();
        var url = $(this).attr('action');

        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            cache: false,
            success: function(response){

                // Выводим результаты поиска
                $('.conversations_query').html(response.renderAjax);
            }
        });

        e.preventDefault();
        return false;
    });


    //Запрет на отправку формы поиска
    $(body).keydown('form#search_user_conversation', function(e) {
        if (e.keyCode === 13) e.preventDefault();
    });


    // Переход на страницу диалога через выбор в поиске
    $(body).on('click', '.conversation-link', function () {
        var id = $(this).attr('id').split('-')[1];
        if ($(this).attr('id').split('-')[0] === 'adminConversation') {
            window.location.href = '/admin/message/technical-support?id='+id;
        }
        else if (($(this).attr('id').split('-')[0] === 'conversation')) {
            window.location.href = '/message/technical-support?id='+id;
        }
        else if (($(this).attr('id').split('-')[0] === 'expertConversation')) {
            window.location.href = '/expert/message/technical-support?id='+id;
        }
    });


    // Переход на страницу диалога через выбор в списке
    $(body).on('click', '.container-user_messages', function () {
        var id = $(this).attr('id').split('-')[1];
        if ($(this).attr('id').split('-')[0] === 'adminConversation') {
            window.location.href = '/admin/message/technical-support?id='+id;
        }
        else if (($(this).attr('id').split('-')[0] === 'conversation')) {
            window.location.href = '/message/technical-support?id='+id;
        }
        else if (($(this).attr('id').split('-')[0] === 'expertConversation')) {
            window.location.href = '/expert/message/technical-support?id='+id;
        }
    });


    // Открытие и закрытие списка пользователей на малых экранах
    $(body).on('click', '.button_open_close_list_users', function () {

        var conversation_list_menu = $('.conversation-list-menu');
        if ($(conversation_list_menu).hasClass('active')) {
            $(this).html('Открыть список пользователей');
            $(this).css('background', '#707F99');
            $(conversation_list_menu).removeClass('active');
        }
        else {
            $(this).html('Закрыть список пользователей');
            $(this).css('background', '#4F4F4F');
            $(conversation_list_menu).addClass('active');
        }
    });


    $(document).ready(function () {

        // Если высота блока сообщений не имеет скролла, то при открытии
        // страницы непрочитанные сообщения станут прочитанными
        var timeoutReadMessage;
        var heightScreen = $(body).height(); // Высота экрана
        var scrollHeight = simpleBarDataChatDevelopment.getScrollElement().scrollHeight; // Высота скролла
        if (scrollHeight <= heightScreen - 290) {

            var chat = $(body).find('.data-chat');
            if(timeoutReadMessage) clearTimeout(timeoutReadMessage);
            timeoutReadMessage = setTimeout(function() { //чтобы не искать одно и то же несколько раз

                $(chat).find('.addressee-development.unreadmessage').each(function (index, item) {

                    var message_id = $(item).attr('id').split('-')[1];
                    var url = '/admin/message/read-message-development?id=' + message_id;

                    $.ajax({
                        url: url,
                        method: 'POST',
                        cache: false,
                        success: function(response){
                            // Меняем стили для прочитанного сообщения
                            if (response.success) $(item).removeClass('unreadmessage');
                            // Меняем в шапке сайта в иконке количество непрочитанных сообщений
                            var countUnreadMessagesAfterRead = $(body).find('.countUnreadMessages');
                            var newQuantityAfterRead = response.countUnreadMessages;
                            $(countUnreadMessagesAfterRead).html(newQuantityAfterRead);
                            if (newQuantityAfterRead < 1) $(countUnreadMessagesAfterRead).removeClass('active');
                            // Меняем в блоке бесед кол-во непрочитанных сообщений для конкретной беседы
                            var blockConversation = $('#conversation-list-menu').find(response.blockConversation);
                            var blockCountUnreadMessagesConversation = $(blockConversation).find('.countUnreadMessagesSender');
                            var countUnreadMessagesForConversation = response.countUnreadMessagesForConversation;
                            $(blockCountUnreadMessagesConversation).html(countUnreadMessagesForConversation);
                            if (countUnreadMessagesForConversation < 1) $(blockCountUnreadMessagesConversation).removeClass('active');
                        }
                    });
                });
            },100);
        }


        // Отслеживаем скролл непрочитанных сообщений
        simpleBarDataChatDevelopment.getScrollElement().addEventListener('scroll', function () {

            var chat = $(body).find('.data-chat');
            if(timeoutReadMessage) clearTimeout(timeoutReadMessage);
            timeoutReadMessage = setTimeout(function() { //чтобы не искать одно и то же несколько раз

                $(chat).find('.addressee-development.unreadmessage').each(function (index, item) {

                    var scrollTop = simpleBarDataChatDevelopment.getScrollElement().scrollTop,
                        scrollHeight = simpleBarDataChatDevelopment.getScrollElement().scrollHeight,
                        posTop = $(item).offset().top;

                    if (posTop + ($(item).height() / 2) <= $(chat).height() || scrollTop + $(item).height() > scrollHeight - $(chat).height()) {

                        var message_id = $(item).attr('id').split('-')[1];
                        var url = '/admin/message/read-message-development?id=' + message_id;

                        $.ajax({
                            url: url,
                            method: 'POST',
                            cache: false,
                            success: function(response){
                                // Меняем стили для прочитанного сообщения
                                if (response.success) $(item).removeClass('unreadmessage');
                                // Меняем в шапке сайта в иконке количество непрочитанных сообщений
                                var countUnreadMessagesAfterRead = $(body).find('.countUnreadMessages');
                                var newQuantityAfterRead = response.countUnreadMessages;
                                $(countUnreadMessagesAfterRead).html(newQuantityAfterRead);
                                if (newQuantityAfterRead < 1) $(countUnreadMessagesAfterRead).removeClass('active');
                                // Меняем в блоке бесед кол-во непрочитанных сообщений для конкретной беседы
                                var blockConversation = $('#conversation-list-menu').find(response.blockConversation);
                                var blockCountUnreadMessagesConversation = $(blockConversation).find('.countUnreadMessagesSender');
                                var countUnreadMessagesForConversation = response.countUnreadMessagesForConversation;
                                $(blockCountUnreadMessagesConversation).html(countUnreadMessagesForConversation);
                                if (countUnreadMessagesForConversation < 1) $(blockCountUnreadMessagesConversation).removeClass('active');
                            }
                        });
                    }
                });
            },100);
        });
    });


    // Обновляем данные на странице
    setInterval(function(){


        // Если высота блока сообщений не имеет скролла, то при открытии
        // страницы непрочитанные сообщения станут прочитанными
        var timeoutReadMessage;
        var heightScreen = $(body).height(); // Высота экрана
        var scrollHeight = simpleBarDataChatDevelopment.getScrollElement().scrollHeight; // Высота скролла
        var chat = $(body).find('.data-chat');
        if (scrollHeight <= heightScreen - 290) {

            if(timeoutReadMessage) clearTimeout(timeoutReadMessage);
            timeoutReadMessage = setTimeout(function() { //чтобы не искать одно и то же несколько раз

                $(chat).find('.addressee-development.unreadmessage').each(function (index, item) {

                    var message_id = $(item).attr('id').split('-')[1];
                    var url = '/admin/message/read-message-development?id=' + message_id;

                    $.ajax({
                        url: url,
                        method: 'POST',
                        cache: false,
                        success: function(response){
                            // Меняем стили для прочитанного сообщения
                            if (response.success) $(item).removeClass('unreadmessage');
                            // Меняем в шапке сайта в иконке количество непрочитанных сообщений
                            var countUnreadMessagesAfterRead = $(body).find('.countUnreadMessages');
                            var newQuantityAfterRead = response.countUnreadMessages;
                            $(countUnreadMessagesAfterRead).html(newQuantityAfterRead);
                            if (newQuantityAfterRead < 1) $(countUnreadMessagesAfterRead).removeClass('active');
                            // Меняем в блоке бесед кол-во непрочитанных сообщений для конкретной беседы
                            var blockConversation = $('#conversation-list-menu').find(response.blockConversation);
                            var blockCountUnreadMessagesConversation = $(blockConversation).find('.countUnreadMessagesSender');
                            var countUnreadMessagesForConversation = response.countUnreadMessagesForConversation;
                            $(blockCountUnreadMessagesConversation).html(countUnreadMessagesForConversation);
                            if (countUnreadMessagesForConversation < 1) $(blockCountUnreadMessagesConversation).removeClass('active');
                        }
                    });
                });
            },100);
        }

        // Обновляем беседы техподдержки
        $.ajax({
            url: '/admin/message/get-list-update-conversations?id=' + conversation_id + '&pathname=technical-support',
            method: 'POST',
            cache: false,
            success: function(response){

                var conversation_list_menu = $('#conversation-list-menu');
                var conversation_id = $(conversation_list_menu).find('.active-message').attr('id');
                conversation_id = '#' + conversation_id;

                $(conversation_list_menu).find('.containerForAllConversations').html(response.conversationsForDevelopmentAjax);
                if (!$(conversation_list_menu).find(conversation_id).hasClass('active-message')) $(conversation_list_menu).find(conversation_id).addClass('active-message');

            }
        });


        // Проверяем прочитал ли главный админ сообщения
        $(chat).find('.addressee-main_admin.unreadmessage').each(function (index, item) {

            var message_id = $(item).attr('id').split('-')[1];
            var url = '/admin/message/checking-unread-message-development?id=' + message_id;

            $.ajax({
                url: url,
                method: 'POST',
                cache: false,
                success: function(response){

                    if (response.checkRead === true) {
                        $(item).removeClass('unreadmessage');
                    }
                }
            });
        });


    }, 30000);
</script>
<?//php $this->registerJsFile('@web/js/admin_main_message_technical_suport_development.js'); ?>
<?php $this->registerJsFile('@web/js/form_message_development_and_main_admin.js'); ?>
