<?php

use yii\helpers\Html;
use app\models\User;

$this->title = 'Разработка MVP';
$this->registerCssFile('@web/css/mvp-index-style.css');
$this->registerCssFile('@web/css/methodological-guide-style.css');

?>

<div class="mvp-index">

    <div class="methodological-guide">

        <!--8. Этап 7. Разработка MVP-->
        <h3 class="header-text" id="mvp_development"><span>Разработка MVP</span></h3>

        <div class="row container-fluid">
            <div class="col-md-12">
                <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>
                    <?=  Html::a( '<div class="new_hypothesis_link_block"><div>' . Html::img(['@web/images/icons/add_vector.png'], ['style' => ['width' => '35px']]) . '</div><div style="padding-left: 20px;">Добавить продукт MVP</div></div>',
                        ['/confirm-gcp/data-availability-for-next-step', 'id' => $confirmGcp->id],
                        ['id' => 'checking_the_possibility', 'class' => 'new_hypothesis_link_plus pull-left']
                    ); ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="container-list">
            <p>Для тестирования вашего продукта или технологии на группе первых пользователей возможно создание «минимально жизнеспособного
                продукта» (Minimal viable product – MVP) - прототипа с минимальным требуемым функционалом.</p>
            <p>При помощи MVP можно получить необходимую обратную связь от пользователей о продукте для формирования дальнейшей траектории его развития.</p>
            <p>Создайте макет или прототип «минимально жизнеспособного продукта», который может быть реализован в виде слайдов, скетча
                интерфейса, целевой страницы (landing page), ролика с демо-версией продукта и т.д.</p>
        </div>

    </div>

    <!--Модальные окна-->
    <?= $this->render('modal'); ?>

</div>

<!--Подключение скриптов-->
<?php $this->registerJsFile('@web/js/hypothesis_mvp_index.js'); ?>