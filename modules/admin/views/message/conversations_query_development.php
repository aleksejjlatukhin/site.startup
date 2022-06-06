<?php

use app\models\ClientSettings;
use app\models\ClientUser;
use yii\helpers\Html;
use app\models\User;

?>

<?php if (!$conversations_query) : ?>

    <div class="block_no_search_result">По вашему запросу не найдено ни одного пользователя...</div>

<?php else : ?>

    <?php foreach ($conversations_query as $conversation) : ?>

        <?php if (User::isUserSimple($conversation->user->username)) : ?>

            <div class="conversation-link" id="conversation-<?= $conversation->id; ?>">

                <?php if ($conversation->user->avatar_image) : ?>
                    <?= Html::img('/web/upload/user-'.$conversation->user->id.'/avatar/'.$conversation->user->avatar_image, ['class' => 'user_picture_search']); ?>
                <?php else : ?>
                    <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_search_default']); ?>
                <?php endif; ?>

                <?= $conversation->user->username; ?>
            </div>

        <?php elseif (User::isUserExpert($conversation->user->username)) : ?>

            <div class="conversation-link" id="expertConversation-<?= $conversation->id; ?>">

                <?php if ($conversation->user->avatar_image) : ?>
                    <?= Html::img('/web/upload/user-'.$conversation->user->id.'/avatar/'.$conversation->user->avatar_image, ['class' => 'user_picture_search']); ?>
                <?php else : ?>
                    <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_search_default']); ?>
                <?php endif; ?>

                <?= $conversation->user->username; ?>
            </div>

        <?php elseif (User::isUserMainAdmin($conversation->user->username) || User::isUserManager($conversation->user->username)) : ?>

            <div class="conversation-link" id="adminConversation-<?= $conversation->id; ?>">

                <?php if ($conversation->user->avatar_image) : ?>
                    <?= Html::img('/web/upload/user-'.$conversation->user->id.'/avatar/'.$conversation->user->avatar_image, ['class' => 'user_picture_search']); ?>
                <?php else : ?>
                    <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_search_default']); ?>
                <?php endif; ?>

                <?= $conversation->user->username; ?>
            </div>

        <?php elseif (User::isUserAdmin($conversation->user->username)) : ?>

            <?php
            /** @var ClientUser $clientUser */
            $clientUser = $conversation->user->clientUser;
            $clientSettings = ClientSettings::findOne(['client_id' => $clientUser->getClientId()]);
            $adminCompany = User::findOne(['id' => $clientSettings->getAdminId()]);
            ?>

            <?php if (User::isUserMainAdmin($adminCompany->getUsername())) : ?>

                <div class="conversation-link" id="adminConversation-<?= $conversation->id;?>">

            <?php else : ?>

                <div class="conversation-link" id="clientAdminConversation-<?= $conversation->id;?>">

            <?php endif; ?>

                <?php if ($conversation->user->avatar_image) : ?>
                    <?= Html::img('/web/upload/user-'.$conversation->user->id.'/avatar/'.$conversation->user->avatar_image, ['class' => 'user_picture_search']); ?>
                <?php else : ?>
                    <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_search_default']); ?>
                <?php endif; ?>

                <?= $conversation->user->username; ?>
            </div>

        <?php elseif (User::isUserAdminCompany($conversation->user->username)) : ?>

            <div class="conversation-link" id="clientAdminConversation-<?= $conversation->id; ?>">

                <?php if ($conversation->user->avatar_image) : ?>
                    <?= Html::img('/web/upload/user-'.$conversation->user->id.'/avatar/'.$conversation->user->avatar_image, ['class' => 'user_picture_search']); ?>
                <?php else : ?>
                    <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_search_default']); ?>
                <?php endif; ?>

                <?= $conversation->user->username; ?>
            </div>

        <?php endif; ?>

    <?php endforeach; ?>

<?php endif;
