<?php

use yii\helpers\Html;

?>

<?php if (!$conversations_query && !$expert_conversations_query) : ?>

    <div class="block_no_search_result">По вашему запросу не найдено ни одного пользователя...</div>

<?php else : ?>

    <?php foreach ($conversations_query as $conversation) : ?>

        <div class="conversation-link" id="conversation-<?= $conversation->id; ?>">

            <?php if ($conversation->user->avatar_image) : ?>
                <?= Html::img('/web/upload/user-'.$conversation->user->id.'/avatar/'.$conversation->user->avatar_image, ['class' => 'user_picture_search']); ?>
            <?php else : ?>
                <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_search_default']); ?>
            <?php endif; ?>

            <?= $conversation->user->second_name . ' ' . $conversation->user->first_name . ' ' . $conversation->user->middle_name; ?>
        </div>

    <?php endforeach; ?>

    <?php foreach ($expert_conversations_query as $conversation) : ?>

        <div class="conversation-link" id="expertConversation-<?= $conversation->id; ?>">

            <?php if ($conversation->expert->avatar_image) : ?>
                <?= Html::img('/web/upload/user-'.$conversation->expert->id.'/avatar/'.$conversation->expert->avatar_image, ['class' => 'user_picture_search']); ?>
            <?php else : ?>
                <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_search_default']); ?>
            <?php endif; ?>

            <?= $conversation->expert->second_name . ' ' . $conversation->expert->first_name . ' ' . $conversation->expert->middle_name; ?>
        </div>

    <?php endforeach; ?>

<?php endif;