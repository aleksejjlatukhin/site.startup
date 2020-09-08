<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\widgets\DetailView;
use app\models\User;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Программа генерации ГЦП';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index', 'id' => $project->user_id]];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа генерации ГПС', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $generationProblem->title, 'url' => ['generation-problem/view', 'id' => $generationProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа подтверждения ' . $generationProblem->title, 'url' => ['confirm-problem/view', 'id' => $confirmProblem->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gcp-index table-project-kartik">


    <?php
    // Описание выполнения задачи на данной странице
    Modal::begin([
        'options' => [
            'id' => 'information-table-problem-view',
        ],
        'size' => 'modal-md',
        'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Информация</h3>',
    ]);
    ?>

    <div style="color: #F2F2F2; padding: 0 30px; font-size: 18px;">

        <p>Совершите необходимые действия на данной странице:</p>

        <p>- сгенерируйте гипотезу ценностного предложения;</p>

        <p>- отредактируйте описание гипотезы по грамматическому смыслу (переход к редактированию через ссылку «наименование» ГЦП);</p>

        <p>- далее переходите к подтверждению гипотезы ценностного предложения</p>

    </div>

    <?php
    Modal::end();
    ?>


    <div class="row d-inline p-2" style="background: #707F99; font-size: 26px; font-weight: 700; color: #F2F2F2; border-radius: 5px 5px 0 0; padding: 0; margin: 0; padding-top: 20px; padding-bottom: 10px;/*height: 80px;*//*padding-top: 12px;padding-left: 20px;margin-top: 10px;*/">

        <div class="col-md-12 col-lg-6" style="padding: 0 20px; text-align: center;">

            <?php
            echo 'Программа генерации ГЦП' .

                Html::a('i', ['#'], [
                    'style' => ['margin-left' => '20px', 'font-size' => '13px', 'font-weight' => '700', 'padding' => '2px 8px', 'background-color' => '#F2F2F2', 'border-radius' => '50%', 'text-decoration' => 'none'],
                    'class' => 'table-kartik-link',
                    'data-toggle' => 'modal',
                    'data-target' => "#information-table-problem-view",
                    'title' => 'Посмотреть описание',
                ])
            ?>
        </div>

        <div class="col-md-12 col-lg-2" style="padding: 0 10px 10px 10px; text-align: center;">
            <?= Html::a('Данные сегмента', ['#'], [
                'class' => 'btn btn-sm btn-default',
                'style' => ['font-weight' => '700', 'color' => '#373737', 'width' => '170px'],
                'data-toggle' => 'modal',
                'data-target' => '#data_segment_modal',
            ]); ?>
        </div>

        <div class="col-md-12 col-lg-2" style="padding: 0 10px 10px 10px; text-align: center;">
            <?= Html::a('Дорожная карта сегмента', ['segment/one-roadmap', 'id' => $segment->id], ['class' => 'btn btn-sm btn-default', 'style' => ['font-weight' => '700', 'color' => '#373737', 'width' => '170px']]) ?>
        </div>

        <div class="col-md-12 col-lg-2" style="padding: 0 10px 10px 10px; text-align: center;">
            <?= Html::a('Сводная таблица проекта', ['projects/result', 'id' => $project->id], ['class' => 'btn btn-sm btn-default', 'style' => ['font-weight' => '700', 'color' => '#373737', 'width' => '170px']]) ?>
        </div>

    </div>



    <div class="style-header-table-kartik">


        <?php

        $gridColumns = [

            [
                'attribute' => 'title',
                'label' => 'Наименование',
                'header' => '<div class="font-header-table" style="font-size: 12px; font-weight: 500;">Наименование</div>',
                'groupOddCssClass' => 'kv',
                'groupEvenCssClass' => 'kv',
                'options' => ['colspan' => 1,],
                'value' => function ($model, $key, $index, $widget) {

                    if ($model){

                        return '<div class="text-center">' .
                            Html::a($model->title, ['#'],[
                                'class' => 'btn btn-primary',
                                'data-toggle' => 'modal',
                                'data-target' => "#update_description_modal-$model->id",
                                'title' => 'Редактировать описание',
                                ])
                            . '</div>';
                    }
                },
                'format' => 'raw',
                'hiddenFromExport' => true, // Убрать столбец при скачивании
            ],


            [
                'attribute' => 'description',
                'label' => 'Описание гипотезы',
                'header' => '<div class="font-header-table" style="font-size: 12px; font-weight: 500;">Описание гипотезы</div>',
                'groupOddCssClass' => 'kv',
                'groupEvenCssClass' => 'kv',
                'options' => ['colspan' => 1],
                'value' => function ($model, $key, $index, $widget) {

                    return '<div style="padding: 0 5px;">' . $model->description . '</div>';
                },
                'format' => 'raw',
            ],


            [
                'attribute' => 'date_create',
                'label' => 'Дата создания',
                'header' => '<div class="font-header-table" style="font-size: 12px; font-weight: 500;">Дата создания</div>',
                'groupOddCssClass' => 'kv',
                'groupEvenCssClass' => 'kv',
                'options' => ['colspan' => 1],
                'value' => function ($model, $key, $index, $widget) {

                    return '<div class="text-center" style="padding: 0 5px;">' . date("d.m.y", $model->created_at) . '</div>';
                },
                'format' => 'raw',
            ],


            [
                'attribute' => 'status',
                'label' => 'Подтверждение',
                'header' => '<div class="font-header-table" style="font-size: 12px; font-weight: 500;">Подтверждение</div>',
                'groupOddCssClass' => 'kv',
                'groupEvenCssClass' => 'kv',
                'options' => ['colspan' => 1],
                'value' => function ($model, $key, $index, $widget) {

                    if ($model->exist_confirm === 1) {

                        return '<div class="text-center" style="padding: 0 5px;">' . Html::a(Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px',]]), Url::to(['/confirm-gcp/view', 'id' => $model->confirm->id])) . '</div>';

                    }elseif ($model->exist_confirm === null) {

                        return '<div class="text-center" style="padding: 0 5px;">' . Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]), Url::to(['/confirm-gcp/create', 'id' => $model->id])) . '</div>';

                    }elseif ($model->exist_confirm === 0) {

                        return '<div class="text-center" style="padding: 0 5px;">' . Html::a(Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px',]]), Url::to(['/confirm-gcp/view', 'id' => $model->confirm->id])) . '</div>';

                    }else {
                        return '';
                    }
                },
                'format' => 'raw',
            ],

            [
                'attribute' => 'date_confirm',
                'label' => 'Дата подтверждения',
                'header' => '<div class="font-header-table" style="font-size: 12px; font-weight: 500;">Дата подтверждения</div>',
                'groupOddCssClass' => 'kv',
                'groupEvenCssClass' => 'kv',
                'options' => ['colspan' => 1],
                'value' => function ($model, $key, $index, $widget) {

                    if ($model->date_confirm) {

                        return '<div class="text-center" style="padding: 0 5px;">'. date("d.m.y", strtotime($model->date_confirm)) .'</div>';
                    }else {
                        return '';
                    }
                },
                'format' => 'raw',
            ],

        ]

        ?>


        <?php

        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'showPageSummary' => false, //whether to display the page summary row for the grid view.
            'pjax' => false,
            'hashExportConfig' => false,
            'striped' => false,
            'bordered' => true,
            'panel' => [
                'type' => 'default',
                'heading' => false,
                'before' => false,
                'after' => false,
            ],
            'toolbar' => false,
            'condensed' => true,
            'summary' => false,
            'hover' => true,
            'columns' => $gridColumns,
            'beforeHeader' => [
                [
                    'columns' => [
                        ['content' =>  Html::a(Html::img('@web/images/icons/icon-plus.png', ['style' => ['width' => '30px', 'margin-right' => '10px']]), ['#'], ['data-toggle' => 'modal', 'data-target' => '#problem_create_modal',]) . 'Гипотеза ценностного предложения', 'options' => ['colspan' => 3, 'class' => 'font-segment-header-table text-center', 'style' => ['padding-top' => '10px', 'padding-bottom' => '10px']]],
                        ['content' => 'Ценностное предложение', 'options' => ['colspan' => 2, 'class' => 'font-header-table', 'style' => ['padding-top' => '15px', 'padding-bottom' => '15px', 'text-align' => 'center']]],
                    ],
                    'options' => [
                        'class' => 'style-header-table-kartik',
                    ]
                ]
            ],
        ]);

        ?>


    </div>



    <?php
    // Модальное окно - создание ГЦП
    Modal::begin([
        'options' => [
            'id' => 'problem_create_modal',
            'class' => 'problem_create_modal',
        ],
        'size' => 'modal-lg',
        'header' => '<div class="text-center"><span style="font-size: 24px;">Генерация гипотезы ценностного предложения</span></div>',
    ]);
    ?>

    <div class="style-header-table-kartik"></div>

    <?php $form = ActiveForm::begin(['id' => 'gcp_create', 'action' => "/gcp/create?id=$confirmProblem->id"]); ?>

    <div class="row">
        <div class="col-md-12">

            <?= $form->field($formCreateGcp, 'good')->label('1. Формулировка перспективного продукта (товара / услуги):')->textInput(['maxlength' => true, 'required' => true]); ?>

            <p style="font-weight: 700;">2. Для какого сегмента предназначено: <?= Html::a($segment->name, ['#'], ['title' => 'Посмотреть описание', 'data-toggle' => 'modal', 'data-target' => '#data_segment_modal',]) ?></b></p>

            <?= $form->field($confirmProblem, 'need_consumer')->label('3. Для удовлетворения следующей потребности сегмента:')->textarea(['rows' => 1, 'readOnly' => true,]); ?>

            <p style="font-weight: 700;">4. Какую выгоду дает использование данного продукта потребителю – представителю сегмента:

                <?= Html::a('i', ['#'], [
                    'style' => [
                        'margin-left' => '10px',
                        'font-size' => '13px',
                        'font-weight' => '700',
                        'padding' => '2px 8px',
                        'background-color' => '#707F99',
                        'border-radius' => '50%',
                        'text-decoration' => 'none',
                        'color' => '#F2F2F2',
                    ],
                    'class' => 'table-kartik-link',
                    'data-toggle' => 'modal',
                    'data-target' => "#information_benefit_modal",
                    'title' => 'Посмотреть описание',
                ])?>

            </p>

            <?= $form->field($formCreateGcp, 'benefit')->label(false)->textarea(['rows' => 1, 'required' => true]); ?>

            <p style="font-weight: 700;">5. По сравнению с каким продуктом заявлена выгода (с чем сравнивается):

                <?= Html::a('i', ['#'], [
                    'style' => [
                        'margin-left' => '10px',
                        'font-size' => '13px',
                        'font-weight' => '700',
                        'padding' => '2px 8px',
                        'background-color' => '#707F99',
                        'border-radius' => '50%',
                        'text-decoration' => 'none',
                        'color' => '#F2F2F2',
                    ],
                    'class' => 'table-kartik-link',
                    'data-toggle' => 'modal',
                    'data-target' => "#information_contrast_modal",
                    'title' => 'Посмотреть описание',
                ])?>

            </p>

            <?= $form->field($formCreateGcp, 'contrast')->label(false)->textarea(['rows' => 1, 'required' => true]); ?>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>


    <?php
    Modal::end();
    ?>



    <?php
        // Модальное окно - Данные сегмента
        Modal::begin([
            'options' => [
                'id' => 'data_segment_modal',
                'class' => 'data_segment_modal',
            ],
            'size' => 'modal-lg',
            'header' => '<h3 class="text-center">Информация о сегменте</h3>',
        ]);
        ?>

        <?= $segment->allInformation; ?>

        <?php
        Modal::end();
    ?>


    <?php
    // Модальное окно - информация о заполнении выгоды
    Modal::begin([
        'options' => [
            'id' => 'information_benefit_modal',
            'class' => 'information_benefit_modal',
        ],
        'size' => 'modal-md',
        'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Информация</h3>',
    ]);
    ?>

    <div style="color: #F2F2F2; padding: 0 30px; font-size: 18px;">

        <p>Все выгоды формулируются по трем критериям:</p>

        <p>- временной фактор,</p>

        <p>- экономический фактор,</p>

        <p>- качественный фактор.</p>

        <p>Первые два параметра выгоды должны быть исчисляемыми.</p>

        <p>Параметр качества(исчисляемый /лаконичный текст).</p>

    </div>

    <?php
    Modal::end();
    ?>



    <?php
    // Модальное окно - информация о заполнении выгоды
    Modal::begin([
        'options' => [
            'id' => 'information_contrast_modal',
            'class' => 'information_contrast_modal',
        ],
        'size' => 'modal-md',
        'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Информация</h3>',
    ]);
    ?>

    <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Укажите параметры аналога, с которыми сравниваются параметры нового продукта:</h4>

    <?php
    Modal::end();
    ?>


    <?php

    foreach ($models as $model) :
    // Модальное окно - редактирование описания ГЦП
    Modal::begin([
        'options' => [
            'id' => "update_description_modal-$model->id",
            'class' => 'update_description_modal',
        ],
        'size' => 'modal-md',
        'header' => '<h3 class="text-center">Редактирование описания '. $model->title .'</h3>',
    ]);
    ?>

    <?php
        ActiveForm::begin(['id' => "form_update_description-$model->id", 'action' => "/gcp/update?id=$model->id"]);
    ?>

    <?= $form->field($model, 'description')->label(false)->textarea(['rows' => 6, 'required' => true]); ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php
        ActiveForm::end();
    ?>

    <?php

    Modal::end();
    endforeach;

    ?>




    <div style="font-style: italic;margin-left: auto;"><span class="bolder">ГЦП*</span> - гипотеза ценностного предложения.</div>

</div>


<?php

$script = "

    $(document).ready(function() {
    
        //Фон для модального окна информации при заголовке таблицы
        var information_modal_problem_view = $('#information-table-problem-view').find('.modal-content');
        information_modal_problem_view.css('background-color', '#707F99');
        
        //Фон для модального окна формы создания новой ГЦП
        var problem_create_modal = $('#problem_create_modal').find('.modal-body');
        problem_create_modal.css('background-color', '#F2F2F2');
        
        //Фон для модального окна формы редактирования описания ГЦП
        var update_description_modal = $('.update_description_modal').find('.modal-body');
        update_description_modal.css('background-color', '#F2F2F2');
        
        //Фон для модального окна информация о заполнении выгоды в форме создания новой ГЦП
        var information_benefit_modal = $('#information_benefit_modal').find('.modal-content');
        information_benefit_modal.css('background-color', '#707F99');
        
        //Фон для модального окна информация о заполнении продукта с которым идет сравнение в форме создания новой ГЦП
        var information_contrast_modal = $('#information_contrast_modal').find('.modal-content');
        information_contrast_modal.css('background-color', '#707F99');
        
        
        //Отмена перехода по ссылке
        $('a.disabled').on('click', false);
        
    });
    
    
    
";
$position = \yii\web\View::POS_READY;
$this->registerJs($script, $position);

?>


<?php

foreach ($models as $model) :

$script2 = "
    
    //Форма редактирования описания ГЦП
    $('#form_update_description-$model->id').on('beforeSubmit', function(e){
    
        var data = $(this).serialize();
        var url = $(this).attr('action');
        
        var str_description_gcp = $('tr[data-key=\"$model->id\"]').find('td[data-col-seq=\"1\"]').find('div');
    
        $.ajax({
            
            url: url,
            method: 'POST',
            data: data,
            cache: false,
            success: function(response){
            
                if(!response['error']){
                
                    //Изменить значение строки в таблице ГЦП
                    str_description_gcp.html(response.description);
                    
                    //Закрыть модальное окно с формой редактирования
                    $('#update_description_modal-". $model->id ."').modal('hide');
                
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
$this->registerJs($script2, $position);

endforeach;

?>
