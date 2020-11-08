<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use app\models\Segment;

$this->title = 'Генерация бизнес-модели';

$this->registerCssFile('@web/css/business-model-index-style.css');

?>
<div class="business-model-index">

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
            'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 link_in_the_header text-center',
            'onclick' => 'return false',
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

        <?= Html::a('<div class="stage_number">5</div><div>Разработка гипотез ценностных предложений</div>',
            ['/gcp/index', 'id' => $confirmProblem->id],
            ['class' => 'passive_navigation_block navigation_block']
        ) ;?>

        <?= Html::a('<div class="stage_number">6</div><div>Подтверждение гипотез ценностных предложений</div>',
            ['/confirm-gcp/view', 'id' => $confirmGcp->id],
            ['class' => 'passive_navigation_block navigation_block']
        ) ;?>

        <?= Html::a('<div class="stage_number">7</div><div>Разработка MVP</div>',
            ['/mvp/index', 'id' => $confirmGcp->id],
            ['class' => 'passive_navigation_block navigation_block']
        ) ;?>

        <?= Html::a('<div class="stage_number">8</div><div>Подтверждение MVP</div>',
            ['/confirm-mvp/view', 'id' => $confirmMvp->id],
            ['class' => 'passive_navigation_block navigation_block']
        ) ;?>

        <div class="active_navigation_block navigation_block">
            <div class="stage_number">9</div>
            <div>Генерация бизнес-модели</div>
        </div>

    </div>


    <div class="row segment_info_data">

        <div class="col-xs-12 col-md-12 col-lg-8 stage_name_row">

            <?php
            $segment_name = $segment->name;
            if (mb_strlen($segment_name) > 12){
                $segment_name = mb_substr($segment_name, 0, 12) . '...';
            }

            $problem_description = $generationProblem->description;
            if (mb_strlen($problem_description) > 12){
                $problem_description = mb_substr($problem_description, 0, 12) . '...';
            }

            $gcp_description = $gcp->description;
            if (mb_strlen($gcp_description) > 15){
                $gcp_description = mb_substr($gcp_description, 0, 15) . '...';
            }

            $mvp_description = $mvp->description;
            if (mb_strlen($mvp_description) > 15){
                $mvp_description = mb_substr($mvp_description, 0, 15) . '...';
            }
            ?>

            <?= Html::a('Сегмент: <div>' . $segment_name . '</div> / Проблема: <div>' . $problem_description . '</div> / ЦП: <div>' . $gcp_description . '</div> / MVP: <div>' . $mvp_description . '</div><span class="arrow_link"><span></span><span><span></span>', ['#'], ['id' => 'view_desc_stage_width_max_1900', 'onclick' => 'return false', 'class' => 'view_block_description view_desc_stage']); ?>

            <?php
            $mvp_description = $mvp->description;
            if (mb_strlen($mvp_description) > 50){
                $mvp_description = mb_substr($mvp_description, 0, 50) . '...';
            }
            ?>

            <?= Html::a('Сегмент: <div>' . $segment_name . '</div> / Проблема: <div>' . $problem_description . '</div> / ЦП: <div>' . $gcp_description . '</div> / MVP: <div>' . $mvp_description . '</div><span class="arrow_link"><span></span><span><span></span>', ['#'], ['id' => 'view_desc_stage_width_min_1900', 'onclick' => 'return false', 'class' => 'view_block_description view_desc_stage']); ?>

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
        <div>Формулировка ценностного предложения:</div>
        <div><?= $gcp->description;?></div>
        <div>Формулировка минимально жизнеспособного продукта:</div>
        <div><?= $mvp->description;?></div>
    </div>



    <div class="container-fluid container-data row">

        <?php if (empty($model)) : ?>

            <div class="container-fluid row">

                <div class="col-md-12" style="padding: 15px 0;">

                    <?=  Html::a( '<div class="new_segment_link_block"><div>' . Html::img(['@web/images/icons/add_vector.png'], ['style' => ['width' => '35px']]) . '</div><div style="padding-left: 20px;">Бизнес-модель</div></div>',
                        ['/confirm-mvp/data-availability-for-next-step', 'id' => $confirmMvp->id],
                        ['id' => 'checking_the_possibility', 'class' => 'new_segment_link_plus pull-right']
                    );
                    ?>

                </div>

            </div>

        <?php else : ?>

            <div class="row" style="margin: 0;">

                <div class="col-md-9 text_update_page">

                    <?php echo Html::a( 'Бизнес-модель' . Html::img('/images/icons/icon_export.png', ['style' => ['width' => '22px', 'margin-left' => '10px', 'margin-bottom' => '10px']]), ['/business-model/mpdf-business-model', 'id' => $model->id], [
                        'class' => 'export_link',
                        'target' => '_blank',
                        //'data-toggle'=>'tooltip',
                        'title' => 'Скачать в pdf',
                    ]);?>
                </div>

                <div class="button-update col-md-3">

                    <?= Html::button('Редактировать', [
                        'id' => 'update_business_model',
                        'class' => 'btn btn-default',
                        'style' => [
                            'color' => '#FFFFFF',
                            'background' => '#52BE7F',
                            'padding' => '0 7px',
                            'width' => '190px',
                            'height' => '40px',
                            'font-size' => '24px',
                            'border-radius' => '8px',
                        ],
                        'data-toggle' => 'modal',
                        'data-target' => '#business_model_update_modal',
                    ])?>
                </div>
            </div>

            <div class="blocks_business_model">

                <div class="block_20_business_model">

                    <div class="desc_block_20">
                        <h5>Ключевые партнеры</h5>
                        <div id="view_partners"><?= $model->partners; ?></div>
                    </div>

                </div>

                <div class="block_20_business_model">

                    <div class="desc_block_20">

                        <h5>Ключевые направления</h5>

                        <div class="mini_header_desc_block">Тип взаимодейстивия с рынком:</div>
                        <?php
                        if ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2C) {
                            echo 'В2С (бизнес-клиент)';
                        } else {
                            echo 'B2B (бизнес-бизнес)';
                        }
                        ?>

                        <div class="mini_header_desc_block">Сфера деятельности:</div>
                        <?= $segment->field_of_activity; ?>

                        <div class="mini_header_desc_block">Вид деятельности:</div>
                        <?= $segment->sort_of_activity; ?>

                        <div class="mini_header_desc_block">Специализация вида деятельности:</div>
                        <?= $segment->specialization_of_activity; ?>

                    </div>

                    <div class="desc_block_20">
                        <h5>Ключевые ресурсы</h5>
                        <div id="view_resources"><?= $model->resources; ?></div>
                    </div>

                </div>

                <div class="block_20_business_model">

                    <div class="desc_block_20">
                        <h5>Ценностное предложение</h5>
                        <?= $gcp->description; ?>
                    </div>

                </div>

                <div class="block_20_business_model">

                    <div class="desc_block_20">
                        <h5>Взаимоотношения с клиентами</h5>
                        <div id="view_relations"><?= $model->relations; ?></div>
                    </div>

                    <div class="desc_block_20">
                        <h5>Каналы коммуникации и сбыта</h5>
                        <div id="view_distribution_of_sales"><?= $model->distribution_of_sales; ?></div>
                    </div>

                </div>

                <div class="block_20_business_model">

                    <div class="desc_block_20">

                        <h5>Потребительский сегмент</h5>

                        <div class="mini_header_desc_block">Наименование:</div>
                        <?= $segment->name; ?>

                        <div class="mini_header_desc_block">Краткое описание:</div>
                        <?= $segment->description; ?>

                        <div class="mini_header_desc_block">Потенциальное количество потребителей:</div>
                        <?= ' от ' . number_format($segment->quantity_from * 1000, 0, '', ' ') .
                        ' до ' . number_format($segment->quantity_to * 1000, 0, '', ' ') . ' человек'; ?>

                        <div class="mini_header_desc_block">Объем рынка:</div>
                        <?= number_format($segment->market_volume * 1000000, 0, '', ' ') . ' рублей'; ?>

                    </div>
                </div>

            </div>

            <div class="blocks_business_model">

                <div class="block_50_business_model">

                    <div class="desc_block_50">
                        <h5>Структура издержек</h5>
                        <div id="view_cost"><?= $model->cost; ?></div>
                    </div>

                </div>

                <div class="block_50_business_model">

                    <div class="desc_block_50">
                        <h5>Потоки поступления доходов</h5>
                        <div id="view_revenue"><?= $model->revenue; ?></div>
                    </div>

                </div>

            </div>

        <?php endif; ?>

    </div>


    <?php
    // Модальное окно для создания Бизнес-модели
    Modal::begin([
        'options' => [
            'id' => 'business_model_create_modal',
            'class' => 'business_model_create_modal',
        ],
        'size' => 'modal-lg',
        'header' => '<h3 class="text-center">Внесите данные для создания бизнес-модели</h3>',
    ]);
    ?>

    <?php
    $form = ActiveForm::begin([
        'id' => 'business_model_create',
        'action' => "/business-model/create?id=$confirmMvp->id",
        'options' => ['class' => 'g-py-15'],
        'errorCssClass' => 'u-has-error-v1',
        'successCssClass' => 'u-has-success-v1-1',
    ]);
    ?>

    <div class="row">

        <div class="col-md-12" style="margin-top: 10px;">

            <?= $form->field($newModel, 'partners', ['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->textarea([
                'rows' => 1,
                'maxlength' => true,
                'required' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => '',
            ]);
            ?>

        </div>

        <div class="col-md-12" style="margin-top: 10px;">

            <?= $form->field($newModel, 'resources', ['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->textarea([
                'rows' => 1,
                'maxlength' => true,
                'required' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => '',
            ]);
            ?>

        </div>

        <div class="col-md-12" style="margin-top: 10px;">

            <?= $form->field($newModel, 'relations', ['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->textarea([
                'rows' => 1,
                'maxlength' => true,
                'required' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => '',
            ]);
            ?>

        </div>

        <div class="col-md-12" style="margin-top: 10px;">

            <?= $form->field($newModel, 'distribution_of_sales', ['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->textarea([
                'rows' => 1,
                'maxlength' => true,
                'required' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => '',
            ]);
            ?>

        </div>

        <div class="col-md-12" style="margin-top: 10px;">

            <?= $form->field($newModel, 'cost', ['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->textarea([
                'rows' => 1,
                'maxlength' => true,
                'required' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => '',
            ]);
            ?>

        </div>

        <div class="col-md-12" style="margin-top: 10px;">

            <?= $form->field($newModel, 'revenue', ['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->textarea([
                'rows' => 1,
                'maxlength' => true,
                'required' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => '',
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

    <?php ActiveForm::end(); ?>


    <?php Modal::end(); ?>



    <!--Если существует Бизнес-модель-->
    <?php if (!empty($model)) : ?>

        <?php
        // Модальное окно редактирования Бизнес-модели
        Modal::begin([
            'options' => [
                'id' => 'business_model_update_modal',
                'class' => 'business_model_update_modal',
            ],
            'size' => 'modal-lg',
            'header' => '<h3 class="text-center">Редактирование бизнес-модели</h3>',
        ]);
        ?>

        <?php
        $form = ActiveForm::begin([
            'id' => 'business_model_update',
            'action' => "/business-model/update?id=$model->id",
            'options' => ['class' => 'g-py-15'],
            'errorCssClass' => 'u-has-error-v1',
            'successCssClass' => 'u-has-success-v1-1',
        ]);
        ?>

        <div class="row">

            <div class="col-md-12" style="margin-top: 10px;">

                <?= $form->field($model, 'partners', ['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->textarea([
                    'rows' => 1,
                    'maxlength' => true,
                    'required' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                ]);
                ?>

            </div>

            <div class="col-md-12" style="margin-top: 10px;">

                <?= $form->field($model, 'resources', ['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->textarea([
                    'rows' => 1,
                    'maxlength' => true,
                    'required' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                ]);
                ?>

            </div>

            <div class="col-md-12" style="margin-top: 10px;">

                <?= $form->field($model, 'relations', ['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->textarea([
                    'rows' => 1,
                    'maxlength' => true,
                    'required' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                ]);
                ?>

            </div>

            <div class="col-md-12" style="margin-top: 10px;">

                <?= $form->field($model, 'distribution_of_sales', ['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->textarea([
                    'rows' => 1,
                    'maxlength' => true,
                    'required' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                ]);
                ?>

            </div>

            <div class="col-md-12" style="margin-top: 10px;">

                <?= $form->field($model, 'cost', ['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->textarea([
                    'rows' => 1,
                    'maxlength' => true,
                    'required' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                ]);
                ?>

            </div>

            <div class="col-md-12" style="margin-top: 10px;">

                <?= $form->field($model, 'revenue', ['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->textarea([
                    'rows' => 1,
                    'maxlength' => true,
                    'required' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
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

        <?php ActiveForm::end(); ?>


        <?php Modal::end(); ?>

    <?php endif; ?>




    <?php
    // Модальное окно - сообщение о том что данных недостаточно для создания Бизнес-модели
    Modal::begin([
        'options' => [
            'id' => 'business_model_create_modal_error',
            'class' => 'business_model_create_modal_error',
        ],
        'size' => 'modal-md',
        'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 25px;">Недостаточно данных для создания бизнес-модели.</h3>',
    ]);
    ?>

    <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
        Вернитесь к подтверждению продукта (MVP).
    </h4>

    <?php Modal::end(); ?>


</div>



<?php

$script = "

    $(document).ready(function() {
        
        //Фон для модального окна (при создании Бизнес-модели - недостаточно данных)
        var business_model_create_modal_error = $('#business_model_create_modal_error').find('.modal-content');
        business_model_create_modal_error.css('background-color', '#707F99');
        
        
        
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
    
    
    
    
    //При попытке добавить Бизнес-модель проверяем существуют ли необходимые данные
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
                    $('#business_model_create_modal').modal('show');
                }else{
                    $('#business_model_create_modal_error').modal('show');
                }
            },
            error: function(){
                alert('Ошибка');
            }
        });
        
        //e.preventDefault();
        return false;
    });
    
    
    //При сохранинии данных из формы создания бизнес-модели
    $('#business_model_create').on('beforeSubmit', function(e){
    
        var data = $(this).serialize();
        var url = $(this).attr('action');
        
        $.ajax({
        
            url: url,
            method: 'POST',
            data: data,
            cache: false,
            success: function(response){
            
                if (!response['error']) {
                    
                    //Закрываем окно создания бизнес-модели
                    $('#business_model_create_modal').modal('hide');
                    
                    //Назначаем перезагрузку
                    location.reload();
                }
            
            }, error: function(){
                alert('Ошибка');
            }
        });
    
        e.preventDefault();
        return false;
    });
    
    //Форма редактирования Бизнес-модели
    $('#business_model_update').on('beforeSubmit', function(e){
    
        var data = $(this).serialize();
        var url = $(this).attr('action');
    
        $.ajax({
            
            url: url,
            method: 'POST',
            data: data,
            cache: false,
            success: function(response){
            
                if(!response['error']){
                
                    //Закрываем окно редактирования
                    $('#business_model_update_modal').modal('hide');
                    
                    //Обновляем данные на странице
                    $('#view_partners').html(response.model.partners);
                    $('#view_resources').html(response.model.resources);
                    $('#view_relations').html(response.model.relations);
                    $('#view_distribution_of_sales').html(response.model.distribution_of_sales);
                    $('#view_cost').html(response.model.cost);
                    $('#view_revenue').html(response.model.revenue);
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
