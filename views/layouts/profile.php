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
                        /*'<li class="divider" style="padding: 0; margin: 0;"></li>',*/
                        /*'<li class="dropdown-header">'.Yii::$app->user->identity['username'].'</li>',*/
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

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
