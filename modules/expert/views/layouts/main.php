<?php

use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\ProfileAsset;

ProfileAsset::register($this);
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

            <div style="margin-bottom: -20px;">

                <?php
                NavBar::begin([
                    'id' => 'main_menu_expert',
                    'brandLabel' => Yii::$app->name = 'Spaccel <span style="font-size: 13px;font-weight: 700;border-bottom: 1px solid #c0c0c0;">expert-panel</span>',
                    'brandUrl' => ['/expert/default/index'],
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

                        !Yii::$app->user->isGuest ? (
                        ['label' => $user->countUnreadCommunications ? '<div class="countUnreadCommunications active">' . $user->countUnreadCommunications . '</div>' . Html::img('/images/icons/icon_notification_bell.png', ['class' => 'icon_messanger', 'title' => 'Уведомления'])
                            : '<div class="countUnreadCommunications"></div>' . Html::img('/images/icons/icon_notification_bell.png', ['class' => 'icon_messanger', 'title' => 'Уведомления']), 'url' => ['/expert/communications/notifications', 'id' => Yii::$app->user->id]]) : '',

                        ['label' => Html::img('/images/icons/icon_expertise.png', ['class' => 'icon_messanger', 'title' => 'Экспертизы']), 'url' => ['/expert/expertise/index']],

                        [
                            'label' => Yii::$app->user->identity['avatar_image'] ? Html::img('/web/upload/user-'.Yii::$app->user->id.'/avatar/'.Yii::$app->user->identity['avatar_image'], ['class' => 'icon_user_avatar user_profile_picture'])
                                : Html::img('/images/icons/button_user_menu.png', ['class' => 'icon_user_avatar_default user_profile_picture']),
                            'items' => [
                                ['label' => 'Мой профиль', 'url' => Url::to(['/expert/profile/index', 'id' => Yii::$app->user->id])],
                                ['label' => '<span>Выход ('.Yii::$app->user->identity['username'].')</span>', 'url' => Url::to(['/site/logout'])],
                            ],
                        ],

                        ['label' => $user->countUnreadMessages ? '<div class="countUnreadMessages active">' . $user->countUnreadMessages . '</div>' . Html::img('/images/icons/icon_messager_animation.svg', ['class' => 'icon_messanger', 'title' => 'Сообщения'])
                            : '<div class="countUnreadMessages"></div>' . Html::img('/images/icons/icon_messager_animation.svg', ['class' => 'icon_messanger', 'title' => 'Сообщения']), 'url' => ['/expert/message/index', 'id' => Yii::$app->user->id]],

                        !Yii::$app->user->isGuest ? (
                        ['label' => Html::img('/images/icons/icon_light_bulb.png', ['class' => 'icon_messanger', 'title' => 'Методическое руководство']), 'url' => ['/site/methodological-guide']]) : '',
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

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
