<?php

use yii\helpers\Html;
use app\modules\expert\models\MessageExpert;

?>

<?php $totalDateMessages = array(); // Массив общих дат сообщений ?>
<?php array_push($totalDateMessages, $lastMessageOnPage->dayAndDateRus); ?>

<?php foreach ($messages as $i => $message) : ?>

    <?php
    // Вывод общих дат для сообщений
    if (!in_array($message->dayAndDateRus, $totalDateMessages)) {
        array_push($totalDateMessages, $message->dayAndDateRus);
        echo '<div class="dayAndDayMessage">'.$message->dayAndDateRus.'</div>';
    }
    ?>

    <?php if ($message->sender_id != $user->id) : ?>

        <?php if ($message->status == MessageExpert::NO_READ_MESSAGE) : ?>

            <div class="message addressee-user unreadmessage" id="message_id-<?= $message->id;?>">

                <?php if ($expert->avatar_image) : ?>
                    <?= Html::img('/web/upload/user-'.$expert->id.'/avatar/'.$expert->avatar_image, ['class' => 'user_picture_message']); ?>
                <?php else : ?>
                    <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default_message']); ?>
                <?php endif; ?>

                <div class="sender_data">
                    <div class="sender_info">
                        <div class="interlocutor"><?= $expert->username; ?></div>
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
                                        <?= Html::a($file->file_name, ['/expert/message/download', 'category' => $file->category, 'id' => $file->id], ['target' => '_blank', 'title' => $file->file_name]);?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>

            </div>

        <?php else : ?>

            <div class="message addressee-user" id="message_id-<?= $message->id;?>">

                <?php if ($expert->avatar_image) : ?>
                    <?= Html::img('/web/upload/user-'.$expert->id.'/avatar/'.$expert->avatar_image, ['class' => 'user_picture_message']); ?>
                <?php else : ?>
                    <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default_message']); ?>
                <?php endif; ?>

                <div class="sender_data">
                    <div class="sender_info">
                        <div class="interlocutor"><?= $expert->username; ?></div>
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
                                        <?= Html::a($file->file_name, ['/expert/message/download', 'category' => $file->category, 'id' => $file->id], ['target' => '_blank', 'title' => $file->file_name]);?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>

            </div>

        <?php endif; ?>

    <?php else : ?>

        <?php if ($message->status == MessageExpert::NO_READ_MESSAGE) : ?>

            <div class="message addressee-expert unreadmessage" id="message_id-<?= $message->id;?>">

                <?php if ($user->avatar_image) : ?>
                    <?= Html::img('/web/upload/user-'.$user->id.'/avatar/'.$user->avatar_image, ['class' => 'user_picture_message']); ?>
                <?php else : ?>
                    <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default_message']); ?>
                <?php endif; ?>

                <div class="sender_data">
                    <div class="sender_info">
                        <div class="interlocutor"><?= $user->username; ?></div>
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
                                        <?= Html::a($file->file_name, ['/expert/message/download', 'category' => $file->category, 'id' => $file->id], ['target' => '_blank', 'title' => $file->file_name]);?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>

            </div>

        <?php else : ?>

            <div class="message addressee-expert" id="message_id-<?= $message->id;?>">

                <?php if ($user->avatar_image) : ?>
                    <?= Html::img('/web/upload/user-'.$user->id.'/avatar/'.$user->avatar_image, ['class' => 'user_picture_message']); ?>
                <?php else : ?>
                    <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default_message']); ?>
                <?php endif; ?>

                <div class="sender_data">
                    <div class="sender_info">
                        <div class="interlocutor"><?= $user->username; ?></div>
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
                                        <?= Html::a($file->file_name, ['/expert/message/download', 'category' => $file->category, 'id' => $file->id], ['target' => '_blank', 'title' => $file->file_name]);?>
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
