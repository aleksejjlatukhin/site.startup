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

<div class="wrap table-project-kartik">

    <div class="" style="margin-bottom: -20px;">

        <?php
        NavBar::begin([
            'id' => 'main_menu',
            'brandLabel' => Yii::$app->name = 'Spaccel',
            'brandUrl' => Yii::$app->homeUrl,
            'brandOptions' => ['class' => 'font_nav_menu_brand'],
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
            'renderInnerContainer' => false,
        ]);


        /*if (!Yii::$app->user->isGuest):
            */?><!--
            <div class="navbar-form navbar-right">
                <button class="btn btn-md btn-default"
                        data-container="body"
                        data-toggle="popover"
                        data-trigger="focus"
                        data-placement="bottom"
                        data-title="<?/*= Yii::$app->user->identity['username'] */?>"
                        data-content="
                                <p><a href='<?/*= Url::to(['/site/profile', 'id' => Yii::$app->user->identity['id']]) */?>' data-method='post'>Мой профиль</a></p>
                                <p><a href='<?/*= Url::to(['/site/logout']) */?>' data-method='post'>Выход</a></p>
                            ">
                    <span class="glyphicon glyphicon-user"></span>
                </button>
            </div>
        --><?php
/*        endif;*/

        $conversation = \app\models\ConversationAdmin::findOne(['user_id' => Yii::$app->user->id]);

        echo Nav::widget([
            'id' => 'main_navbar_right',
            'options' => ['class' => 'navbar-nav navbar-right font_nav_menu_link'],
            'items' => [
                ['label' => 'Главная', 'url' => ['/']],
                ['label' => 'Проекты', 'url' => ['/projects/index', 'id' => Yii::$app->user->id]],
                ['label' => 'О проекте', 'url' => ['/site/#']],
                //['label' => 'Сообщения', 'url' => ['/message/view', 'id' => $conversation->id]],

                !Yii::$app->user->isGuest ? ([
                    'label' => Html::img('/images/icons/button_user_menu.png', ['style' => ['width' => '35px', 'padding' => '0', 'margin' => '-10px 0']]),
                    'items' => [
                        ['label' => 'Мой профиль', 'url' => Url::to(['/site/profile', 'id' => Yii::$app->user->identity['id']])],
                        /*'<li class="divider" style="padding: 0; margin: 0;"></li>',*/
                        /*'<li class="dropdown-header">'.Yii::$app->user->identity['username'].'</li>',*/
                        ['label' => '<span>Выход ('.Yii::$app->user->identity['username'].')</span>', 'url' => Url::to(['/site/logout'])],
                    ],
                ]) : (''),
            ],
            'encodeLabels' => false,
        ]);
        NavBar::end();
        ?>

    </div>






    <div class="container-fluid">

<!--        <div class="menu-arrow">-->
<!---->
<!--            <div id="menu-arrow-container">-->
<!---->
<!--                --><?//
//                    //имя контроллера страницы на которой находится пользователь
//                    $controller = explode('/', Yii::$app->request->url)[1];
//                ?>
<!---->
<!--                -->
<!---->
<!--                --><?php //if ($controller == 'segment') : ?>
<!---->
<!--                    --><?//= '<div class="menu-arrowlist-active">1.<br>Генерация<br>ГЦС<div class="crib-gcs">Генерация гипотез целевых сегментов</div></div>';?>
<!---->
<!--                --><?php //else : ?>
<!---->
<!--                    --><?//= '<div class="menu-arrowlist">1.<br>Генерация<br>ГЦС<div class="crib-gcs">Генерация гипотез целевых сегментов</div></div>';?>
<!---->
<!--                --><?php //endif; ?>
<!---->
<!--                -->
<!---->
<!--                --><?php //if ($controller == 'interview' || $controller == 'respond' || $controller == 'feedback-expert'
//                    || $controller == 'generation-problem' || $controller == 'desc-interview') : ?>
<!---->
<!--                    --><?//= '<div class="menu-arrowlist-active">2.<br>Генерация<br>ГПС<div class="crib-gcs">Генерация гипотез проблем сегмента</div></div>';?>
<!---->
<!--                --><?php //else : ?>
<!---->
<!--                    --><?//= '<div class="menu-arrowlist">2.<br>Генерация<br>ГПС<div class="crib-gcs">Генерация гипотез проблем сегмента</div></div>';?>
<!---->
<!--                --><?php //endif; ?>
<!---->
<!--                -->
<!---->
<!--                --><?php //if ($controller == 'confirm-problem' || $controller == 'responds-confirm' || $controller == 'desc-interview-confirm'
//                    || $controller == 'feedback-expert-confirm') : ?>
<!---->
<!--                    --><?//= '<div class="menu-arrowlist-active">3.<br>Подтвержд<br>ение ГПС<div class="crib-gcs">Подтверждение гипотезы проблемы сегмента</div></div>';?>
<!---->
<!--                --><?php //else : ?>
<!---->
<!--                    --><?//= '<div class="menu-arrowlist">3.<br>Подтвержд<br>ение ГПС<div class="crib-gcs">Подтверждение гипотезы проблемы сегмента</div></div>';?>
<!---->
<!--                --><?php //endif; ?>
<!---->
<!--                -->
<!---->
<!--                --><?php //if ($controller == 'gcp') : ?>
<!---->
<!--                    --><?//= '<div class="menu-arrowlist-active">4.<br>Разработка<br>ГЦП<div class="crib-gcs">Разработка гипотез ценностных предложений</div></div>';?>
<!---->
<!--                --><?php //else : ?>
<!---->
<!--                    --><?//= '<div class="menu-arrowlist">4.<br>Разработка<br>ГЦП<div class="crib-gcs">Разработка гипотез ценностных предложений</div></div>';?>
<!---->
<!--                --><?php //endif; ?>
<!---->
<!--                -->
<!---->
<!--                --><?php //if ($controller == 'confirm-gcp' || $controller == 'responds-gcp' || $controller == 'feedback-expert-gcp'
//                    || $controller == 'desc-interview-gcp') : ?>
<!---->
<!--                    --><?//= '<div class="menu-arrowlist-active">5.<br>Подтвержд<br>ение ГЦП<div class="crib-gcs">Подтверждение гипотезы ценностного предложения</div></div>';?>
<!---->
<!--                --><?php //else : ?>
<!---->
<!--                    --><?//= '<div class="menu-arrowlist">5.<br>Подтвержд<br>ение ГЦП<div class="crib-gcs">Подтверждение гипотезы ценностного предложения</div></div>';?>
<!---->
<!--                --><?php //endif; ?>
<!---->
<!--                -->
<!---->
<!--                --><?php //if ($controller == 'mvp') : ?>
<!---->
<!--                    --><?//= '<div class="menu-arrowlist-active">6.<br>Разработка<br>ГMVP<div class="crib-gcs">Разработка гипотез минимально жизнеспособных продуктов</div></div>';?>
<!---->
<!--                --><?php //else : ?>
<!---->
<!--                    --><?//= '<div class="menu-arrowlist">6.<br>Разработка<br>ГMVP<div class="crib-gcs">Разработка гипотез минимально жизнеспособных продуктов</div></div>';?>
<!---->
<!--                --><?php //endif; ?>
<!---->
<!--                -->
<!---->
<!--                --><?php //if ($controller == 'confirm-mvp' || $controller == 'responds-mvp' || $controller == 'feedback-expert-mvp'
//                    || $controller == 'desc-interview-mvp') : ?>
<!---->
<!--                    --><?//= '<div class="menu-arrowlist-active">7.<br>Подтвержд<br>ение ГMVP<div class="crib-gcs">Подтверждение гипотезы минимально жизнеспособного продукта</div></div>';?>
<!---->
<!--                --><?php //else : ?>
<!---->
<!--                    --><?//= '<div class="menu-arrowlist">7.<br>Подтвержд<br>ение ГMVP<div class="crib-gcs">Подтверждение гипотезы минимально жизнеспособного продукта</div></div>';?>
<!---->
<!--                --><?php //endif; ?>
<!---->
<!--                -->
<!---->
<!--                --><?php //if ($controller == 'business-model') : ?>
<!---->
<!--                    <div class="menu-last-arrow-active">8.<br>Генерация<br>бизнес-модели<div class="crib-gcs-last">Генерация бизнес-модели по Остервальдеру</div></div>-->
<!---->
<!--                --><?php //else: ?>
<!---->
<!--                    <div class="menu-last-arrow">8.<br>Генерация<br>бизнес-модели<div class="crib-gcs-last">Генерация бизнес-модели по Остервальдеру</div></div>-->
<!---->
<!--                --><?php //endif;?>
<!---->
<!--            </div>-->

<!--        </div>-->

<!--        --><?//= Breadcrumbs::widget([
//            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
//        ]) ?>
<!--        --><?//= Alert::widget() ?>
<!---->
<!--        --><?php //if( Yii::$app->session->hasFlash('success') ): ?>
<!--            <div class="alert alert-success alert-dismissible" role="alert">-->
<!--                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
<!--                --><?php //echo Yii::$app->session->getFlash('success'); ?>
<!--            </div>-->
<!--        --><?php //endif;?>
<!---->
<!--        --><?php //if( Yii::$app->session->hasFlash('error') ): ?>
<!--            <div class="alert alert-danger alert-dismissible" role="alert">-->
<!--                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
<!--                --><?php //echo Yii::$app->session->getFlash('error'); ?>
<!--            </div>-->
<!--        --><?php //endif;?>

        <?= $content ?>

    </div>
</div>

<footer class="footer">
    <div class="container-fluid">
        <p class="pull-left">&copy; Spaccel <?= date('Y') ?></p>

        <!--<p class="pull-right"><?/*= Yii::powered() */?></p>-->
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
