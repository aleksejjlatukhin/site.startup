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


    echo $form->field($formRegistration, 'role')
        ->hiddenInput()
        ->label(false);


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
    ]); ?>


    <div class="block-exist-agree">

        <?= $form->field($formRegistration, 'exist_agree', ['template' => '{input}{label}'])
            ->checkbox(['value' => 1, 'checked ' => true, 'class' => 'custom-checkbox'], false); ?>

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