<?php

use yii\helpers\Html;
use app\models\User;

?>

<?php if (!$conversations_query) : ?>

    <div class="block_no_search_result">По вашему запросу не найдено ни одного пользователя...</div>

<?php else : ?>

    <?php foreach ($conversations_query as $conversation) : ?>

        <?php if (User::isUserAdmin($conversation->user->username)) : ?>

            <div class="conversation-link" id="adminConversation-<?= $conversation->id; ?>">

                <?php if ($conversation->user->avatar_image) : ?>
                    <?= Html::img('/web/upload/user-'.$conversation->user->id.'/avatar/'.$conversation->user->avatar_image, ['class' => 'user_picture_search']); ?>
                <?php else : ?>
                    <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_search_default']); ?>
                <?php endif; ?>

                <?= $conversation->user->second_name . ' ' . $conversation->user->first_name . ' ' . $conversation->user->middle_name; ?>
            </div>

        <?php elseif (User::isUserSimple($conversation->user->username)) : ?>

            <div class="conversation-link" id="conversation-<?= $conversation->id; ?>">

                <?php if ($conversation->user->avatar_image) : ?>
                    <?= Html::img('/web/upload/user-'.$conversation->user->id.'/avatar/'.$conversation->user->avatar_image, ['class' => 'user_picture_search']); ?>
                <?php else : ?>
                    <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_search_default']); ?>
                <?php endif; ?>

                <?= $conversation->user->second_name . ' ' . $conversation->user->first_name . ' ' . $conversation->user->middle_name; ?>
            </div>

        <?php endif; ?>

    <?php endforeach; ?>

<?php endif; ?>
