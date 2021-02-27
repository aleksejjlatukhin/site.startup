<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\User;
use yii\bootstrap\Modal;

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

    <!--All-information Project begin-->

    <?php // Модальное окно - данные проекта
    Modal::begin([
        'options' => ['id' => 'data_project_modal'],
        'size' => 'modal-lg',
        'header' => '<h3 class="text-center">Исходные данные по проекту</h3>',
    ]); ?>
    <!--Контент добавляется через Ajax-->
    <?php Modal::end(); ?>

    <!--All-information Project end-->


    <!--All-information Segment begin-->

    <?php // Модальное окно - Данные сегмента
    Modal::begin([
        'options' => ['id' => 'data_segment_modal', 'class' => 'data_segment_modal',],
        'size' => 'modal-lg',
        'header' => '<h3 class="text-center">Исходные данные сегмента</h3>',
    ]); ?>
    <!--Контент добавляется через Ajax-->
    <?php Modal::end(); ?>

    <!--All-information Segment end-->


    <!--Roadmap Project begin-->

    <?php // Модальное окно - дорожная карта проекта
    Modal::begin([
        'options' => ['id' => 'showRoadmapProject', 'class' => 'showRoadmapProject'],
        'size' => 'modal-lg',
        'header' => '<h2 class="text-center" style="font-size: 32px; color: #4F4F4F;"></h2>',
    ]); ?>
    <!--Контент добавляется через Ajax-->
    <?php Modal::end(); ?>

    <!--Roadmap Project end-->


    <!--Roadmap Segment begin-->

    <?php // Модальное окно - дорожная карта сегмента
    Modal::begin([
        'options' => ['id' => 'showRoadmapSegment', 'class' => 'showRoadmapSegment'],
        'size' => 'modal-lg',
        'header' => '<div class="roadmap_segment_modal_header_title"><h2 class="roadmap_segment_modal_header_title_h2"></h2></div>',
    ]); ?>
    <!--Контент добавляется через Ajax-->
    <?php Modal::end(); ?>

    <!--Roadmap Segment end-->


    <!--Result Project begin-->

    <?php // Модальное окно - сводная таблица проекта
    Modal::begin([
        'options' => ['id' => 'showResultTableProject', 'class' => 'showResultTableProject'],
        'size' => 'modal-lg',
    ]); ?>
    <!--Контент добавляется через Ajax-->
    <?php Modal::end(); ?>

    <!--Result Project end-->


    <!--Report Project begin-->

    <?php // Модальное окно - протокол проекта
    Modal::begin([
        'options' => ['id' => 'showReportProject', 'class' => 'showReportProject'],
        'size' => 'modal-lg',
        'header' => '<h2 class="text-center" style="font-size: 32px; color: #4F4F4F;"></h2>',
    ]); ?>
    <!--Контент добавляется через Ajax-->
    <?php Modal::end(); ?>

    <!--Report Project end-->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
