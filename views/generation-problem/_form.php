<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\models\GenerationProblem */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="d-inline p-2 bg-success" style="font-size: 16px;border-radius: 5px;padding:15px 20px;margin-bottom: 20px;">
    Необходимо просмотреть и проанализировать все материалы интервью и выявить проблемы, которые характерны для нескольких респондентов
</div>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th scope="col"  style="width: 120px;text-align: center;padding-bottom: 15px; padding-top: 15px;">Респонденты</th>
            <th scope="col"  style="width: 330px;text-align: center;padding-bottom: 15px;padding-top: 15px;">Выводы интервью</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($responds as $respond) : ?>
        <?php if ((!empty($respond->descInterview))) : ?>
        <tr>
            <td>
                <?= Html::a($respond->name, Url::to(['respond/view', 'id' => $respond->id])); ?>
            </td>

            <td>
                <?php if (strlen($respond->descInterview->result) <= 200) : ?>

                <?= mb_substr($respond->descInterview->result, 0, 200) ?>

                <?php else: ?>

                <?= mb_substr($respond->descInterview->result, 0, 200) . '...' ?>

                <?php endif; ?>
            </td>
        </tr>
        <?php endif;?>
    <?php endforeach;?>
    </tbody>
</table>



<div class="generation-problem-form">

    <?php $form = ActiveForm::begin(['id' => 'my_form_problem', 'action' => ['create', 'id' => $model->interview_id]]); ?>

    <? $placeholder = 'Примеры: 
- отсутствие путеводителя по комерциализации результатов интеллектуальной деятельности, 
- отсутствие необходимой информации по патентованию...' ?>

    <?= $form->field($model, 'description')->label('Описание гипотезы проблемы сегмента')->textarea(['rows' => 6, 'placeholder' => $placeholder]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>



<?php

/*$js = <<<JS
    $('#my_form_problem').submit(function(){
        
        var $form = $(this);

        $.ajax({
        
            url: form.attr('action'),
            method: 'post',
            //dataType: 'html',
            data: form.serializeArray(),
            success: function(data){
                $('#results').html(data);
            },
            error:  function(xhr, str){
	            alert('Возникла ошибка: ' + xhr.responseCode);
            }
        });

        return false;
    });
JS;*/





