<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Cообщения | Администратор';
?>

    <br>
    <div class="row">

        <div class="col-md-8" style="border-right: 1px solid #ccc; padding-right: 0;">

            <div class="data-message">

                <div style="padding: 10px 0; margin-top: -20px;border-bottom: 1px solid #ccc; text-align: center;">

                    <span style="margin-left: -100px; padding-right: 10px;">

                        <?php if ($admin['avatar_image']) : ?>

                            <?= Html::img('/web/upload/user-'.$admin->id.'/avatar/'.$admin->avatar_image, ['width' => '40px', 'height' => '40px', 'class' => 'round-avatar'])?>

                        <?php else : ?>

                            <?= Html::img('/images/avatar/default.jpg', ['width' => '40px', 'height' => '40px', 'class' => 'round-avatar'])?>

                        <?php endif; ?>

                    </span>

                    <span style="font-weight: 700;">
                        Администратор
                    </span>

                </div>

                <div class="chat">

                    <?php if (!empty($messages)) : ?>

                        <div class="data-chat">

                            <?php foreach ($messages as $message) : ?>

                                <?php if ($message->sender_id != $user->id) : ?>

                                    <div class="row message message_id_<?= $message->id;?>" style="margin: 0;">
                                        <div class="send">
                                            <div class="send_data">
                                                <div style="display: flex;">

                                                    <div style="padding-right: 15px;">

                                                        <?php if ($admin['avatar_image']) : ?>

                                                            <?= Html::img('/web/upload/user-'.$admin->id.'/avatar/'.$admin->avatar_image, ['width' => '50px', 'height' => '50px', 'class' => 'round-avatar'])?>

                                                        <?php else : ?>

                                                            <?= Html::img('/images/avatar/default.jpg', ['width' => '50px', 'height' => '50px', 'class' => 'round-avatar'])?>

                                                        <?php endif; ?>

                                                    </div>

                                                    <div style="padding-top: 5px;">

                                                        <div style="font-size: 13px; font-weight: 700;">
                                                        <span style="padding-right: 30px;">
                                                            Администратор
                                                        </span>
                                                        </div>

                                                        <div style="font-size: 13px; font-weight: 700;">
                                                        <span style="padding-right: 5px; font-size: 12px;">
                                                        <?= date('H:i', $message['updated_at']); ?>
                                                        </span>

                                                            <span style="font-size: 12px;">
                                                        <?= date('d.m.Y', $message['updated_at']); ?>
                                                        </span>
                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="message-description" style="padding: 10px 5px;">
                                                    <?= $message->description; ?>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                <?php else : ?>

                                    <div class="row message message_id_<?= $message->id;?>" style="margin: 0;">
                                        <div class="income">
                                            <div class="income_data">
                                                <div style="display: flex;">

                                                    <div style="padding-right: 15px;">

                                                        <?php if ($user['avatar_image']) : ?>

                                                            <?= Html::img('/web/upload/user-'.$user->id.'/avatar/'.$user->avatar_image, ['width' => '50px', 'height' => '50px', 'class' => 'round-avatar'])?>

                                                        <?php else : ?>

                                                            <?= Html::img('/images/avatar/default.jpg', ['width' => '50px', 'height' => '50px', 'class' => 'round-avatar'])?>

                                                        <?php endif; ?>

                                                    </div>

                                                    <div style="padding-top: 5px;">

                                                        <div style="font-size: 13px; font-weight: 700;">
                                                        <span style="padding-right: 30px;">
                                                            <?= $user->second_name . ' ' . $user->first_name . ' ' . $user->middle_name; ?>
                                                        </span>
                                                        </div>

                                                        <div style="font-size: 13px; font-weight: 700;">
                                                        <span style="padding-right: 5px; font-size: 12px;">
                                                            <?= date('H:i', $message['updated_at']); ?>
                                                        </span>

                                                            <span style="font-size: 12px;">
                                                            <?= date('d.m.Y', $message['updated_at']); ?>
                                                        </span>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="message-description" style="padding: 10px 5px;">
                                                    <?= $message->description; ?>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                <?php endif; ?>

                            <?php endforeach; ?>

                        </div>

                        <div class="create-message" style="margin: 15px 20px;">

                            <?php $form = ActiveForm::begin(['id' => 'create-message-admin']); ?>

                            <? $placeholder = 'Напишите сообщение' ?>

                            <?= $form->field($model, 'description')->label(false)->textarea(['rows' => 3, 'placeholder' => $placeholder]) ?>

                            <div class="form-group">

                                <?= Html::submitButton('Отправить', [
                                    'class' => 'btn btn-primary',
                                    'style' => [
                                        'font-weight' => '700',
                                        'font-size' => '13px',
                                    ]
                                ]) ?>

                            </div>

                            <?php ActiveForm::end(); ?>
                        </div>

                    <?php else : ?>

                        <div class="message not-message" style="display: flex">
                            <div class="income" style="margin: 0 auto; text-align: center;">
                                <div class="income_data">
                                    У Вас нет пока общих сообщений с данным пользователем...
                                </div>
                            </div>
                        </div>

                        <div class="data-chat"></div>

                        <div class="create-message" style="margin: 15px 20px; padding-top: 35vh;">

                            <?php $form = ActiveForm::begin(['id' => 'create-message-admin']); ?>

                            <? $placeholder = 'Напишите сообщение' ?>

                            <?= $form->field($model, 'description')->label(false)->textarea(['rows' => 3, 'placeholder' => $placeholder]) ?>

                            <div class="form-group">

                                <?= Html::submitButton('Отправить', [
                                    'class' => 'btn btn-primary',
                                    'style' => [
                                        'font-weight' => '700',
                                        'font-size' => '13px',
                                    ]
                                ]) ?>

                            </div>

                            <?php ActiveForm::end(); ?>
                        </div>

                    <?php endif; ?>


                </div>
            </div>
        </div>

        <div class="col-md-4">

            <p style="padding-bottom: 20px; border-bottom: 1px solid #ccc; margin-bottom: 0; font-weight: 700; padding-left: 10px;">
                Категории:
            </p>

            <p style="font-weight: 700; padding-left: 10px; color: green; padding-top: 20px;">
                Администратор
            </p>

        </div>

    </div>


<?php

$script = "

    //Блок прокрутки в сообщениях
     $(document).ready(function() {
         $('.chat').stop().animate({
           scrollTop: $('.chat')[0].scrollHeight
         }, 800);
         
     });
    
    
    
     $('form').on('beforeSubmit', function(e){
        
        var data = $(this).serialize();
        var messages = ".$messages.";
        
        $.ajax({
        
            url: '". Url::to(['view', 'id' => \Yii::$app->request->get('id')])."',
            method: 'POST',
            data: data,
            success: function(response){
                
                //Создаем время и дату в нужном формате
                var date =  + response.message['updated_at'];
                var newTime = new Date();
                var fullTime = ('0' + newTime.getHours(date)).slice(-2) + ':' + ('0' + newTime.getMinutes(date)).slice(-2);
                var newDate = new Date();
                var fullDate = ('0' + newDate.getDate(date)).slice(-2) + '.' + ('0' + (newDate.getMonth(date) + 1)).slice(-2) + '.' + newDate.getFullYear(date);
                
                
                
                
                if (messages.length > 1) {  //Если до отправки формы в беседе уже были сообщения
                
                    if (response.message['sender_id'] != response.user['id']) {
                
                        $('.data-chat').append('<\div class=\"row message message_id_' + response.message['id'] + '\" style=\"margin: 0;\"><\div class=\"send\"><\div class=\"send_data\"><\div style=\"display: flex;\"><\div style=\"padding-right: 15px;\"><\img class=\"round-avatar\" style=\"width: 50px; height: 50px;\" src='+ response.admin['avatar_image'] +' ><\/div><\div style=\"padding-top: 5px;\"><\div style=\"font-size: 13px; font-weight: 700;\"><\span style=\"padding-right: 30px;\">Администратор<\/span><\/div><\div style=\"font-size: 13px; font-weight: 700;\"><\span style=\"padding-right: 10px; font-size: 12px;\">' + fullTime + '<\/span><\span style=\"font-size: 12px;\">' + fullDate + '<\/span><\/div><\/div><\/div><\div class=\"message-description\" style=\"padding: 10px 5px;\">' + response.message['description'] + '<\/div><\/div><\/div>');
                    
                    }else {
                    
                        $('.data-chat').append('<\div class=\"row message message_id_' + response.message['id'] + '\" style=\"margin: 0;\"><\div class=\"income\"><\div class=\"income_data\"><\div style=\"display: flex;\"><\div style=\"padding-right: 15px;\"><\img class=\"round-avatar\" style=\"width: 50px; height: 50px;\" src='+ response.user['avatar_image'] +' ><\/div><\div style=\"padding-top: 5px;\"><\div style=\"font-size: 13px; font-weight: 700;\"><\span style=\"padding-right: 30px;\">' + response.user['second_name'] + ' ' + response.user['first_name'] + ' ' + response.user['middle_name'] + '<\/span><\/div><\div style=\"font-size: 13px; font-weight: 700;\"><\span style=\"padding-right: 10px; font-size: 12px;\">' + fullTime + '<\/span><\span style=\"font-size: 12px;\">' + fullDate + '<\/span><\/div><\/div><\/div><\div class=\"message-description\" style=\"padding: 10px 5px;\">' + response.message['description'] + '<\/div><\/div><\/div>');
                    }
                
                }else {  //Если до отправки формы в беседе не было сообщений
                
                    if (response.message['sender_id'] != response.user['id']) {
                    
                        $('.not-message').empty();
                        $('.create-message').css('padding-top', '0');    
                        $('.data-chat').append('<\div class=\"row message message_id_' + response.message['id'] + '\" style=\"margin: 0;\"><\div class=\"send\"><\div class=\"send_data\"><\div style=\"display: flex;\"><\div style=\"padding-right: 15px;\"><\img class=\"round-avatar\" style=\"width: 50px; height: 50px;\" src='+ response.admin['avatar_image'] +' ><\/div><\div style=\"padding-top: 5px;\"><\div style=\"font-size: 13px; font-weight: 700;\"><\span style=\"padding-right: 30px;\">Администратор<\/span><\/div><\div style=\"font-size: 13px; font-weight: 700;\"><\span style=\"padding-right: 10px; font-size: 12px;\">' + fullTime + '<\/span><\span style=\"font-size: 12px;\">' + fullDate + '<\/span><\/div><\/div><\/div><\div class=\"message-description\" style=\"padding: 10px 5px;\">' + response.message['description'] + '<\/div><\/div><\/div>');
                    
                    } else {
                    
                        $('.not-message').empty();
                        $('.create-message').css('padding-top', '0');
                        $('.data-chat').append('<\div class=\"row message message_id_' + response.message['id'] + '\" style=\"margin: 0;\"><\div class=\"income\"><\div class=\"income_data\"><\div style=\"display: flex;\"><\div style=\"padding-right: 15px;\"><\img class=\"round-avatar\" style=\"width: 50px; height: 50px;\" src='+ response.user['avatar_image'] +' ><\/div><\div style=\"padding-top: 5px;\"><\div style=\"font-size: 13px; font-weight: 700;\"><\span style=\"padding-right: 30px;\">' + response.user['second_name'] + ' ' + response.user['first_name'] + ' ' + response.user['middle_name'] + '<\/span><\/div><\div style=\"font-size: 13px; font-weight: 700;\"><\span style=\"padding-right: 10px; font-size: 12px;\">' + fullTime + '<\/span><\span style=\"font-size: 12px;\">' + fullDate + '<\/span><\/div><\/div><\/div><\div class=\"message-description\" style=\"padding: 10px 5px;\">' + response.message['description'] + '<\/div><\/div><\/div>');
                    }
                    
                }
                
                $('#create-message-admin')[0].reset();
            },
            error: function(){
                alert('Ошибка');
            }
        });
        e.preventDefault();

        return false;
     });
     
     
     
     //Автоматическое обновление страницы
     function reloadcontent() {
         $.ajax ({
             url: '". Url::to(['update', 'id' => \Yii::$app->request->get('id')])."',
             cache: false,
             success: function(response) {
        
                 if (response.messages.length > 0) {
                
                     $('.data-chat').html('');
                     $('.not-message').empty();
                     $('.create-message').css('padding-top', '0');
                    
                     for (var i = 0; i < response.messages.length; i++) {
                        
                         if (response.messages[i]['sender_id'] != response.user['id']) {
                        
                             $('.data-chat').append('<\div class=\"row message message_id_' + response.messages[i]['id'] + '\" style=\"margin: 0;\"><\div class=\"send\"><\div class=\"send_data\"><\div style=\"display: flex;\">   <\div style=\"padding-right: 15px;\"><\img class=\"round-avatar\" style=\"width: 50px; height: 50px;\" src='+ response.admin['avatar_image'] +' ><\/div><\div style=\"padding-top: 5px;\"><\div style=\"font-size: 13px; font-weight: 700;\"><\span style=\"padding-right: 30px;\">Администратор<\/span><\/div><\div style=\"font-size: 13px; font-weight: 700;\"><\span style=\"padding-right: 8px; font-size: 12px;\">' + response.times[i] + '<\/span><\span style=\"font-size: 12px;\">' + response.dates[i] + '<\/span><\/div><\/div><\/div><\div class=\"message-description\" style=\"padding: 10px 5px;\">' + response.messages[i]['description'] + '<\/div><\/div><\/div><\/div>');
                           
                         }else {
                        
                             $('.data-chat').append('<\div class=\"row message message_id_' + response.messages[i]['id'] + '\" style=\"margin: 0;\"><\div class=\"income\"><\div class=\"income_data\"><\div style=\"display: flex;\">   <\div style=\"padding-right: 15px;\"><\img class=\"round-avatar\" style=\"width: 50px; height: 50px;\" src='+ response.user['avatar_image'] +' ><\/div><\div style=\"padding-top: 5px;\"><\div style=\"font-size: 13px; font-weight: 700;\"><\span style=\"padding-right: 30px;\">' + response.user['second_name'] + ' ' + response.user['first_name'] + ' ' + response.user['middle_name'] + '<\/span><\/div><\div style=\"font-size: 13px; font-weight: 700;\"><\span style=\"padding-right: 8px; font-size: 12px;\">' + response.times[i] + '<\/span><\span style=\"font-size: 12px;\">' + response.dates[i] + '<\/span><\/div><\/div><\/div><\div class=\"message-description\" style=\"padding: 10px 5px;\">' + response.messages[i]['description'] + '<\/div><\/div><\/div><\/div>');
                         }
                     }
                 } 
             }
         });
     }
     
     
     function timeUpdate(){  //Установка таймера на обновление страницы
        
        reloadcontent();
     }
     setInterval (timeUpdate,10000);
     
     
";

$this->registerJs($script);
