<?php

use yii\helpers\Html;
use app\models\User;
use yii\widgets\ActiveForm;

$this->title = 'Генерация гипотез проблем сегмента';
$this->registerCssFile('@web/css/problem-index-style.css');
$this->registerCssFile('@web/css/methodological-guide-style.css');

?>

<div class="generation-problem-index">

    <div class="methodological-guide">

        <!--4. Этап 3. Генерация гипотез проблем сегментов-->
        <h3 class="header-text" id="generating_hypotheses_for_segment_problems"><span>Генерация гипотез проблем сегментов</span></h3>

        <div class="row container-fluid">
            <div class="col-md-12">
                <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>
                    <?=  Html::a( '<div class="new_hypothesis_link_block"><div>' . Html::img(['@web/images/icons/add_vector.png'], ['style' => ['width' => '35px']]) . '</div><div style="padding-left: 20px;">Добавить проблему</div></div>',
                        ['/confirm-segment/data-availability-for-next-step', 'id' => $confirmSegment->id],
                        ['id' => 'checking_the_possibility', 'class' => 'new_hypothesis_link_plus pull-left']
                    ); ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="container-list">

            <div class="simple-block">
                <p>
                    <span>Задача:</span>
                    Сформировать список гипотез, которые должны быть протестированы на следующей стадии.
                </p>
                <p>
                    <span>Результат:</span>
                    Одна или несколько сформулированных и проверенных гипотез, внесенные в форму на платформе Spaccel.ru.
                </p>
            </div>

            <br>

            <p>
                Исходной информацией для формулировки гипотез проблем сегментов является информационная таблица, где собраны
                все «Варианты проблем» по материалам всех респондентов. Перед формулировкой гипотезы проблемы проанализируйте
                результаты, собранные в поле «Варианты проблем».
            </p>

            <div class="bold">Общие рекомендации как анализировать результаты ГИ:</div>

            <div class="container-text">
                <ol>
                    <li><span class="pl-15">Сводим все в таблицу и смотрим, где есть схожие и противоположные ответы на вопросы.</span></li>
                    <li><span class="pl-15">Ищем одинаковые. Тогда мы выясняем, есть ли у пользователей схожая проблема.</span></li>
                    <li><span class="pl-15">Если в процессе проведения интервью с одним респондентом выявили проблему, то желательно включить в сценарий проведения с другими респондентами вопрос, есть ли у них выявленная проблема.</span></li>
                    <li>
                        <span class="pl-15">Помните, что проблема может лежать в другой плоскости и не в том месте, про которое спрашивали.</span>
                        <p class="mt-10 pl-25">
                            <span class="bold">Пример:</span>
                            Есть стартап, который помогает делать on-line трансляции в интернете. Это делают, потому что есть ожидание,
                            что их никто не умеет делать технически. Поэтому их мало. Но реальность – нет аудитории. Нет интереса.
                            А проблема в том, что просто не умеют привлечь аудиторию.
                        </p>
                    </li>
                    <li>
                        <span class="pl-15">Может быть выявлена проблема, связанная с тем, что у собеседников разный лексикон. Т.е. иногда будущий пользователь системы не может правильно определить, что ему нужно, потому что он не владеет лексиконом.</span>
                        <p class="mt-10 pl-25">
                            <span class="bold">Пример:</span>
                            Пользователям не нужна CRM система, а нужна система, как они говорят, учет продаж,  что у них с воронкой продаж,
                            контроль менеджеров. Т.е. они называют CRM другими словами.
                        </p>
                    </li>
                </ol>
            </div>

            <p class="bold blue">
                Сформулируйте гипотезы проблемы для проверки. Правильная гипотеза (потребности, проблемы) состоит из: предположения,
                действия для проверки, метрики результата и вывода.
            </p>

            <p class="bold">Примеры:</p>

            <p>
                <span class="bold">Сетевой магазин.</span>
                Гипотеза: минимум 20% офисов продаж узнают о новых акциях с задержкой больше трех дней, либо вообще не узнают — из-за
                этого продажи не растут, либо падают. Чтобы найти решение, мы проведем интервью с менеджерами из десяти точек продаж
                в одном регионе. Если семеро и больше подтвердят гипотезу, то сделаем пилотный запуск системы быстрого оповещения точек
                продаж об акциях через мессенджер, после чего проверим, как изменился уровень продаж.
            </p>

            <p>
                <span class="bold">Магазин книг на английском языке.</span>
                Гипотеза: читатели испытывают трудности при заказе книг на Amazon из-за долгой и дорогой доставки. Мы проведем опрос
                среди людей, которые читают на английском. Если больше 70% ответят о долгой и/или дорогой доставке книг с Amazon, гипотезу
                считаем подтвержденной и запускаем тестовую рекламную кампанию на сайт по продаже книг с быстрой и недорогой доставкой книг
                в оригинале.
            </p>

        </div>

    </div>

    <div class="formExpectedResults" style="display: none;">

        <?php
        $form = ActiveForm::begin([
            'id' => 'formExpectedResults'
        ]); ?>

        <div class="formExpectedResults_inputs">

            <div class="row container-fluid rowExpectedResults rowExpectedResults-" style="margin-bottom: 15px;">

                <div class="col-md-6 field-EXR">

                    <?= $form->field($formModel, "_expectedResultsInterview[0][question]", ['template' => '{input}'])->textarea([
                        'rows' => 2,
                        'maxlength' => true,
                        'required' => true,
                        'placeholder' => 'Напишите вопрос',
                        'id' => '_expectedResults_question-',
                        'class' => 'style_form_field_respond form-control',
                    ]); ?>

                </div>

                <div class="col-md-6 field-EXR">

                    <?= $form->field($formModel, "_expectedResultsInterview[0][answer]", ['template' => '{input}'])->textarea([
                        'rows' => 2,
                        'maxlength' => true,
                        'required' => true,
                        'placeholder' => 'Напишите ответ',
                        'id' => '_expectedResults_answer-',
                        'class' => 'style_form_field_respond form-control',
                    ]); ?>

                </div>

                <div class="col-md-12">

                    <?= Html::button('Удалить вопрос/ответ', [
                        'id' => 'remove-expectedResults-',
                        'class' => "remove-expectedResults btn btn-default",
                        'style' => [
                            'display' => 'flex',
                            'align-items' => 'center',
                            'justify-content' => 'center',
                            'width' => '170px',
                            'height' => '40px',
                            'font-size' => '16px',
                            'border-radius' => '8px',
                        ]
                    ]); ?>
                </div>
            </div>
        </div>
        <?php
        ActiveForm::end();
        ?>
    </div>

    <!--Модальные окна-->
    <?= $this->render('modal'); ?>

</div>

<!--Подключение скриптов-->
<?php $this->registerJsFile('@web/js/hypothesis_problem_index.js'); ?>