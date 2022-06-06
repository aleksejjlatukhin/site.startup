<?php

use app\modules\admin\models\MessageManager;
use yii\helpers\Html;

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

    <?php if ($message->sender_id != $manager->id) : ?>

        <?php if ($message->status == MessageManager::NO_READ_MESSAGE) : ?>

            <div class="message addressee-manager unreadmessage" id="message_id-<?= $message->id;?>">

                <?php if ($main_admin->avatar_image) : ?>
                    <?= Html::img('/web/upload/user-'.$main_admin->id.'/avatar/'.$main_admin->avatar_image, ['class' => 'user_picture_message']); ?>
                <?php else : ?>
                    <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default_message']); ?>
                <?php endif; ?>

                <div class="sender_data">
                    <div class="sender_info">
                        <div>Главный администратор</div>
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

            <div class="message addressee-manager" id="message_id-<?= $message->id;?>">

                <?php if ($main_admin->avatar_image) : ?>
                    <?= Html::img('/web/upload/user-'.$main_admin->id.'/avatar/'.$main_admin->avatar_image, ['class' => 'user_picture_message']); ?>
                <?php else : ?>
                    <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default_message']); ?>
                <?php endif; ?>

                <div class="sender_data">
                    <div class="sender_info">
                        <div>Главный администратор</div>
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

        <?php if ($message->status == MessageManager::NO_READ_MESSAGE) : ?>

            <div class="message addressee-main_admin unreadmessage" id="message_id-<?= $message->id;?>">

                <?php if ($manager->avatar_image) : ?>
                    <?= Html::img('/web/upload/user-'.$manager->id.'/avatar/'.$manager->avatar_image, ['class' => 'user_picture_message']); ?>
                <?php else : ?>
                    <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default_message']); ?>
                <?php endif; ?>

                <div class="sender_data">
                    <div class="sender_info">
                        <div class="interlocutor"><?= $manager->username; ?></div>
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

                <?php if ($manager->avatar_image) : ?>
                    <?= Html::img('/web/upload/user-'.$manager->id.'/avatar/'.$manager->avatar_image, ['class' => 'user_picture_message']); ?>
                <?php else : ?>
                    <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default_message']); ?>
                <?php endif; ?>

                <div class="sender_data">
                    <div class="sender_info">
                        <div class="interlocutor"><?= $manager->username; ?></div>
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
