<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\models\GenerationProblem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-md-8">

        <div class="d-inline p-2 bg-success" style="font-size: 15px;border-radius: 5px;padding:15px;margin-bottom: 20px;">
            Необходимо просмотреть и проанализировать все материалы интервью представителей сегмента и выявить проблемы, которые характерны для нескольких респондентов
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
                    <?php if (($respond->descInterview->status == 1)) : ?>
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
                <?php endif;?>
            <?php endforeach;?>
            </tbody>
        </table>

    </div>
</div>




<div class="generation-problem-form">

    <?php $form = ActiveForm::begin(['id' => 'gpsForm']); ?>

    <? $placeholder = 'Примеры: 
- отсутствие путеводителя по комерциализации результатов интеллектуальной деятельности, 
- отсутствие необходимой информации по патентованию...' ?>

    <div class="row">
        <div class="col-md-8">

            <?= $form->field($model, 'description')->label('<h4>Напишите описание гипотезы проблемы сегмента</h4>')->textarea(['rows' => 4, 'placeholder' => $placeholder]) ?>

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

        <h4>Добавленные гипотезы проблем сегмента:</h4>
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

<?= Html::a('<< Программа генерации ГПС', ['interview/view', 'id' => $model->interview_id], ['class' => 'btn btn-default']) ?>

<?php

$script = "
    
     $('form').on('beforeSubmit', function(e){
        
        var data = $(this).serialize();

        $.ajax({
        
            url: '". Url::to(['create', 'id' => $model->interview_id])."',
            method: 'POST',
            data: data,
            success: function(response){
                console.log(data);
                
                $('.new').append('<\a href=\"\" id=\"link\">' + response.title + '<\/a>' + ' | ');
                 
                var a = document.getElementById('link');
                var str = '".Url::toRoute(['view'])."?id=' +response.id;
                a.href = str;
                a.id = response.id;
                
                //$('#generationproblem-description').val('');
                $('#gpsForm')[0].reset();
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





