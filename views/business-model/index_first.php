<?php

use yii\helpers\Html;
use app\models\User;

$this->title = 'Генерация бизнес-модели';
$this->registerCssFile('@web/css/business-model-index-style.css');
$this->registerCssFile('@web/css/methodological-guide-style.css');

?>

<div class="business-model-index">

    <div class="methodological-guide">

        <h3 class="header-text"><span>Разработка бизнес-модели</span></h3>

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

        <div class="container-list">

            <p>Целью, а также заключительным этапом работы в рамках нашего Акселератора является генерация Бизнес-модели. Мы предлагаем
                использовать Канву бизнес-модели (англ. Business model canvas), разработанную авторами Александром Остервальдером и Ивом Пинье.</p>

            <p>Канва бизнес-модели состоит из 9 блоков, которые могут быть объединены в 4 группы, каждый из блоков описывает
                свою часть бизнес-модели организации, а именно: ключевые партнеры, ключевые активности, достоинства и предложения,
                отношения с заказчиком, пользовательские сегменты, ключевые ресурсы, каналы поставки, структура затрат и источники доходов.</p>

            <p>На основании ранее заполненных форм система автоматически сгенерирует вариант Бизнес-модели,
                который при необходимости вы сможете отредактировать.</p>

            <p>Наличие решения к цепочке «Клиент – Проблема – Ценностное предложение» означает, что Бизнес-модель найдена.
                Подтверждением актуальности Бизнес-модели может также служить первая произведенная продажа.</p>

            <div>Ниже приведены материалы по теме «Канва бизнес-модели»:</div>
            <div class="container-text">
                <ol>
                    <li class="pl-15">
                        A Better Way to Think About Your Business Model, A. Osterwalder, Harvard Business Review, 06.05.2013
                        <div><?= Html::a('https://hbr.org/2013/05/a-better-way-to-think-about-yo', 'https://hbr.org/2013/05/a-better-way-to-think-about-yo', ['target' => '_blank'])?></div>
                    </li>
                    <li class="pl-15">
                        Business Model Canvas. Строим модель бизнеса на примере Uber и Netflix, А. Ница, Skillbox, 21.07.21
                        <div><?= Html::a('https://skillbox.ru/media/management/business-model-canvas/', 'https://skillbox.ru/media/management/business-model-canvas/', ['target' => '_blank'])?></div>
                    </li>
                    <li class="pl-15">
                        Как построить работающую бизнес-модель, Н. Корзинов, Rusbase, 28.06.2018
                        <div><?= Html::a('https://rb.ru/opinion/biznes-model/', 'https://rb.ru/opinion/biznes-model/', ['target' => '_blank'])?></div>
                    </li>
                </ol>
            </div>

            <p>
                По итогам формирования Бизнес-модели в системе Spaccel.ru еще раз внимательно просмотрите заполненные блоки, возможно, некоторые потребуют доработки, другие же потребуют
                внесения информации. По сути, вы увидите упрощенную модель вашего будущего бизнеса, взаимосвязь всех бизнес-процессов, схему функционирования компании.
            </p>

            <p>
                Проанализируйте получившуюся картину на предмет логичности и жизнеспособности, сделайте вывод относительно перспектив продукта и бизнеса. Если какой-либо из блоков «выпадает»
                из общей картины, вам необходимо вернуться назад (совершить pivot) и отрегулировать параметры блока (блоков) или, при необходимости, продукта в целом.
            </p>

        </div>

    </div>

    <!--Модальные окна-->
    <?= $this->render('modal'); ?>

</div>

<!--Подключение скриптов-->
<?php $this->registerJsFile('@web/js/business_model_index.js'); ?>
