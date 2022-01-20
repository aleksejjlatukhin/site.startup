<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\AppAsset;
use yii\bootstrap\Modal;
use app\models\User;

AppAsset::register($this);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => '/images/icons/favicon.png']);

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

    <?php $user = User::findOne(Yii::$app->user->id); ?>

    <div class="shared-container" id="simplebar-shared-container">

        <div class="wrap" id="identifying_recipient_new_message-<?= Yii::$app->user->id; ?>">

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

                echo Nav::widget([
                    'id' => 'main_navbar_right',
                    'options' => ['class' => 'navbar-nav navbar-right font_nav_menu_link'],
                    'items' => [

                        ['label' => $user->countUnreadCommunications ? '<div class="countUnreadCommunications active">' . $user->countUnreadCommunications . '</div>' . Html::img('/images/icons/icon_notification_bell.png', ['class' => 'icon_messanger', 'title' => 'Уведомления'])
                            : '<div class="countUnreadCommunications"></div>' . Html::img('/images/icons/icon_notification_bell.png', ['class' => 'icon_messanger', 'title' => 'Уведомления']), 'url' => ['/communications/notifications', 'id' => Yii::$app->user->id]],

                        ['label' => Html::img('/images/icons/projects_icon.png', ['class' => 'icon_messanger', 'title' => 'Проекты']), 'url' => ['/projects/index', 'id' => Yii::$app->user->id]],

                        [
                            'label' => Yii::$app->user->identity['avatar_image'] ? Html::img('/web/upload/user-'.Yii::$app->user->id.'/avatar/'.Yii::$app->user->identity['avatar_image'], ['class' => 'icon_user_avatar user_profile_picture'])
                                : Html::img('/images/icons/button_user_menu.png', ['class' => 'icon_user_avatar_default user_profile_picture']),
                            'items' => [
                                ['label' => 'Мой профиль', 'url' => Url::to(['/site/profile', 'id' => Yii::$app->user->identity['id']])],
                                ['label' => '<span>Выход ('.Yii::$app->user->identity['username'].')</span>', 'url' => Url::to(['/site/logout'])],
                            ],
                        ],

                        ['label' => $user->countUnreadMessages ? '<div class="countUnreadMessages active">' . $user->countUnreadMessages . '</div>' . Html::img('/images/icons/icon_messager_animation.svg', ['class' => 'icon_messanger', 'title' => 'Сообщения'])
                            : '<div class="countUnreadMessages"></div>' . Html::img('/images/icons/icon_messager_animation.svg', ['class' => 'icon_messanger', 'title' => 'Сообщения']), 'url' => ['/message/index', 'id' => Yii::$app->user->id]],

                        ['label' => Html::img('/images/icons/about-service.png', ['class' => 'icon_messanger', 'title' => 'О сервисе']), 'url' => ['/about']],

                        ['label' => Html::img('/images/icons/icon_light_bulb.png', ['class' => 'icon_messanger', 'title' => 'Методическое руководство']), 'url' => ['/site/methodological-guide']],
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
                <div class="row">
                    <div class="col-xs-7 col-sm-9 col-lg-10">&copy; СТАРТПУЛ, <?= date('Y') ?></div>
                    <div class="col-xs-5 col-sm-3 col-lg-2">
                        <div>тел: +79519042363</div>
                        <div>e-mail: spaccel@mail.ru</div>
                    </div>
                </div>
            </div>
        </footer>

    </div>

    <!--instruction_page begin-->

    <?php // Модальное окно - Инструкция для стадии разработки
    Modal::begin([
        'options' => ['class' => 'modal_instruction_page'],
        'size' => 'modal-lg',
    ]); ?>
    <!--Контент добавляется через Ajax-->
    <?php Modal::end(); ?>

    <!--instruction_page end-->

    <!--All-information Project begin-->

    <?php // Модальное окно - данные проекта
    Modal::begin([
        'options' => ['id' => 'data_project_modal'],
        'size' => 'modal-lg',
    ]); ?>
    <!--Контент добавляется через Ajax-->
    <?php Modal::end(); ?>

    <!--All-information Project end-->


    <!--All-information Segment begin-->

    <?php // Модальное окно - Данные сегмента
    Modal::begin([
        'options' => ['id' => 'data_segment_modal', 'class' => 'data_segment_modal'],
        'size' => 'modal-lg',
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


    <!--Modal Hypothesis delete begin-->

    <?php
    // Подтверждение удаления гипотезы
    Modal::begin([
        'options' => [
            'id' => "delete_hypothesis_modal",
            'class' => 'delete_hypothesis_modal',
        ],
        'size' => 'modal-md',
        'header' => '<h3 class="text-center header-update-modal">Выберите действие</h3>',
        'footer' => '<div class="text-center">'.

            Html::a('Отмена', ['#'],[
                'class' => 'btn btn-default',
                'style' => ['width' => '120px'],
                'onclick' => "$('#delete_hypothesis_modal').modal('hide'); return false;"
            ]).

            Html::a('Ок', ['#'],[
                'class' => 'btn btn-default',
                'style' => ['width' => '120px'],
                'id' => "confirm_delete_hypothesis",
            ]).

            '</div>'
    ]); ?>
    <h4 class="text-center"></h4>
    <!--Контент добавляется через Ajax-->
    <?php Modal::end(); ?>

    <!--Modal Hypothesis delete end-->


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
