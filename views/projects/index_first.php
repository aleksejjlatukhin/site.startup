<?php

use yii\helpers\Html;
use app\models\User;
use yii\widgets\ActiveForm;

$this->title = 'Проекты';
$this->registerCssFile('@web/css/projects-index-style.css');
$this->registerCssFile('@web/css/methodological-guide-style.css');

?>

<div class="projects-index">

    <div class="methodological-guide">

        <!--1. Формулировка проекта-->
        <h3 class="header-text" id="draft_statement"><span>Формулировка проекта</span></h3>

        <div class="row container-fluid">
            <div class="col-md-12">
                <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>
                    <?=  Html::a( '<div class="new_hypothesis_link_block"><div>' . Html::img(['@web/images/icons/add_vector.png'], ['style' => ['width' => '35px']]) . '</div><div style="padding-left: 20px;">Добавить проект</div></div>', ['/projects/get-hypothesis-to-create', 'id' => $user->id],
                        ['id' => 'showHypothesisToCreate', 'class' => 'new_hypothesis_link_plus pull-left']
                    ); ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="container-list">

            <div class="simple-block">
                <p>
                    <span>Задача:</span>
                    Сформулировать цель. Описать проект.
                </p>
                <p>
                    <span>Результат:</span>
                    Заполненная форма на платформе Spaccel.ru.
                </p>
            </div>

            <p>
                В данном разделе необходимо сформулировать и описать проект, включая наименование, краткое название,
                цель проекта для реализации бизнес идеи (не обязательно коммерческой). На страницу можно войти при наличии подтвержденного аккаунта.
            </p>

            <p class="bold">Примеры целей проектов в зависимости от стартовой позиции и их цели:</p>

            <div class="container-text">
                <p class="bold"><span>А.</span>Трансфер технологии:</p>
            </div>

            <p>
                        <span class="bold blue">
                            Право на использование базовой технологии
                        </span>
                (способа) - имеется. Это означает, что вы правообладатель интеллектуальной собственности (<span class="bold">ИС</span>) или обладаете лицензионным
                соглашением (с исключительным или неисключительным правом) на использование интеллектуальной собственности.
            </p>

            <p><span class="bold blue">Наличие образца/продукта</span> &#8212; <span class="bold">нет</span>.</p>

            <p>
                <span class="bold blue">Цель проекта:</span> Найти применение ИС в реальном секторе экономики или других сферах деятельности человека (например, социальная сфера).
            </p>

            <p><span class="bold blue">Задачи</span>, которые необходимо решить для достижения поставленной цели:</p>

            <div class="container-text">
                <ul>
                    <li class="pl-15">
                        Разработать целевой рыночный сегмент, у которого есть потребность в приобретении продукта с характеристиками,
                        которые можно реализовать с помощью имеющейся в распоряжении, ИС на технологию (способ).
                    </li>
                    <li class="pl-15">
                        Разработать продукт, который будет конкурентно востребован целевым рыночным сегментом.
                    </li>
                </ul>
            </div>

            <p>
                <span class="bold blue">Пример проекта:</span>
                Есть идея использовать технологию, имеющуюся в вашем распоряжении, для конкурентного решения конкретной задачи,
                воплощенной в конкретном устройстве или материале для конкретного рыночного сегмента, а именно:
            </p>

            <div class="container-text">
                <ul>
                    <li class="pl-15">
                        Идея использовать know how рецептуры и режимов смешивания компонентов материалов
                        для получения корпусных деталей малых серий из двух компонентного полиуретана.
                    </li>
                    <li class="pl-15">
                        Идея использовать разработанное программное обеспечение, предлагающее
                        применение технологии VR/AR для обучения врачей проведению хирургических операций.
                    </li>
                </ul>
            </div>

            <div class="container-text">
                <p class="bold"><span>В.</span>Бизнес проект:</p>
            </div>

            <p><span class="bold blue">Право на использование базовой технологии</span> (способа) - отсутствует. Более того идея бизнеса не предусматривает наличие ИС.</p>
            <p><span class="bold blue">Цель проекта:</span> Разработать продукт (услугу), который будет востребован целевым сегментом рынка.</p>
            <p><span class="bold blue">Задачи</span>, которые необходимо решить для достижения поставленной цели:</p>

            <div class="container-text">
                <ul>
                    <li class="pl-15">Разработать целевой рыночный сегмент, у которого есть потребность в приобретении какого-либо продукта (услуги).</li>
                    <li class="pl-15">Найти поставщика/партнера продукта (услуги) или разработать новый продукт (услугу), привлекая партнеров, который будет конкурентно востребован целевым рыночным сегментом.</li>
                </ul>
            </div>

            <p><span class="bold blue">Пример проекта:</span> Организация сети проката скутеров для отдыхающих в парках; организация культурных программ в домах отдыха.</p>

        </div>

    </div>

    <div class="form_authors" style="display: none;">

        <?php
        $form = ActiveForm::begin([
            'id' => 'form_authors'
        ]); ?>

        <div class="form_authors_inputs">

            <div class="row row-author row-author-" style="margin-bottom: 15px;">

                <?= $form->field($new_author, "[0]fio", [
                    'template' => '<div class="col-md-12" style="padding-left: 20px; margin-top: 15px;">{label}</div><div class="col-md-12" style="margin-bottom: 15px;">{input}</div>'
                ])->textInput([
                    'maxlength' => true,
                    'required' => true,
                    'id' => 'author_fio-',
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                    'autocomplete' => 'off'
                ]); ?>

                <?= $form->field($new_author, "[0]role", [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12" style="margin-bottom: 15px;">{input}</div>'
                ])->textInput([
                    'maxlength' => true,
                    'required' => true,
                    'id' => 'author_role-',
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                    'autocomplete' => 'off'
                ]); ?>

                <?= $form->field($new_author, "[0]experience", [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12" style="margin-bottom: 15px;">{input}</div>'
                ])->textarea([
                    'rows' => 2,
                    'id' => 'author_experience-',
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                ]) ?>

                <div class="col-md-12">

                    <?= Html::button('Удалить автора', [
                        'id' => 'remove-author-',
                        'class' => "remove-author btn btn-default",
                        'style' => [
                            'display' => 'flex',
                            'align-items' => 'center',
                            'justify-content' => 'center',
                            'background' => '#E0E0E0',
                            'color' => '#FFFFFF',
                            'width' => '200px',
                            'height' => '40px',
                            'font-size' => '24px',
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
<?php $this->registerJsFile('@web/js/project_index.js'); ?>
