<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;


$form = ActiveForm::begin([
    'id' => 'form_user_singup',
    'action' => Url::to(['/site/singup']),
    'options' => ['class' => 'g-py-15'],
    'errorCssClass' => 'u-has-error-v1',
    'successCssClass' => 'u-has-success-v1-1',
]);


    echo $form->field($formRegistration, 'second_name', [
        'template' => '<div style="padding-left: 15px; padding-bottom: 5px;">Фамилия</div><div>{input}</div>'
    ])->textInput([
        'maxlength' => 50,
        'minlength' => 2,
        'required' => true,
        'class' => 'style_form_field_respond form-control',
        'placeholder' => '',
        'autocomplete' => 'off'
    ]);


    echo $form->field($formRegistration, 'first_name', [
        'template' => '<div style="padding-left: 15px; padding-bottom: 5px;">Имя</div><div>{input}</div>'
    ])->textInput([
        'maxlength' => 50,
        'minlength' => 2,
        'required' => true,
        'class' => 'style_form_field_respond form-control',
        'placeholder' => '',
        'autocomplete' => 'off'
    ]);


    echo $form->field($formRegistration, 'middle_name', [
        'template' => '<div style="padding-left: 15px; padding-bottom: 5px;">Отчество</div><div>{input}</div>'
    ])->textInput([
        'maxlength' => 50,
        'minlength' => 2,
        'required' => true,
        'class' => 'style_form_field_respond form-control',
        'placeholder' => '',
        'autocomplete' => 'off'
    ]);


    echo $form->field($formRegistration, 'email', [
        'template' => '<div style="padding-left: 15px; padding-bottom: 5px;">Email</div><div>{input}</div>'
    ])->textInput([
        'type' => 'email',
        'required' => true,
        'maxlength' => true,
        'class' => 'style_form_field_respond form-control',
        'placeholder' => '',
        'autocomplete' => 'off'
    ]);


    echo $form->field($formRegistration, 'telephone', [
        'template' => '<div style="padding-left: 15px; padding-bottom: 5px;">Телефон</div><div>{input}</div>'
    ])->textInput([
        'maxlength' => 50,
        'minlength' => 6,
        'class' => 'style_form_field_respond form-control',
        'placeholder' => '',
        'autocomplete' => 'off'
    ]);


    echo $form->field($formRegistration, 'username', [
        'template' => '<div style="padding-left: 15px; padding-bottom: 5px;">Логин</div><div>{input}</div>'
    ])->textInput([
        'maxlength' => 32,
        'minlength' => 3,
        'required' => true,
        'class' => 'style_form_field_respond form-control',
        'placeholder' => 'Введите от 3 до 32 символов',
        'autocomplete' => 'off'
    ]);


    echo $form->field($formRegistration, 'password', [
        'template' => '<div style="padding-left: 15px; padding-bottom: 5px;">Пароль</div><div>{input}</div>'
    ])->passwordInput([
        'maxlength' => 32,
        'minlength' => 6,
        'required' => true,
        'class' => 'style_form_field_respond form-control',
        'placeholder' => 'Введите от 6 до 32 символов',
        'autocomplete' => 'off'
    ]);


    echo $form->field($formRegistration, 'education', [
        'template' => '<div style="padding-left: 15px; padding-bottom: 5px;">Образование</div><div>{input}</div>'
    ])->textInput([
        'maxlength' => true,
        'minlength' => 2,
        'required' => true,
        'class' => 'style_form_field_respond form-control',
        'placeholder' => 'Укажите наименование ВУЗа(ов)',
        'autocomplete' => 'off'
    ]);


    echo $form->field($formRegistration, 'academic_degree', [
        'template' => '<div style="padding-left: 15px; padding-bottom: 5px;">Ученая степень, звание</div><div>{input}</div>'
    ])->textInput([
        'maxlength' => true,
        'minlength' => 2,
        'required' => true,
        'class' => 'style_form_field_respond form-control',
        'placeholder' => 'Кандидат экономических наук и т.д.',
        'autocomplete' => 'off'
    ]);


    echo $form->field($formRegistration, 'position', [
        'template' => '<div style="padding-left: 15px; padding-bottom: 5px;">Должность</div><div>{input}</div>'
    ])->textInput([
        'maxlength' => true,
        'minlength' => 2,
        'required' => true,
        'class' => 'style_form_field_respond form-control',
        'placeholder' => 'Должность в компании',
        'autocomplete' => 'off'
    ]);


    echo $form->field($formRegistration, 'scope_professional_competence', [
        'template' => '<div style="padding-left: 15px; padding-bottom: 5px;">Сфера профессиональной компетенции</div><div>{input}</div>'
    ])->textarea([
        'row' => 2,
        'maxlength' => true,
        'minlength' => 2,
        'required' => true,
        'class' => 'style_form_field_respond form-control',
        'placeholder' => 'Область(и) ваших знаний для оказания экспертных услуг',
        'autocomplete' => 'off'
    ]);


    echo $form->field($formRegistration, 'publications', [
        'template' => '<div style="padding-left: 15px; padding-bottom: 5px;">Научные публикации</div><div>{input}</div>'
    ])->textarea([
        'row' => 2,
        'maxlength' => true,
        'minlength' => 2,
        'required' => true,
        'class' => 'style_form_field_respond form-control',
        'placeholder' => 'Укажите наиболее значимые на ваш взгляд',
        'autocomplete' => 'off'
    ]);


    echo $form->field($formRegistration, 'implemented_projects', [
        'template' => '<div style="padding-left: 15px; padding-bottom: 5px;">Реализованные проекты</div><div>{input}</div>'
    ])->textarea([
        'row' => 2,
        'maxlength' => true,
        'minlength' => 2,
        'required' => true,
        'class' => 'style_form_field_respond form-control',
        'placeholder' => 'Дайте краткое описание с указанием компаний/проектов и достигнутых результатов',
        'autocomplete' => 'off'
    ]);


    echo $form->field($formRegistration, 'role_in_implemented_projects', [
        'template' => '<div style="padding-left: 15px; padding-bottom: 5px;">Роль в реализованных проектах</div><div>{input}</div>'
    ])->textarea([
        'row' => 2,
        'maxlength' => true,
        'minlength' => 2,
        'required' => true,
        'class' => 'style_form_field_respond form-control',
        'placeholder' => 'Комментарий о вашей роли в реализованных проектах',
        'autocomplete' => 'off'
    ]); ?>


    <div class="block-exist-agree">

        <?= $form->field($formRegistration, 'exist_agree', ['template' => '{input}{label}'])
            ->checkbox(['value' => 1, 'checked ' => true], false); ?>

        <?= Html::a('Я согласен с настоящей Политикой конфиденциальности и условиями обработки моих персональных данных',
            ['/site/confidentiality-policy'], [
                'target' => '_blank',
                'title' => 'Ознакомиться с настоящей Политикой конфиденциальности и условиями обработки моих персональных данных',
                'style' => ['color' => '#FFFFFF', 'line-height' => '18px']
            ]
        ); ?>

    </div>


    <div class="block-submit-registration">

        <?= Html::submitButton('Зарегистрировать меня', [
            'class' => 'btn btn-default',
            'name' => 'singup-button',
            'style' => [
                'margin-top' => '10px',
                'background' => '#E0E0E0',
                'color' => '4F4F4F',
                'border-radius' => '8px',
                'width' => '220px',
                'height' => '40px',
                'font-size' => '16px',
                'font-weight' => '700'
            ]
        ]); ?>

    </div>


<?php ActiveForm::end(); ?>


