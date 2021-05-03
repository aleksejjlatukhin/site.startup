<?php

use yii\helpers\Html;
use app\models\User;

$this->title = 'Генерация бизнес-модели';
$this->registerCssFile('@web/css/business-model-index-style.css');
$this->registerCssFile('@web/css/methodological-guide-style.css');

?>

<div class="business-model-index">

    <div class="methodological-guide">

        <!--10. Этап 9. Разработка бизнес-модели-->
        <h3 class="header-text"id="developing_business_model"><span>Разработка бизнес-модели</span></h3>

        <div class="row container-fluid">
            <div class="col-md-12">
                <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>
                    <?=  Html::a( '<div class="new_hypothesis_link_block"><div>' . Html::img(['@web/images/icons/add_vector.png'], ['style' => ['width' => '35px']]) . '</div><div style="padding-left: 20px;">Бизнес-модель</div></div>',
                            ['/confirm-mvp/data-availability-for-next-step', 'id' => $confirmMvp->id],
                            ['id' => 'checking_the_possibility', 'class' => 'new_hypothesis_link_plus pull-left']
                        ); ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="container-list">Разработка БМ по А. Остервальдеру.</div>

    </div>

    <!--Модальные окна-->
    <?= $this->render('modal'); ?>

</div>

<!--Подключение скриптов-->
<?php $this->registerJsFile('@web/js/business_model_index.js'); ?>
