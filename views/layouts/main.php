<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\AppAsset;
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
                    'label' => Html::img('/images/icons/button_user_menu.png', ['style' => ['width' => '44px', 'padding' => '0', 'margin' => '-10px 0']]),
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


</div>

<footer class="footer">
    <div class="container-fluid">
        <p class="pull-left">&copy; СТАРТПУЛ, <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
