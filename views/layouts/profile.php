<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\ProfileAsset;
use yii\bootstrap\Modal;

ProfileAsset::register($this);
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

            <div class="" style="margin-bottom: -20px;">

                <?php
                NavBar::begin([
                    'id' => 'main_menu_user',
                    'brandLabel' => Yii::$app->name = 'Spaccel',
                    'brandUrl' => Yii::$app->homeUrl,
                    'brandOptions' => ['class' => 'font_nav_menu_brand'],
                    'options' => [
                        'class' => 'navbar-inverse navbar-fixed-top',
                    ],
                    'renderInnerContainer' => false,
                ]);


                $conversation = \app\models\ConversationAdmin::findOne(['user_id' => Yii::$app->user->id]);

                echo Nav::widget([
                    'id' => 'main_navbar_right',
                    'options' => ['class' => 'navbar-nav navbar-right font_nav_menu_link'],
                    'items' => [
                        ['label' => 'Главная', 'url' => ['/']],
                        ['label' => 'Проекты', 'url' => ['/projects/index', 'id' => Yii::$app->user->id]],
                        ['label' => 'О сервисе', 'url' => ['/about']],

                        !Yii::$app->user->isGuest ? ([
                            'label' => Yii::$app->user->identity['avatar_image'] ? Html::img('/web/upload/user-'.Yii::$app->user->id.'/avatar/'.Yii::$app->user->identity['avatar_image'], ['class' => 'icon_user_avatar user_profile_picture'])
                                : Html::img('/images/icons/button_user_menu.png', ['class' => 'icon_user_avatar_default user_profile_picture']),
                            'items' => [
                                ['label' => 'Мой профиль', 'url' => Url::to(['/site/profile', 'id' => Yii::$app->user->identity['id']])],
                                ['label' => '<span>Выход ('.Yii::$app->user->identity['username'].')</span>', 'url' => Url::to(['/site/logout'])],
                            ],
                        ]) : (''),

                        ['label' => Html::img('/images/icons/icon_messanger.png', ['style' => ['width' => '44px', 'padding' => '0', 'margin' => '-10px 0']]), 'url' => ['/message/view', 'id' => $conversation->id]],
                    ],
                    'encodeLabels' => false,
                ]);
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
