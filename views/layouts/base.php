<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\AppAsset;
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

    <?php if (!Yii::$app->user->isGuest) : ?>
        <?php $user = User::findOne(Yii::$app->user->id); $user_id = Yii::$app->user->id; ?>
    <?php else : ?>
        <?php $user_id = 0; ?>
    <?php endif; ?>

    <div class="shared-container" id="simplebar-shared-container">

        <div class="wrap" id="identifying_recipient_new_message-<?= $user_id; ?>">

            <div style="margin-bottom: -20px;">

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
                    'options' => ['class' => 'navbar-nav navbar-right font_nav_menu_link'],
                    'items' => [

                        !Yii::$app->user->isGuest ? (
                        ['label' => 'Проекты', 'url' => ['/projects/index', 'id' => Yii::$app->user->id]]) : (''),

                        ['label' => 'О сервисе', 'url' => ['/about']],

                        !Yii::$app->user->isGuest ? ([
                            'label' => Yii::$app->user->identity['avatar_image'] ? Html::img('/web/upload/user-'.Yii::$app->user->id.'/avatar/'.Yii::$app->user->identity['avatar_image'], ['class' => 'icon_user_avatar user_profile_picture'])
                                : Html::img('/images/icons/button_user_menu.png', ['class' => 'icon_user_avatar_default user_profile_picture']),
                            'items' => [
                                ['label' => 'Мой профиль', 'url' => Url::to(['/site/profile', 'id' => Yii::$app->user->identity['id']])],
                                ['label' => '<span>Выход ('.Yii::$app->user->identity['username'].')</span>', 'url' => Url::to(['/site/logout'])],
                            ],
                        ]) : (''),

                        !Yii::$app->user->isGuest ? (
                        ['label' => $user->countUnreadMessages ? '<div class="countUnreadMessages active">' . $user->countUnreadMessages . '</div>' . Html::img('/images/icons/icon_messager_animation.svg', ['class' => 'icon_messanger'])
                            : '<div class="countUnreadMessages"></div>' . Html::img('/images/icons/icon_messager_animation.svg', ['class' => 'icon_messanger']), 'url' => ['/message/index', 'id' => Yii::$app->user->id]]) : '',
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

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
