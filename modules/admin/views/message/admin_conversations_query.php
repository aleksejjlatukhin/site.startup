<?php

use yii\helpers\Html;

?>

<?php if ($conversations_query) : ?>

    <?php foreach ($conversations_query as $conversation) : ?>

        <div class="conversation-link" id="adminConversation-<?= $conversation->id; ?>">

            <?php if ($conversation->admin->avatar_image) : ?>
                <?= Html::img('/web/upload/user-'.$conversation->admin->id.'/avatar/'.$conversation->admin->avatar_image, ['class' => 'user_picture_search']); ?>
            <?php else : ?>
                <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_search_default']); ?>
            <?php endif; ?>

            <?= $conversation->admin->second_name . ' ' . $conversation->admin->first_name . ' ' . $conversation->admin->middle_name; ?>
        </div>

    <?php endforeach; ?>

<?php else : ?>

    <div class="block_no_search_result">По вашему запросу не найдено ни одного администратора...</div>

<?php endif;
