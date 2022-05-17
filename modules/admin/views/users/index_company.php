<?php

use yii\helpers\Html;
use app\models\User;

$this->title = 'Проектанты «' . $client->getName() . '»';
$this->registerCssFile('@web/css/users-index-style.css');
?>

<div class="users-index">

    <div class="col-md-12" style="margin-top: 35px; padding-left: 25px;">
        <?= Html::a($this->title . Html::img('/images/icons/icon_report_next.png'), ['#'],[
            'class' => 'link_to_instruction_page open_modal_instruction_page',
            'title' => 'Инструкция', 'onclick' => 'return false'
        ]); ?>
    </div>

    <div class="container-fluid">

        <div class="row" style="display:flex; align-items: center; padding: 30px 0 15px 0; font-weight: 700;">

            <div class="col-md-3" style="padding-left: 30px;">
                Фамилия, имя, отчество
            </div>

            <div class="col-md-3 text-center">
                Трекер
            </div>

            <div class="col-md-2 text-center">
                Статус
            </div>

            <div class="col-md-2 text-center">
                E-mail, телефон
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

                <div class="row container-one_user user_container_number-<?=$user->id;?>">

                    <div class="col-md-3 column-user-fio" id="link_user_profile-<?= $user->id;?>">

                        <!--Проверка существования аватарки-->
                        <?php if ($user->avatar_image) : ?>
                            <?= Html::img('/web/upload/user-'.$user->id.'/avatar/'.$user->avatar_image, ['class' => 'user_picture']); ?>
                        <?php else : ?>
                            <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default']); ?>
                        <?php endif; ?>

                        <!--Проверка онлайн статуса-->
                        <?php if ($user->checkOnline === true) : ?>
                            <div class="checkStatusOnlineUser active"></div>
                        <?php else : ?>
                            <div class="checkStatusOnlineUser"></div>
                        <?php endif; ?>

                        <div class="block-fio-and-date-last-visit">
                            <div class="block-fio"><?= $user->second_name.' '.$user->first_name.' '.$user->middle_name; ?></div>
                            <div class="block-date-last-visit">
                                <?php if($user->checkOnline !== true && $user->checkOnline !== false) : ?>
                                    Пользователь был в сети <?= $user->checkOnline;?>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-3 column-tracker">

                        <?php if ($admin = $user->admin) : ?>
                            <span><?= $admin->second_name.' '.mb_substr($admin->first_name, 0, 1).'.'.mb_substr($admin->middle_name, 0, 1).'.';?></span>
                        <?php else : ?>
                            <span>Не установлен</span>
                        <?php endif; ?>

                    </div>

                    <div class="col-md-2 column-user-status">

                        <?php if ($user->status === User::STATUS_DELETED) : ?>
                            <span class="text-danger">Заблокирован</span>
                        <?php elseif ($user->status === User::STATUS_NOT_ACTIVE) : ?>
                            <span>Не активирован</span>
                        <?php elseif ($user->status === User::STATUS_ACTIVE) : ?>
                            <span class="text-success">Активирован</span>
                        <?php endif; ?>

                    </div>

                    <div class="col-md-2 text-center">
                        <div class=""><?= $user->email; ?></div>
                        <div class=""><?= $user->telephone; ?></div>
                    </div>

                    <div class="col-md-1 text-center">
                        <?= date('d.m.Y', $user->updated_at); ?>
                    </div>

                    <div class="col-md-1 text-center">
                        <?= date('d.m.Y', $user->created_at); ?>
                    </div>

                </div>

            <?php endforeach; ?>

            <div class="pagination-users">
                <?= \yii\widgets\LinkPager::widget([
                    'pagination' => $pages,
                    'activePageCssClass' => 'pagination_active_page',
                    'options' => ['class' => 'pagination-users-list'],
                ]); ?>
            </div>

        </div>
    </div>
</div>

<!--Подключение скриптов-->
<?php $this->registerJsFile('@web/js/users_index_main_admin.js'); ?>
