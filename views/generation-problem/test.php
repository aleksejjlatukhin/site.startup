<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

?>
<div class="generation-problem-form">

    <?php $form = ActiveForm::begin(['id' => 'myForm']); ?>

    <? $placeholder = 'Примеры: 
- отсутствие путеводителя по комерциализации результатов интеллектуальной деятельности, 
- отсутствие необходимой информации по патентованию...' ?>

    <?= $form->field($model, 'description')->label('Описание гипотезы проблемы сегмента')->textarea(['rows' => 6, 'placeholder' => $placeholder]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<div class="container new">
    <?php if (!empty($models)) : ?>
        <?php foreach ($models as $model) : ?>
            <?= Html::a($model->title, Url::to(['view', 'id' => $model->id]));?>
        <?php endforeach;?>
    <?php endif; ?>
</div>

<?php



$script = "
    
     $('form').on('beforeSubmit', function(e){
        
        var data = $(this).serialize();

        $.ajax({
        
            url: '". Url::to(['test', 'id' => $model->interview_id])."',
            method: 'POST',
            data: data,
            success: function(response){
                console.log(data);
                
                $('.new').append('<\a href=\"\" id=\"link\">' + response.title + '<\/a>' + ', ');
                 
                var a = document.getElementById('link');
                var str = '".Url::toRoute(['view'])."?id=' +response.id;
                a.href = str;
                a.id = response.id;
                
                $('#myForm')[0].reset();
            },
            error: function(){
                alert('Ошибка');
            }
        });
        e.preventDefault();

        return false;
     });
";

$this->registerJs($script);


