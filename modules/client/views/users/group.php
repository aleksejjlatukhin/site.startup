<?php

use yii\data\Pagination;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\User;
use yii\widgets\LinkPager;

/**
 * @var User $admin
 * @var User[] $users
 * @var Pagination $pages
 */

$this->title = 'Пользователи | Трекер «'.$admin->getUsername().'»';
$this->registerCssFile('@web/css/users-index-style.css');

?>


<div class="users-index">

    <h3 style="margin: 30px 0 0 0;"><?= $this->title ?></h3>

    <div class="container-fluid">

        <div class="row" style="display:flex; align-items: center; padding: 30px 0 15px 0; font-weight: 700;">

            <div class="col-md-3" style="padding-left: 30px;">
                Логин
            </div>

            <div class="col-md-3 text-center">
                Проекты
            </div>

            <div class="col-md-2 text-center">
                Статус
            </div>

            <div class="col-md-2 text-center">
                E-mail
            </div>

            <div class="col-md-1 text-center">
                Дата измен.
            </div>

            <div class="col-md-1 text-center">
                Дата регистр.
            </div>

        </div>

        <div class="row block_all_users">

            <?php foreach ($users as $user) : ?>

                <div class="row container-one_user user_container_number-<?=$user->getId() ?>">

                    <div class="col-md-3 column-user-fio" id="link_user_profile-<?= $user->getId() ?>">

                        <!--Проверка существования аватарки-->
                        <?php if ($user->getAvatarImage()) : ?>
                            <?= Html::img('/web/upload/user-'.$user->getId().'/avatar/'.$user->getAvatarImage(), ['class' => 'user_picture']) ?>
                        <?php else : ?>
                            <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default']) ?>
                        <?php endif; ?>

                        <!--Проверка онлайн статуса-->
                        <?php if ($user->checkOnline === true) : ?>
                            <div class="checkStatusOnlineUser active"></div>
                        <?php else : ?>
                            <div class="checkStatusOnlineUser"></div>
                        <?php endif; ?>

                        <div class="block-fio-and-date-last-visit">
                            <div class="block-fio"><?= $user->getUsername() ?></div>
                            <div class="block-date-last-visit">
                                <?php if(is_string($user->checkOnline)) : ?>
                                    Пользователь был в сети <?= $user->checkOnline ?>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-3 column-tracker">

                        <?= Html::a( 'Проекты - '.count($user->projects), Url::to(['/projects/index', 'id' => $user->getId()]),[
                            'style' => [
                                'display' => 'flex',
                                'align-items' => 'center',
                                'justify-content' => 'center',
                                'background' => '#E0E0E0',
                                'width' => '180px',
                                'height' => '40px',
                                'font-size' => '18px',
                                'border-radius' => '8px',
                            ],
                            'class' => 'btn btn-lg btn-default',
                        ]) ?>

                    </div>

                    <div class="col-md-2 column-user-status">

                        <?php if (!User::isUserAdmin(Yii::$app->user->identity['username'])) : ?>

                            <?php if ($user->getStatus() === User::STATUS_DELETED) : ?>

                                <?= Html::submitButton('Заблокирован', [
                                    'class' => 'btn btn-lg btn-danger open_change_status_modal',
                                    'id' => 'open_change_status_modal-'.$user->getId(),
                                    'style' => [
                                        'display' => 'flex',
                                        'align-items' => 'center',
                                        'justify-content' => 'center',
                                        'background' => '#d9534f',
                                        'width' => '180px',
                                        'height' => '40px',
                                        'font-size' => '18px',
                                        'border-radius' => '8px',
                                    ],
                                ]) ?>

                            <?php elseif ($user->getStatus() === User::STATUS_NOT_ACTIVE) : ?>

                                <?= Html::submitButton('Не активирован', [
                                    'class' => 'btn btn-lg btn-default open_change_status_modal',
                                    'id' => 'open_change_status_modal-'.$user->getId(),
                                    'style' => [
                                        'display' => 'flex',
                                        'align-items' => 'center',
                                        'justify-content' => 'center',
                                        'background' => '#FFFFFF',
                                        'width' => '180px',
                                        'height' => '40px',
                                        'font-size' => '18px',
                                        'border-radius' => '8px',
                                    ],
                                ]) ?>

                            <?php elseif ($user->getStatus() === User::STATUS_ACTIVE) : ?>

                                <?= Html::submitButton('Активирован', [
                                    'class' => 'btn btn-lg btn-success open_change_status_modal',
                                    'id' => 'open_change_status_modal-'.$user->getId(),
                                    'style' => [
                                        'display' => 'flex',
                                        'align-items' => 'center',
                                        'justify-content' => 'center',
                                        'background' => '#52BE7F',
                                        'width' => '180px',
                                        'height' => '40px',
                                        'font-size' => '18px',
                                        'border-radius' => '8px',
                                    ],
                                ]) ?>

                            <?php endif; ?>

                        <?php else : ?>

                            <?php if ($user->getStatus() === User::STATUS_DELETED) : ?>

                                <h4 class="text-danger">Заблокирован</h4>

                            <?php elseif ($user->getStatus() === User::STATUS_NOT_ACTIVE) : ?>

                                <h4 class="text-primary">Не активирован</h4>

                            <?php elseif ($user->getStatus() === User::STATUS_ACTIVE) : ?>

                                <h4 class="text-success">Активирован</h4>

                            <?php endif; ?>

                        <?php endif; ?>

                    </div>

                    <div class="col-md-2 text-center">
                        <div class=""><?= $user->getEmail() ?></div>
                    </div>

                    <div class="col-md-1 text-center">
                        <?= date('d.m.Y', $user->getUpdatedAt()) ?>
                    </div>

                    <div class="col-md-1 text-center">
                        <?= date('d.m.Y', $user->getCreatedAt()) ?>
                    </div>

                </div>

            <?php endforeach; ?>

            <div class="pagination-users">
                <?= LinkPager::widget([
                    'pagination' => $pages,
                    'activePageCssClass' => 'pagination_active_page',
                    'options' => ['class' => 'pagination-users-list'],
                ]) ?>
            </div>

        </div>

    </div>

</div>

<!--Подключение скриптов-->
<?php $this->registerJsFile('@web/js/users_index_main_admin.js'); ?>




