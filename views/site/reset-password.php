<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\ResetPasswordForm */
/* @var $form ActiveForm */
$this->title = 'Востановление пароля';

?>
<div class="site-resetPassword">


    <div class="background_for_main_page">

        <div class="row top_line_text_main_page">
            <div class="col-md-12">Инструмент для Прокачки бизнес-идеи, поиска потребительского сегмента, формирования продукта</div>
        </div>

        <div class="content_main_page">


            <?php if (Yii::$app->user->isGuest) : ?>


                <?php if ($model->exist === true) : ?>


                <div class="row style_form_reset_password">

                    <div class="col-md-12 text-center" style="font-size: 20px; margin: 25px 0 60px 0;">Восстановление пароля</div>

                    <div class="col-md-12 text-center" style=" margin: 45px 0 10px 0;">Введите в поле новый пароль.</div>

                    <?php $form = ActiveForm::begin([
                        'id' => 'reset_password_form',
                        'action' => Url::to(['/site/reset-password', 'key' => Yii::$app->request->get('key')]),
                        'options' => ['class' => 'g-py-15'],
                        'errorCssClass' => 'u-has-error-v1',
                        'successCssClass' => 'u-has-success-v1-1',
                    ]); ?>

                    <div class="col-md-12">

                        <?= $form->field($model, 'password')->label(false)
                            ->passwordInput([
                                'minlength' => 6,
                                'maxlength' => 32,
                                'required' => true,
                                'class' => 'style_form_field_respond form-control',
                                'placeholder' => 'Введите от 6 до 32 символов',
                                'autocomplete' => 'off'
                            ]) ?>

                    </div>

                    <div class="col-md-12 text-center" style="position: absolute; bottom: 0; height: 70px;">
                        <?= Html::submitButton('Сохранить', [
                            'class' => 'btn btn-default',
                            'style' => [
                                'background' => '#E0E0E0',
                                'color' => '4F4F4F',
                                'border-radius' => '8px',
                                'width' => '140px',
                                'height' => '40px',
                                'font-size' => '16px',
                                'font-weight' => '700'
                            ]
                        ]) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>

                <?php else : ?>

                <?php //echo 'Ключ просрочен';?>


                <div class="row style_password_recovery_for_email">

                    <div class="col-md-12 text-center" style="font-size: 20px; margin: 25px 0 45px 0;">Восстановление пароля</div>

                    <div class="col-md-12 text-center" style="margin: 15px 0;">Ссылка на восстановление пароля была просрочена или изменена.</div>

                    <?php $form = ActiveForm::begin([
                        'id' => 'form_send_email',
                        'action' => Url::to(['/site/send-email']),
                        'options' => ['class' => 'g-py-15'],
                        'errorCssClass' => 'u-has-error-v1',
                        'successCssClass' => 'u-has-success-v1-1',
                    ]); ?>

                    <div class="col-md-12" style="margin-top: 5px;">

                        <?= $form->field($model_send_email, 'email')->label(false)->input('email',[
                            'required' => true,
                            'class' => 'style_form_field_respond form-control',
                            'placeholder' => 'Введите email',
                            'autocomplete' => 'off'
                        ]) ?>

                    </div>

                    <div class="col-md-12 text-center">Для повторной отправки письма введите адрес электронной почты (указанный при регистрации).</div>

                    <div class="col-md-12 text-center" style="position: absolute; bottom: 0; height: 70px;">

                        <?= Html::submitButton('Отправить', [
                            'class' => 'btn btn-default',
                            'name' => 'send-email-button',
                            'style' => [
                                'background' => '#E0E0E0',
                                'color' => '4F4F4F',
                                'border-radius' => '8px',
                                'width' => '170px',
                                'height' => '40px',
                                'font-size' => '16px',
                                'font-weight' => '700'
                            ]
                        ]) ?>

                    </div>

                    <?php ActiveForm::end(); ?>

                </div>


                <div class="row style_answer_for_password_recovery">

                    <div class="col-md-12 text-center title" style="font-size: 20px; margin: 25px 0 45px 0;"></div>

                    <div class="col-md-12 text-center text" style="margin: 45px 0 0 0;"></div>

                    <div class="col-md-12 text-center link_back" style="position: absolute; bottom: 0; height: 45px;">
                        <?= Html::a('Вернуться назад',['#'], ['onclick' => 'return false', 'class' => 'link_singup', 'id' => 'go_back_password_recovery_for_email',]);?>
                    </div>

                </div>

                <?php endif; ?>

            <?php endif;?>


            <div class="content_main_page_block_text">

                <div>
                    <h1 class="top_title_main_page">Акселератор стартап-проектов</h1>
                </div>

                <div>
                    <div class="bottom_title_main_page">Customer Development <span>ШАГ</span> ЗА <span>ШАГОМ</span></div>
                </div>

            </div>

        </div>

    </div>

</div><!-- site-resetPassword -->


<?php


$script = "

    //Вернуться к отправке почты для восстановления пароля
    $('body').on('click', '#go_back_password_recovery_for_email', function(){
        $('.style_answer_for_password_recovery').hide();
        $('.style_password_recovery_for_email').show();
    });

    //Отправка формы для получения письма на почту для сены пароля
    $('body').on('beforeSubmit', '#form_send_email', function(e){
    
        var data = $(this).serialize();
        var url = $(this).attr('action');
        
        $.ajax({
        
            url: url,
            method: 'POST',
            data: data,
            cache: false,
            success: function(response){
                
                if(response['success']) {
                   
                    $('.style_password_recovery_for_email').hide();
                    $('.style_answer_for_password_recovery').find('.title').html(response['message']['title']);
                    $('.style_answer_for_password_recovery').find('.text').html(response['message']['text']);
                    $('.style_answer_for_password_recovery').find('.link_back').hide();  
                    $('.style_answer_for_password_recovery').show();
                }
                
                if(response['error']) {
                   
                    $('.style_password_recovery_for_email').hide();
                    $('.style_answer_for_password_recovery').find('.title').html(response['message']['title']);
                    $('.style_answer_for_password_recovery').find('.text').html(response['message']['text']);
                    $('.style_answer_for_password_recovery').find('.link_back').show();
                    $('.style_answer_for_password_recovery').show();
                }
            },
            error: function(){
                alert('Ошибка');
            }
        });
    
        e.preventDefault();

        return false;
    });
    
";
$position = \yii\web\View::POS_READY;
$this->registerJs($script, $position);

?>