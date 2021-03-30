<?php

use yii\helpers\Html;
use app\models\MessageDevelopment;

?>

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
