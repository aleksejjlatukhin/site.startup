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

$this->title = 'Разработка гипотез ценностных предложений';

$this->registerCssFile('@web/css/gcp-index-style.css');

?>
<div class="gcp-index">


    <div class="row project_info_data">


        <div class="col-xs-12 col-md-12 col-lg-4 project_name">
            <span>Проект:</span>
            <?= $project->project_name; ?>
        </div>

        <?= Html::a('Данные проекта', ['/projects/show-all-information', 'id' => $project->id], [
            'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 openAllInformationProject link_in_the_header',
        ]) ?>

        <?= Html::a('Протокол проекта', ['/projects/report', 'id' => $project->id], [
            'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 link_in_the_header text-center',
            'onclick' => 'return false',
        ]) ?>

        <?= Html::a('Дорожная карта проекта', ['/projects/show-roadmap', 'id' => $project->id], [
            'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 openRoadmapProject link_in_the_header text-center',
        ]) ?>

        <?= Html::a('Сводная таблица проекта', ['/projects/result', 'id' => $project->id], [
            'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 openResultTableProject link_in_the_header text-center',
        ]) ?>

    </div>



    <div class="row navigation_blocks">

        <?= Html::a('<div class="stage_number">1</div><div>Генерация гипотез целевых сегментов</div>',
            ['/segment/index', 'id' => $project->id],
            ['class' => 'passive_navigation_block navigation_block']
        ) ;?>

        <?= Html::a('<div class="stage_number">2</div><div>Подтверждение гипотез целевых сегментов</div>',
            ['/interview/view', 'id' => $interview->id],
            ['class' => 'passive_navigation_block navigation_block']
        ) ;?>

        <?= Html::a('<div class="stage_number">3</div><div>Генерация гипотез проблем сегментов</div>',
            ['/generation-problem/index', 'id' => $interview->id],
            ['class' => 'passive_navigation_block navigation_block']
        ) ;?>

        <?= Html::a('<div class="stage_number">4</div><div>Подтверждение гипотез проблем сегментов</div>',
            ['/confirm-problem/view', 'id' => $confirmProblem->id],
            ['class' => 'passive_navigation_block navigation_block']
        ) ;?>

        <div class="active_navigation_block navigation_block">
            <div class="stage_number">5</div>
            <div>Разработка гипотез ценностных предложений</div>
        </div>

        <div class="no_transition_navigation_block navigation_block">
            <div class="stage_number">6</div>
            <div>Подтверждение гипотез ценностных предложений</div>
        </div>

        <div class="no_transition_navigation_block navigation_block">
            <div class="stage_number">7</div>
            <div>Разработка MVP</div>
        </div>

        <div class="no_transition_navigation_block navigation_block">
            <div class="stage_number">8</div>
            <div>Подтверждение MVP</div>
        </div>

        <div class="no_transition_navigation_block navigation_block">
            <div class="stage_number">9</div>
            <div>Генерация бизнес-модели</div>
        </div>

    </div>



    <div class="row segment_info_data">

        <div class="col-xs-12 col-md-12 col-lg-8 stage_name_row">

            <?php
            $segment_name = $segment->name;
            if (mb_strlen($segment_name) > 15){
                $segment_name = mb_substr($segment_name, 0, 15) . '...';
            }

            $problem_description = $generationProblem->description;
            if (mb_strlen($problem_description) > 50){
                $problem_description = mb_substr($problem_description, 0, 50) . '...';
            }
            ?>

            <?= Html::a('Сегмент: <div>' . $segment_name . '</div> / Проблема: <div>' . $problem_description . '</div><span class="arrow_link"><span></span><span><span></span>', ['#'], ['id' => 'view_desc_stage_width_max_1900', 'onclick' => 'return false', 'class' => 'view_block_description view_desc_stage']); ?>

            <?php
            $problem_description = $generationProblem->description;
            if (mb_strlen($problem_description) > 100){
                $problem_description = mb_substr($problem_description, 0, 100) . '...';
            }
            ?>

            <?= Html::a('Сегмент: <div>' . $segment_name . '</div> / Проблема: <div>' . $problem_description . '</div><span class="arrow_link"><span></span><span><span></span>', ['#'], ['id' => 'view_desc_stage_width_min_1900', 'onclick' => 'return false', 'class' => 'view_block_description view_desc_stage']); ?>

        </div>

        <?= Html::a('Данные сегмента', ['/segment/show-all-information', 'id' => $segment->id], [
            'class' => 'col-xs-12 col-sm-6 col-md-6 col-lg-2 openAllInformationSegment link_in_the_header',
        ]) ?>

        <?= Html::a('Дорожная карта сегмента', ['/segment/show-roadmap', 'id' => $segment->id], [
            'class' => 'col-xs-12 col-sm-6 col-md-6 col-lg-2 openRoadmapSegment link_in_the_header text-center',
        ]) ?>

    </div>


    <div class="row block_description_stage">
        <div>Наименование сегмента:</div>
        <div><?= $segment->name;?></div>
        <div>Формулировка проблемы:</div>
        <div><?= $generationProblem->description;?></div>
    </div>



    <div class="container-fluid container-data row">

        <div class="container-fluid row">

            <div class="col-md-12" style="padding: 15px 0;">

                <?=  Html::a( '<div class="new_segment_link_block"><div>' . Html::img(['@web/images/icons/add_vector.png'], ['style' => ['width' => '35px']]) . '</div><div style="padding-left: 20px;">Новое ценностное предложение</div></div>',
                    ['/confirm-problem/data-availability-for-next-step', 'id' => $confirmProblem->id],
                    ['id' => 'checking_the_possibility', 'class' => 'new_segment_link_plus pull-right']
                );
                ?>

            </div>

        </div>


        <!--Заголовки для списка проблем-->
        <div class="row headers_data_problem" style="margin: 0; padding: 10px; padding-top: 0;">

            <div class="col-md-1 ">
                <div class="row">
                    <div class="col-md-4" style="padding: 0;"></div>
                    <div class="col-md-8" style="padding: 0;">Обознач.</div>
                </div>

            </div>

            <div class="col-md-7">Описание гипотезы ценностного предложения</div>

            <div class="col-md-1 text-center"><div>Дата создания</div></div>

            <div class="col-md-1 text-center header_date_confirm"><div>Дата подтв.</div></div>

            <div class="col-md-2"></div>

        </div>


        <div class="block_all_problems_segment row" style="padding-left: 10px; padding-right: 10px;">

            <!--Данные для списка ценностных предложений-->
            <?php foreach ($models as $model) : ?>

                <div class="row container-one_hypothesis row_hypothesis-<?= $model->id;?>" style="margin: 3px 0; padding: 10px;">

                    <div class="col-md-1">
                        <div class="row">

                            <div class="col-md-4" style="padding: 0;">

                                <?php
                                if ($model->exist_confirm === 1) {

                                    echo '<div class="" style="padding: 0 5px;">' . Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px',]]) . '</div>';

                                }elseif ($model->exist_confirm === null && empty($model->confirm)) {

                                    echo '<div class="" style="padding: 0 5px;">' . Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]) . '</div>';

                                }elseif ($model->exist_confirm === null && !empty($model->confirm)) {

                                    echo '<div class="" style="padding: 0 5px;">' . Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]) . '</div>';

                                }elseif ($model->exist_confirm === 0) {

                                    echo '<div class="" style="padding: 0 5px;">' . Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px',]]) . '</div>';

                                }
                                ?>

                            </div>

                            <div class="col-md-8 hypothesis_title" style="padding: 0 0 0 5px;">

                                <?= $model->title; ?>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-7" id="column_gcp_description-<?=$model->id;?>">

                        <?php
                        $gcp_desc = $model->description;
                        if (mb_strlen($gcp_desc) > 180) {
                            $gcp_desc = mb_substr($gcp_desc, 0, 180) . '...';
                        }
                        ?>

                        <?= '<div title="'.$model->description.'" style="line-height: 21px;">' . $gcp_desc . '</div>'?>

                    </div>

                    <div class="col-md-1 text-center">

                        <?= date("d.m.y", $model->created_at); ?>

                    </div>

                    <div class="col-md-1 text-center">

                        <?php if ($model->time_confirm) : ?>
                            <?= date("d.m.y", $model->time_confirm); ?>
                        <?php endif; ?>

                    </div>

                    <div class="col-md-2">

                        <div class="row pull-right" style="padding-right: 10px; display:flex; align-items: center;">

                            <div style="margin-right: 25px;">

                                <?php if ($model->confirm) : ?>

                                    <?= Html::a('Далее', ['/confirm-gcp/view', 'id' => $model->confirm->id], [
                                        'class' => 'btn btn-default',
                                        'style' => [
                                            'display' => 'flex',
                                            'align-items' => 'center',
                                            'justify-content' => 'center',
                                            'color' => '#FFFFFF',
                                            'background' => '#52BE7F',
                                            'width' => '120px',
                                            'height' => '40px',
                                            'font-size' => '18px',
                                            'border-radius' => '8px',
                                        ]
                                    ]);
                                    ?>

                                <?php else : ?>

                                    <?= Html::a('Подтвердить', ['/confirm-gcp/create', 'id' => $model->id], [
                                        'class' => 'btn btn-default',
                                        'style' => [
                                            'display' => 'flex',
                                            'align-items' => 'center',
                                            'justify-content' => 'center',
                                            'color' => '#FFFFFF',
                                            'background' => '#707F99',
                                            'width' => '120px',
                                            'height' => '40px',
                                            'font-size' => '18px',
                                            'border-radius' => '8px',
                                        ]
                                    ]);
                                    ?>

                                <?php endif; ?>

                            </div>

                            <div>

                                <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

                                    <?= Html::a(Html::img('/images/icons/icon_update.png', ['style' => ['width' => '24px', 'margin-right' => '20px']]),['#'], [
                                        'class' => '',
                                        'title' => 'Редактировать',
                                        'data-toggle' => 'modal',
                                        'data-target' => '#update_description_modal-' . $model->id,
                                    ]); ?>

                                <?php endif; ?>

                            </div>

                            <div >

                                <?= Html::a(Html::img('/images/icons/icon_delete.png', ['style' => ['width' => '24px']]),['/gcp/delete', 'id' => $model->id], [
                                    'class' => 'delete_hypothesis',
                                    'title' => 'Удалить',
                                ]); ?>

                            </div>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </div>


    <?php if (count($models) > 0) : ?>

        <div class="row information_status_confirm">

            <div>

                <div style="display:flex; align-items: center;">
                    <?= Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px', 'margin-right' => '8px']]);?>
                    <div>Ценностное предложение подтверждено</div>
                </div>

                <div style="display:flex; align-items: center;">
                    <?= Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px', 'margin-right' => '8px']]);?>
                    <div>Ценностное предложение не подтверждено</div>
                </div>

                <div style="display:flex; align-items: center;">
                    <?= Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px', 'margin-right' => '8px']]);?>
                    <div>Ценностное предложение ожидает подтверждения</div>
                </div>

            </div>

        </div>

    <?php endif; ?>



    <?php
    // Модальное окно - создание ГЦП
    Modal::begin([
        'options' => [
            'id' => 'gcp_create_modal',
            'class' => 'gcp_create_modal',
        ],
        'size' => 'modal-lg',
        'header' => '<div style="display:flex; align-items: center; justify-content: center; font-weight: 700;"><span style="font-size: 24px; color: #4F4F4F; padding-right: 10px;">Создание гипотезы ценностного предложения</span>' . Html::a(Html::img('/images/icons/icon_info.png'), ['#'], [
                'data-toggle' => 'modal',
                'data-target' => "#information_gcp_create",
                'title' => 'Посмотреть описание',
            ]) . '</div>',
    ]);
    ?>



    <?php
        $form = ActiveForm::begin([
            'id' => 'gcp_create',
            'action' => "/gcp/create?id=$confirmProblem->id",
            'options' => ['class' => 'g-py-15'],
            'errorCssClass' => 'u-has-error-v1',
            'successCssClass' => 'u-has-success-v1-1',
        ]);
    ?>

    <div class="row" style="color: #4F4F4F;">


        <div class="col-md-12" style="margin-top: 10px;">

            <?= $form->field($formCreateGcp, 'good', ['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->label('Формулировка перспективного продукта (товара / услуги):')->textInput([
                'maxlength' => 255,
                'required' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => '',
            ]); ?>

        </div>


        <div class="col-md-12" style="padding-left: 20px; font-weight: 700;">

            Для какого сегмента предназначено:
            <span class="gcp_create_segment_link">
                <?= Html::a('Данные сегмента', ['/segment/show-all-information', 'id' => $segment->id], [
                    'class' => 'openAllInformationSegment',
                    'title' => 'Посмотреть описание',
                ]) ?>
            </span>

        </div>


        <div class="col-md-12" style="padding-left: 20px; margin-top: 10px;">

            <div style="font-weight: 700;">
                Для удовлетворения следующей потребности сегмента:
            </div>

            <div><?= $confirmProblem->need_consumer;?></div>

        </div>


        <div class="col-md-12" style="margin-top: 10px;">

            <?= $form->field($formCreateGcp, 'benefit', ['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->label('Какую выгоду дает использование данного продукта потребителю (представителю сегмента):')->textarea([
                'rows' => 2,
                'maxlength' => 255,
                'required' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => 'Все выгоды формулируются по трем критериям: временной, экономический и качественный факторы.
Первые два параметра выгоды должны быть исчисляемыми. Параметр качества(исчисляемый /лаконичный текст).',
            ]);
            ?>

        </div>


        <div class="col-md-12">

            <?= $form->field($formCreateGcp, 'contrast', ['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->label('По сравнению с каким продуктом заявлена выгода (с чем сравнивается):')->textarea([
                'rows' => 1,
                'maxlength' => 255,
                'required' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => 'Укажите параметры аналога, с которыми сравниваются параметры нового продукта',
            ]); ?>

        </div>


    </div>

    <div class="form-group row container-fluid">
        <?= Html::submitButton('Сохранить', [
            'class' => 'btn btn-success pull-right',
            'style' => [
                'color' => '#FFFFFF',
                'background' => '#52BE7F',
                'padding' => '0 7px',
                'width' => '140px',
                'height' => '40px',
                'font-size' => '24px',
                'border-radius' => '8px',
            ]
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>



    <?php Modal::end(); ?>


    <?php
    // Описание выполнения задачи при создании ГЦП
    Modal::begin([
        'options' => [
            'id' => 'information_gcp_create',
        ],
        'size' => 'modal-md',
        'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Информация</h3>',
    ]);
    ?>

    <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
        Сгенерируйте гипотезу ценностного предложения и отредактируйте её по грамматическому смыслу.
    </h4>

    <?php
    Modal::end();
    ?>


    <?php
    // Модальное окно - сообщение о том что данных недостаточно для создания ГЦП
    Modal::begin([
        'options' => [
            'id' => 'gcp_create_modal_error',
            'class' => 'gcp_create_modal_error',
        ],
        'size' => 'modal-md',
        'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Недостаточно данных для создания ГЦП.</h3>',
    ]);
    ?>

    <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
        Вернитесь к подтверждению проблемы сегмента.
    </h4>

    <?php Modal::end(); ?>



    <?php foreach ($models as $model) : ?>

        <?php
        // Модальное окно - редактирование описания ГЦП
        Modal::begin([
            'options' => [
                'id' => "update_description_modal-$model->id",
                'class' => 'update_description_modal',
            ],
            'size' => 'modal-lg',
            'header' => '<h3 class="text-center" style="color: #4F4F4F; font-weight: 700;">Редактирование гипотезы ценностного предложения - '. $model->title .'</h3>',
        ]);
        ?>

        <?php
        $form = ActiveForm::begin([
            'id' => "form_update_description-$model->id",
            'action' => "/gcp/update?id=$model->id",
            'options' => ['class' => 'g-py-15 gcpUpdateForm'],
            'errorCssClass' => 'u-has-error-v1',
            'successCssClass' => 'u-has-success-v1-1',
            ]);
        ?>

        <div class="row" style="color: #4F4F4F;">
            <div class="col-md-12">

                <?= $form->field($model, 'description', ['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->label('Описание гипотезы ценностного предложения')->textarea([
                    'rows' => 4,
                    'maxlength' => 1000,
                    'required' => true,
                    'class' => 'style_form_field_respond form-control',
                ]);
                ?>

            </div>
        </div>

        <div class="form-group row container-fluid">
            <?= Html::submitButton('Сохранить', [
                'class' => 'btn btn-success pull-right',
                'style' => [
                    'color' => '#FFFFFF',
                    'background' => '#52BE7F',
                    'padding' => '0 7px',
                    'width' => '140px',
                    'height' => '40px',
                    'font-size' => '24px',
                    'border-radius' => '8px',
                ]
            ]) ?>
        </div>

        <?php
        ActiveForm::end();
        ?>

        <?php Modal::end(); ?>

    <?php endforeach; ?>


</div>


<?php

$script = "

    $(document).ready(function() {
    
        //Фон для модального окна информации в заголовке при создании ГЦП
        var information_gcp_create_modal = $('#information_gcp_create').find('.modal-content');
        information_gcp_create_modal.css('background-color', '#707F99');
        
        //Фон для модального окна (при создании ГЦП - недостаточно данных)
        var gcp_create_modal_error = $('#gcp_create_modal_error').find('.modal-content');
        gcp_create_modal_error.css('background-color', '#707F99');
        
        
        
        //Возвращение скролла первого модального окна после закрытия второго
        $('.modal').on('hidden.bs.modal', function (e) {
            if($('.modal:visible').length)
            {
                $('.modal-backdrop').first().css('z-index', parseInt($('.modal:visible').last().css('z-index')) - 10);
                $('body').addClass('modal-open');
            }
        }).on('show.bs.modal', function (e) {
            if($('.modal:visible').length)
            {
                $('.modal-backdrop.in').first().css('z-index', parseInt($('.modal:visible').last().css('z-index')) + 10);
                $(this).css('z-index', parseInt($('.modal-backdrop.in').first().css('z-index')) + 10);
            }
        });
        
    });
    
    
    
    
    //При попытке добавить ГЦП проверяем существуют ли необходимые данные
    //Если данных достаточно - показываем окно с формой
    //Если данных недостаточно - показываем окно с сообщением error
    $('#checking_the_possibility').on('click', function(){
    
        var url = $(this).attr('href');
        
        $.ajax({
        
            url: url,
            method: 'POST',
            cache: false,
            success: function(response){
                if(response['success']){
                    $('#gcp_create_modal').modal('show');
                }else{
                    $('#gcp_create_modal_error').modal('show');
                }
            },
            error: function(){
                alert('Ошибка');
            }
        });
        
        //e.preventDefault();
        return false;
    });
    
    
    
    //Форма редактирования описания ГЦП
    $('.gcpUpdateForm').on('beforeSubmit', function(e){
    
        var id = $(this).attr('id');
        id = id.split('-');
        id = id[1];
    
        var data = $(this).serialize();
        var url = '/gcp/update?id=' + id;
    
        $.ajax({
            
            url: url,
            method: 'POST',
            data: data,
            cache: false,
            success: function(response){
            
                if(!response['error']){
                
                    $('#update_description_modal-' + id).modal('hide');

                    var description = response.description;
                
                    if (description.length > 180) {
                        description = description.substring(0, 180) + '...';
                    }
                    
                    var column = $('#column_gcp_description-' + id).html('<\div title=\"' + response.description + '\">' + description + '<\/div>');
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
