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

<div class="wrap">

    <div style="margin-bottom: -15px;">

        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name = 'site.startup',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);


        if (!Yii::$app->user->isGuest):
            ?>
            <div class="navbar-form navbar-right">
                <button class="btn btn-md btn-default"
                        data-container="body"
                        data-toggle="popover"
                        data-trigger="focus"
                        data-placement="bottom"
                        data-title="<?= Yii::$app->user->identity['username'] ?>"
                        data-content="
                                <p><a href='<?= Url::to(['/site/profile']) ?>' data-method='post'>Мой профиль</a></p>
                                <p><a href='<?= Url::to(['/site/logout']) ?>' data-method='post'>Выход</a></p>
                            ">
                    <span class="glyphicon glyphicon-user"></span>
                </button>
            </div>
        <?php
        endif;


        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
                ['label' => 'Главная', 'url' => ['/']],

                !Yii::$app->user->isGuest ? (
                ['label' => 'Мои проекты', 'url' => ['/projects/index']]) : (''),

                Yii::$app->user->isGuest ? (
                ['label' => 'Войти', 'url' => ['/site/login']]) : (''),

                Yii::$app->user->isGuest ? (
                ['label' => 'Регистрация', 'url' => ['/site/singup']]) : (''),
            ],
        ]);
        NavBar::end();
        ?>

    </div>


    <div class="container">

        <div class="arrow">

            <div id="arrow-container">

                <?= Html::a('1.<br>Генерация<br>ГЦС', Url::to(['target-segment']), ['class' => 'arrowlist'])?>

                <?= Html::a('2.<br>Генерация<br>ГПС', Url::to(['segment-problems']), ['class' => 'arrowlist'])?>

                <?= Html::a('3.<br>Подтвержд<br>ение ГПС', Url::to(['problem-confirmation']), ['class' => 'arrowlist'])?>

                <?= Html::a('4.<br>Разработка<br>ГЦП', Url::to(['value-proposition']), ['class' => 'arrowlist'])?>

                <?= Html::a('5.<br>Подтвержд<br>ение ГЦП', Url::to(['offer-confirmation']), ['class' => 'arrowlist'])?>

                <?= Html::a('6.<br>Разработка<br>ГMVP', Url::to(['development-mvp']), ['class' => 'arrowlist'])?>

                <?= Html::a('7.<br>Подтвержд<br>ение ГMVP', Url::to(['mvp-confirmation']), ['class' => 'arrowlist'])?>

                <?= Html::a('8.<br>Генерация<br>бизнес-модели', Url::to(['business-model']), ['class' => 'arrowlist'])?>

            </div>

        </div>

        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>

        <?php if( Yii::$app->session->hasFlash('success') ): ?>
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo Yii::$app->session->getFlash('success'); ?>
            </div>
        <?php endif;?>

        <?php if( Yii::$app->session->hasFlash('error') ): ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo Yii::$app->session->getFlash('error'); ?>
            </div>
        <?php endif;?>

        <?= $content ?>

    </div>
</div>


<script type="text/javascript">
    try{
        var el=document.getElementById('arrow-container').getElementsByTagName('a');
        var url=document.location.href;
        for(var i=0;i<el.length; i++){
            if (url==el[i].href){
                el[i].className += ' act';
            };
        };
    }catch(e){}
</script>


<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
