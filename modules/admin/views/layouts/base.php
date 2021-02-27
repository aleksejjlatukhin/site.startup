<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\AppAsset;
use app\models\User;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

    <div class="shared-container" id="simplebar-shared-container">

        <div class="wrap">

            <div style="margin-bottom: -20px;">

                <?php
                NavBar::begin([
                    'id' => 'main_menu_admin',
                    'brandLabel' => Yii::$app->name = 'Spaccel <span style="font-size: 13px;font-weight: 700;border-bottom: 1px solid #c0c0c0;">admin-panel</span>',
                    'brandUrl' => ['/admin/default/index'],
                    'brandOptions' => ['class' => 'font_nav_menu_brand'],
                    'options' => [
                        'class' => 'navbar-inverse navbar-fixed-top',
                    ],
                    'renderInnerContainer' => false,
                ]);


                if (User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])) :

                    echo Nav::widget([
                        'id' => 'main_navbar_right',
                        'options' => ['class' => 'navbar-nav navbar-right font_nav_menu_link'],
                        'items' => [
                            ['label' => 'Главная', 'url' => ['/admin/']],
                            ['label' => 'Проекты', 'url' => ['/admin/projects/index']],
                            ['label' => 'Пользователи', 'url' => ['/admin/users/index']],

                            [
                                'label' => Yii::$app->user->identity['avatar_image'] ? Html::img('/web/upload/user-'.Yii::$app->user->id.'/avatar/'.Yii::$app->user->identity['avatar_image'], ['class' => 'icon_user_avatar user_profile_picture'])
                                    : Html::img('/images/icons/button_user_menu.png', ['class' => 'icon_user_avatar_default user_profile_picture']),
                                'items' => [
                                    ['label' => 'Мой профиль', 'url' => Url::to(['/admin/profile/index', 'id' => Yii::$app->user->id])],
                                    ['label' => '<span>Выход ('.Yii::$app->user->identity['username'].')</span>', 'url' => Url::to(['/site/logout'])],
                                ],
                            ],

                            ['label' => Html::img('/images/icons/icon_messanger.png', ['style' => ['width' => '44px', 'padding' => '0', 'margin' => '-10px 0']]), 'url' => ['/admin/message/index', 'id' => Yii::$app->user->id]],
                        ],
                        'encodeLabels' => false,
                    ]);

                elseif (User::isUserAdmin(Yii::$app->user->identity['username'])) :

                    echo Nav::widget([
                        'id' => 'main_navbar_right',
                        'options' => ['class' => 'navbar-nav navbar-right font_nav_menu_link'],
                        'items' => [
                            ['label' => 'Главная', 'url' => ['/admin/']],
                            ['label' => 'Проекты', 'url' => ['/admin/projects/group', 'id' => Yii::$app->user->id]],
                            ['label' => 'Пользователи', 'url' => ['/admin/users/group', 'id' => Yii::$app->user->id]],

                            [
                                'label' => Yii::$app->user->identity['avatar_image'] ? Html::img('/web/upload/user-'.Yii::$app->user->id.'/avatar/'.Yii::$app->user->identity['avatar_image'], ['class' => 'icon_user_avatar user_profile_picture'])
                                    : Html::img('/images/icons/button_user_menu.png', ['class' => 'icon_user_avatar_default user_profile_picture']),
                                'items' => [
                                    ['label' => 'Мой профиль', 'url' => Url::to(['/admin/profile/index', 'id' => Yii::$app->user->id])],
                                    ['label' => '<span>Выход ('.Yii::$app->user->identity['username'].')</span>', 'url' => Url::to(['/site/logout'])],
                                ],
                            ],

                            ['label' => Html::img('/images/icons/icon_messanger.png', ['style' => ['width' => '44px', 'padding' => '0', 'margin' => '-10px 0']]), 'url' => ['/admin/message/index', 'id' => Yii::$app->user->id]],
                        ],
                        'encodeLabels' => false,
                    ]);

                endif;

                NavBar::end();
                ?>

            </div>


            <div class="container-fluid">
                <?= $content ?>
            </div>
        </div>


        <footer class="footer">
            <div class="container-fluid">
                <p class="pull-left">&copy; СТАРТПУЛ, <?= date('Y') ?></p>
            </div>
        </footer>

    </div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>