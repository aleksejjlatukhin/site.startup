<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Mvp */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mvp-form">

    <?php $form = ActiveForm::begin(['id' => 'mvpForm']); ?>

    <div class="row">
        <div class="col-md-8">

    <? $placeholder = 'Примеры: 
- презентация, 
- макет, 
- программное обеспечение, 
- опытный образец, 
- видео и т.д.' ?>

    <?= $form->field($model, 'description')->label('<h4>Напишите описание гипотезы Minimum Viable Product</h4>')->textarea(['rows' => 5, 'placeholder' => $placeholder]) ?>

        </div>
    </div>

    <div class="form-group">

        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

    </div>

    <?php ActiveForm::end(); ?>

</div>

<div class="row">
    <div class="col-md-8">

        <hr>

        <h4>Добавленные MVP:</h4>
        <div class="new" style="font-size: 15px;font-weight: 700;">
            <?php if (!empty($models)) : ?>
                <?php foreach ($models as $model) : ?>
                    <?= Html::a($model->title, Url::to(['view', 'id' => $model->id])) . ' | ';?>
                <?php endforeach;?>
            <?php endif; ?>
        </div>

        <hr>

    </div>
</div>



<?php

$script = "
    
     $('form').on('beforeSubmit', function(e){
        
        var data = $(this).serialize();

        $.ajax({
        
            url: '". Url::to(['create', 'id' => $model->confirm_gcp_id])."',
            method: 'POST',
            data: data,
            success: function(response){
                console.log(data);
                
                $('.new').append('<\a href=\"\" id=\"link\">' + response.title + '<\/a>' + ' | ');
                 
                var a = document.getElementById('link');
                var str = '".Url::toRoute(['view'])."?id=' +response.id;
                a.href = str;
                a.id = response.id;
                
                $('#mvpForm')[0].reset();
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
