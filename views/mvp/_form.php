<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\bootstrap\Modal;

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

        <?= Html::submitButton('Сохранить', [
                'class' => 'btn btn-success',
                'data-toggle' => 'modal',
                'data-target' => '#mvp_modal',
        ]) ?>

    </div>

    <?php ActiveForm::end(); ?>

</div>

<!--<div class="row">
    <div class="col-md-8">

        <hr>

        <h4>Добавленные MVP:</h4>
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
        
            url: '". Url::to(['create', 'id' => $model->confirm_gcp_id])."',
            method: 'POST',
            data: data,
            success: function(response){
                //console.log(response);
                
                //Создаем ссылку новой ГMVP и добавляем её к элементу с классом mvp-title
                $('.mvp-title').append('<\div class=\"border-gray\" style=\"height: 100px; padding-top: 20px;\"><\a href=\"\" id=\"link\">' + response.title + '<\/a><\/div>');
                var a = document.getElementById('link');
                var str = '".Url::toRoute(['view'])."?id=' +response.id;
                a.href = str;
                a.id = response.id;
                
                
                //Выводим дату создания ГMVP
                var dateMvp = new Date().toLocaleDateString();
                $('.mvp-date').append('<\div class=\"border-gray\" style=\"height: 100px; padding-top: 20px;\">' + dateMvp  + '<\/div>');
                
                
                //Выводим описание ГMVP
                var Desc = response.description;
                if(Desc.length > 170){
                    Desc = Desc.substr(0, 170) + '...';
                }
                $('.mvp-description').append('<\div class=\"border-gray\" style=\"height: 100px; padding: 20px 10px;\">' + Desc  + '<\/div>');
                
                
                //Выводим статус проблемы
                var img = document.getElementById('status_image');
                $('.mvp-status').append('<\div class=\"border-gray\" style=\"height: 100px; padding-top: 20px;\"><\img style=\"width: 18px; padding-bottom: 13px;\" id=\"img-stat\" src=\"' + img.src +'\"/><\a href=\"\" id=\"but-stat\" style=\"width:130px; font-weight:700;\" class=\"btn btn-sm btn-primary\">' + 'Подтвердить' + '<\/a><\/div>');
                var imageStatus = document.getElementById('img-stat');
                var buttonStatus = document.getElementById('but-stat');
                var strBut = '".Url::toRoute(['confirm-mvp/create'])."?id=' +response.id;
                buttonStatus.href = strBut;
                imageStatus.id = 'img-stat' + response.id;
                buttonStatus.id = 'but-stat' + response.id;
                
                
                //Выводим пустой блок для даты проблемы
                $('.confirm-date').append('<\div class=\"border-gray\" style=\"height: 100px;\"><\/div>');
                
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

Modal::begin([
    'options' => [
        'id' => 'mvp_modal'
    ],
    'size' => 'modal-lg',
    'header' => '<h2>Сведения о генерации ГMVP' .  Html::a("Разработка ГMVP", ["mvp/index", "id" => $confirmGcp->id], ["class" => "btn btn-sm btn-default pull-right", "style" => ["margin-right" => "30px"]]) . '</h2>',
    /*'toggleButton' => [
        'label' => 'Модальное окно',
    ],*/
    //'footer' => '',
]);

?>

    <table class="table table-bordered table-striped" style="margin: 0;">
        <thead>
        <tr>
            <th scope="col" rowspan="2" style="text-align: center;width: 140px;padding: 30px 0;">ГMVP</th>
            <th scope="col" rowspan="2" style="text-align: center;width: 140px;padding: 30px 0;">Дата ГMVP</th>
            <th scope="col" rowspan="2" style="text-align: center;width: 400px;padding: 30px 0;">Формулировка</th>
            <th scope="col" colspan="2" style="width: 300px; text-align: center;padding: 10px 0;">MVP (продукт)</th>
        </tr>
        <tr>
            <td style="width: 150px;text-align: center;font-weight: 700;">Статус</td>
            <td style="width: 150px;text-align: center;font-weight: 700;">Дата</td>
        </tr>
        </thead>
        <tbody>
        <tr>

            <td style="text-align: center; padding: 0;font-size: 13px; font-weight: 700;">


                <div class="mvp-title" >
                    <? if (!empty($models)){
                        foreach ($models as $k => $model) {

                            //echo Html::a($model->title, Url::to(['mvp/view', 'id' => $model->id])) . '<div style="height:'. $height[$k] .'px"></div><hr>';

                            if (isset($model->confirm)){

                                /*Если есть подтверждение то выводим его результат*/
                                if ($model->exist_confirm === 1) {

                                    echo '<div class="border-gray" style="height: 70px; padding-top: 20px;">'. Html::a($model->title, Url::to(['mvp/view', 'id' => $model->id])) .'</div>';
                                }
                                if ($model->exist_confirm === 0) {

                                    echo '<div class="border-gray" style="height: 70px; padding-top: 20px;">'. Html::a($model->title, Url::to(['mvp/view', 'id' => $model->id])) .'</div>';
                                }

                                /*Если у существующей ГПС нет подтверждения то выводим следующее*/
                                if ($model->exist_confirm === null) {
                                    echo '<div class="border-gray" style="height: 100px; padding-top: 20px;">'. Html::a($model->title, Url::to(['mvp/view', 'id' => $model->id])) . '</div>';
                                }

                            }else {

                                echo '<div class="border-gray" style="height: 100px; padding-top: 20px;">'. Html::a($model->title, Url::to(['mvp/view', 'id' => $model->id])) . '</div>';
                            }
                        }
                    }
                    ?>
                </div>

            </td>

            <td style="text-align: center; padding: 0; font-size: 13px; font-weight: 700;">

                <div class="mvp-date">
                    <? if (!empty($models)){
                        foreach ($models as $k => $model) {
                            //echo date("d.m.Y", strtotime($model->date_create)) . '<div style="height:'. $height[$k] .'px"></div><hr>';

                            if (isset($model->confirm)){

                                /*Если есть подтверждение то выводим его результат*/
                                if ($model->exist_confirm === 1) {

                                    echo '<div class="border-gray" style="height: 70px; padding-top: 20px;">'. date("d.m.Y", strtotime($model->date_create)) .'</div>';
                                }
                                if ($model->exist_confirm === 0) {

                                    echo '<div class="border-gray" style="height: 70px; padding-top: 20px;">'. date("d.m.Y", strtotime($model->date_create)) .'</div>';
                                }

                                /*Если у существующей ГПС нет подтверждения то выводим следующее*/
                                if ($model->exist_confirm === null) {
                                    echo '<div class="border-gray" style="height: 100px; padding-top: 20px;">'. date("d.m.Y", strtotime($model->date_create)) . '</div>';
                                }

                            }else {

                                echo '<div class="border-gray" style="height: 100px; padding-top: 20px;">'. date("d.m.Y", strtotime($model->date_create)) . '</div>';
                            }
                        }
                    }
                    ?>
                </div>



            </td>

            <td style=" padding: 0; font-size: 13px;">

                <div class="mvp-description" >
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

                <div class="mvp-status">
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
                                    '<div>' . Html::a('Подтверждение', ['confirm-mvp/view', 'id' => $model->confirm->id], ['class' => 'btn btn-sm btn-warning', 'style' => ['margin-top' => '10px', 'width' => '130px', 'font-weight' => '700']]) . '</div></div>';
                            }

                        }else {

                            echo '<div class="border-gray" style="height: 100px; padding-top: 15px;"><div>'. Html::img('@web/images/icons/fast forward.png', ['style' => ['width' => '18px', 'padding-bottom' => '3px',]]) .'</div>'.
                                '<div>' . Html::a('Подтвердить', ['confirm-mvp/create', 'id' => $model->id], ['class' => 'btn btn-sm btn-primary', 'style' => ['margin-top' => '10px', 'width' => '130px', 'font-weight' => '700']]) . '</div></div>';
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
