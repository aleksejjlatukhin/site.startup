<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\bootstrap\Modal;


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
                <th scope="col"  class="col-md-4" style="text-align: center;padding-bottom: 15px; padding-top: 15px;">Респонденты</th>
                <th scope="col"  class="col-md-8" style="text-align: center;padding-bottom: 15px;padding-top: 15px;">Выводы интервью</th>
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
        <?= Html::submitButton('Сохранить', [
                'class' => 'btn btn-success',
                'data-toggle' => 'modal',
                'data-target' => '#gps_modal',
            ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<!--<div class="row">
    <div class="col-md-8">

        <hr>

        <h4>Добавленные гипотезы проблем сегмента:</h4>
        <div class="new" style="font-size: 15px;font-weight: 700;">
            <?php /*if (!empty($models)) : */?>
                <?php /*foreach ($models as $model) : */?>
                    <?/*= Html::a($model->title, Url::to(['view', 'id' => $model->id])) . ' | ';*/?>
                <?php /*endforeach;*/?>
            <?php /*endif; */?>
        </div>

        <hr>

    </div>
</div>-->




<?= '<div style="display:none;">' . Html::img('@web/images/icons/fast forward.png', ['id' => 'status_image', 'style' => ['width' => '18px', 'padding-bottom' => '3px',]]) . '</div>';?>

<?php

$script = "
    
     $('form').on('beforeSubmit', function(e){
        
        var data = $(this).serialize();

        $.ajax({
        
            url: '". Url::to(['create', 'id' => $model->interview_id])."',
            method: 'POST',
            data: data,
            success: function(response){
                //console.log(response);
                
                //Создаем ссылку новой ГПС и добавляем её к элементу с классом gps-title
                $('.gps-title').append('<\div class=\"border-gray\" style=\"height: 100px; padding-top: 20px;\"><\a href=\"\" id=\"link\">' + response.title + '<\/a><\/div>');
                var a = document.getElementById('link');
                var str = '".Url::toRoute(['view'])."?id=' +response.id;
                a.href = str;
                a.id = response.id;
                
                
                //Выводим дату создания ГПС
                var dateGps = new Date().toLocaleDateString();
                $('.gps-date').append('<\div class=\"border-gray\" style=\"height: 100px; padding-top: 20px;\">' + dateGps  + '<\/div>');
                
                
                //Выводим описание ГПС
                var Desc = response.description;
                if(Desc.length > 170){
                    Desc = Desc.substr(0, 170) + '...';
                }
                $('.gps-description').append('<\div class=\"border-gray\" style=\"height: 100px; padding: 20px 10px;\">' + Desc  + '<\/div>');
                
                
                //Выводим статус проблемы
                var img = document.getElementById('status_image');
                $('.gps-status').append('<\div class=\"border-gray\" style=\"height: 100px; padding-top: 20px;\"><\img style=\"width: 18px; padding-bottom: 13px;\" id=\"img-stat\" src=\"' + img.src +'\"/><\a href=\"\" id=\"but-stat\" style=\"width:130px; font-weight:700;\" class=\"btn btn-sm btn-primary\">' + 'Подтвердить' + '<\/a><\/div>');
                var imageStatus = document.getElementById('img-stat');
                var buttonStatus = document.getElementById('but-stat');
                var strBut = '".Url::toRoute(['confirm-problem/create'])."?id=' +response.id;
                buttonStatus.href = strBut;
                imageStatus.id = 'img-stat' + response.id;
                buttonStatus.id = 'but-stat' + response.id;
                
                
                //Выводим пустой блок для даты проблемы
                $('.confirm-date').append('<\div class=\"border-gray\" style=\"height: 100px;\"><\/div>');
                
                
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


Modal::begin([
    'options' => [
        'id' => 'gps_modal'
    ],
    'size' => 'modal-lg',
    'header' => '<h2>Сведения о генерации ГПС</h2>',
    /*'toggleButton' => [
        'label' => 'Модальное окно',
    ],*/
    //'footer' => '',
]);

?>


    <table class="table table-bordered table-striped" style="margin: 0;">
        <thead>
        <tr>
            <th scope="col" rowspan="2" style="text-align: center;width: 140px;padding: 30px 0;">ГПС</th>
            <th scope="col" rowspan="2" style="text-align: center;width: 140px;padding: 30px 0;">Дата ГПС</th>
            <th scope="col" rowspan="2" style="text-align: center;width: 400px;padding: 30px 0;">Формулировка</th>
            <th scope="col" colspan="2" style="width: 300px; text-align: center;padding: 10px 0;">Проблема сегмента</th>
        </tr>
        <tr>
            <td style="width: 150px;text-align: center;font-weight: 700;">Статус</td>
            <td style="width: 150px;text-align: center;font-weight: 700;">Дата</td>
        </tr>
        </thead>
        <tbody>
        <tr>

            <td style="text-align: center; padding: 0;font-size: 13px; font-weight: 700;">

                <div class="gps-title" >
                    <?

                    if (!empty($models)){
                        foreach ($models as $k => $model) {

                            if (isset($model->confirm)){

                                /*Если есть подтверждение то выводим его результат*/
                                if ($model->exist_confirm === 1) {

                                    echo '<div class="border-gray" style="height: 70px; padding-top: 20px;">'. Html::a($model->title, Url::to(['generation-problem/view', 'id' => $model->id])) .'</div>';
                                }
                                if ($model->exist_confirm === 0) {

                                    echo '<div class="border-gray" style="height: 70px; padding-top: 20px;">'. Html::a($model->title, Url::to(['generation-problem/view', 'id' => $model->id])) .'</div>';
                                }

                                /*Если у существующей ГПС нет подтверждения то выводим следующее*/
                                if ($model->exist_confirm === null) {
                                    echo '<div class="border-gray" style="height: 100px; padding-top: 20px;">'. Html::a($model->title, Url::to(['generation-problem/view', 'id' => $model->id])) . '</div>';
                                }

                            }else {

                                echo '<div class="border-gray" style="height: 100px; padding-top: 20px;">'. Html::a($model->title, Url::to(['generation-problem/view', 'id' => $model->id])) . '</div>';
                            }

                        }
                    }

                    ?>
                </div>

            </td>

            <td style="text-align: center; padding: 0; font-size: 13px; font-weight: 700;">

                <div class="gps-date">

                    <? if (!empty($models)){
                        foreach ($models as $k => $model) {

                            if (isset($model->confirm)){

                                /*Если есть подтверждение то выводим его результат*/
                                if ($model->exist_confirm === 1) {

                                    echo '<div class="border-gray" style="height: 70px; padding-top: 20px;">'. date("d.m.Y", strtotime($model->date_gps)) .'</div>';
                                }
                                if ($model->exist_confirm === 0) {

                                    echo '<div class="border-gray" style="height: 70px; padding-top: 20px;">'. date("d.m.Y", strtotime($model->date_gps)) .'</div>';
                                }

                                /*Если у существующей ГПС нет подтверждения то выводим следующее*/
                                if ($model->exist_confirm === null) {
                                    echo '<div class="border-gray" style="height: 100px; padding-top: 20px;">'. date("d.m.Y", strtotime($model->date_gps)) . '</div>';
                                }

                            }else {

                                echo '<div class="border-gray" style="height: 100px; padding-top: 20px;">'. date("d.m.Y", strtotime($model->date_gps)) . '</div>';
                            }
                        }
                    }
                    ?>
                </div>



            </td>

            <td style=" padding: 0; font-size: 13px;">

                <div class="gps-description" >
                    <? if (!empty($models)){
                        foreach ($models as $k => $model) {

                            if (isset($model->confirm)){

                                if ($model->exist_confirm !== null) {

                                    if ($model->exist_confirm === 0){

                                        if (mb_strlen($model->description) > 80){

                                            echo '<div class="border-gray" style="height: 70px; padding: 20px 10px;">' . mb_substr($model->description, 0, 80) . '<span>...</span>' . '</div>';

                                        }else {

                                            echo '<div class="border-gray" style="height: 70px; padding: 20px 10px;">' . $model->description . '</div>';
                                        }

                                    }else {

                                        if (mb_strlen($model->description) > 80){

                                            echo '<div class="border-gray" style="height: 70px; padding: 20px 10px;">' . mb_substr($model->description, 0, 80) . '<span>...</span>' . '</div>';

                                        }else {

                                            echo '<div class="border-gray" style="height: 70px; padding: 20px 10px;">' . $model->description . '</div>';
                                        }
                                    }

                                }else {

                                    if (mb_strlen($model->description) > 170){

                                        echo '<div class="border-gray" style="height: 100px; padding: 20px 10px;">' . mb_substr($model->description, 0, 170) . '<span>...</span>' . '</div>';

                                    }else {

                                        echo '<div class="border-gray" style="height: 100px; padding: 20px 10px;">' . $model->description . '</div>';
                                    }
                                }

                            }else {

                                if (mb_strlen($model->description) > 170){

                                    echo '<div class="border-gray" style="height: 100px; padding: 20px 10px;">' . mb_substr($model->description, 0, 170) . '<span>...</span>' . '</div>';

                                }else {

                                    echo '<div class="border-gray" style="height: 100px; padding: 20px 10px;">' . $model->description . '</div>';
                                }
                            }
                        }
                    }
                    ?>
                </div>

            </td>


            <td style="text-align: center;padding: 0;">

                <div class="gps-status">
                    <?php

                    foreach ($models as $i => $model) {

                        if (isset($model->confirm)){

                            /*Если есть подтверждение то выводим его результат*/
                            if ($model->exist_confirm === 1) {

                                echo '<div class="border-gray" style="height: 70px; padding-top: 20px;"><div>'. Html::img('@web/images/icons/green tick.png', ['style' => ['width' => '20px', 'padding-bottom' => '3px',]]) .'</div></div>';
                            }
                            if ($model->exist_confirm === 0) {

                                echo '<div class="border-gray" style="height: 70px; padding-top: 20px;">'. Html::img('@web/images/icons/cross delete.png', ['style' => ['width' => '22px', 'padding-bottom' => '3px',]]) .'</div>';
                            }

                            /*Если у существующей ГПС нет подтверждения то выводим следующее*/
                            if ($model->exist_confirm === null) {
                                echo '<div class="border-gray" style="height: 100px; padding-top: 15px;"><div>'. Html::img('@web/images/icons/fast forward.png', ['style' => ['width' => '18px', 'padding-bottom' => '3px',]]) .'</div>' .
                                    '<div>' . Html::a('Подтверждение', ['confirm-problem/view', 'id' => $model->confirm->id], ['class' => 'btn btn-sm btn-warning', 'style' => ['margin-top' => '10px', 'width' => '130px', 'font-weight' => '700']]) . '</div></div>';
                            }

                        }else {

                            echo '<div class="border-gray" style="height: 100px; padding-top: 15px;"><div>'. Html::img('@web/images/icons/fast forward.png', ['style' => ['width' => '18px', 'padding-bottom' => '3px',]]) .'</div>'.
                                '<div>' . Html::a('Подтвердить', ['confirm-problem/create', 'id' => $model->id], ['class' => 'btn btn-sm btn-primary', 'style' => ['margin-top' => '10px', 'width' => '130px', 'font-weight' => '700']]) . '</div></div>';
                        }
                    }

                    ?>
                </div>
            </td>


            <td style="text-align: center; font-size: 13px; font-weight: 700;padding: 0;">

                <div class="confirm-date">
                    <?php

                    foreach ($models as $k => $model) {

                        if (isset($model->confirm)){

                            /*Если есть подтверждение то выводим его результат*/
                            if ($model->exist_confirm === 1) {
                                echo '<div class="border-gray" style="height: 70px; padding-top: 20px;">'. date('d.m.yy', strtotime($model->date_confirm)) .'</div>';
                            }
                            if ($model->exist_confirm === 0) {
                                echo '<div class="border-gray" style="height: 70px; padding-top: 20px;">'. date('d.m.yy', strtotime($model->date_confirm)) .'</div>';
                            }

                            /*Если у существующей ГПС нет подтверждения то выводим следующее*/
                            if ($model->exist_confirm === null) {
                                echo '<div class="border-gray" style="height:100px;"></div>';
                            }

                        }else {

                            echo '<div class="border-gray" style="height: 100px;"></div>';
                        }
                    }

                    ?>
                </div>

            </td>



        </tr>
        </tbody>
    </table>

<?php
Modal::end();



