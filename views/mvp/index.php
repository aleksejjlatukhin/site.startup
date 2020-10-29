<?php

use yii\helpers\Html;
use app\models\User;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Разработка MVP';

$this->registerCssFile('@web/css/mvp-index-style.css');
?>

<div class="mvp-index">

    <div class="row project_info_data">


        <div class="col-xs-12 col-md-12 col-lg-4 project_name">
            <span>Проект:</span>
            <?= $project->project_name; ?>
        </div>

        <?= Html::a('Данные проекта', ['#'], [
            'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 link_in_the_header',
            'data-toggle' => 'modal',
            'data-target' => "#data_project_modal",
        ]) ?>

        <?= Html::a('Протокол проекта', ['/projects/report', 'id' => $project->id], [
            'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 link_in_the_header text-center',
            'onclick' => 'return false',
        ]) ?>

        <?= Html::a('Дорожная карта проекта', ['#'], [
            'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 link_in_the_header text-center',
            'data-toggle' => 'modal',
            'data-target' => "#showRoadmapProject",
        ]) ?>

        <?= Html::a('Сводная таблица проекта', ['/projects/result', 'id' => $project->id], [
            'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 link_in_the_header text-center',
            'onclick' => 'return false',
        ]) ?>

    </div>


    <?php
    // Модальное окно - данные проекта
    Modal::begin([
        'options' => [
            'id' => 'data_project_modal',
        ],
        'size' => 'modal-lg',
        'header' => '<h3 class="text-center">Исходные данные по проекту</h3>',
    ]);
    ?>

    <?= \yii\widgets\DetailView::widget([
        'model' => $project,
        //'options' => ['class' => 'table table-bordered detail-view'], //Стилизация таблицы
        'attributes' => [

            'project_name',
            'project_fullname:ntext',
            'description:ntext',
            'rid',
            'core_rid:ntext',
            'patent_number',

            [
                'attribute' => 'patent_date',
                'format' => ['date', 'dd.MM.yyyy'],
            ],

            'patent_name:ntext',

            [
                'attribute'=>'Команда проекта',
                'value' => $project->getAuthorInfo($project),
                'format' => 'html',
            ],

            'technology',
            'layout_technology:ntext',
            'register_name',

            [
                'attribute' => 'register_date',
                'format' => ['date', 'dd.MM.yyyy'],
            ],

            'site',
            'invest_name',

            [
                'attribute' => 'invest_date',
                'format' => ['date', 'dd.MM.yyyy'],
            ],

            [
                'attribute' => 'invest_amount',
                'value' => function($project){
                    if($project->invest_amount !== null){
                        return number_format($project->invest_amount, 0, '', ' ');
                    }
                },
            ],

            [
                'attribute' => 'date_of_announcement',
                'format' => ['date', 'dd.MM.yyyy'],
            ],

            'announcement_event',

            [
                'attribute' => 'created_at',
                'format' => ['date', 'dd.MM.yyyy'],
            ],

            [
                'attribute' => 'updated_at',
                'format' => ['date', 'dd.MM.yyyy'],
            ],

            [
                'attribute' => 'pre_files',
                'label' => 'Презентационные файлы',
                'value' => function($model){
                    $string = '';
                    foreach ($model->preFiles as $file){
                        $string .= Html::a($file->file_name, ['/projects/download', 'id' => $file->id], ['class' => '']) . '<br>';
                    }
                    return $string;
                },
                'format' => 'html',
            ]

        ],
    ]) ?>

    <?php
    Modal::end();
    ?>



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

        <div class="active_navigation_block navigation_block">
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
            if (mb_strlen($problem_description) > 15){
                $problem_description = mb_substr($problem_description, 0, 15) . '...';
            }

            $gcp_description = $gcp->description;
            if (mb_strlen($gcp_description) > 35){
                $gcp_description = mb_substr($gcp_description, 0, 35) . '...';
            }
            ?>

            <?= Html::a('Сегмент: <div>' . $segment_name . '</div> / Проблема: <div>' . $problem_description . '</div> / ЦП: <div>' . $gcp_description . '</div><span class="arrow_link"><span></span><span><span></span>', ['#'], ['id' => 'view_desc_stage_width_max_1900', 'onclick' => 'return false', 'class' => 'view_block_description view_desc_stage']); ?>

            <?php
            $gcp_description = $gcp->description;
            if (mb_strlen($gcp_description) > 80){
                $gcp_description = mb_substr($gcp_description, 0, 80) . '...';
            }
            ?>

            <?= Html::a('Сегмент: <div>' . $segment_name . '</div> / Проблема: <div>' . $problem_description . '</div> / ЦП: <div>' . $gcp_description . '</div><span class="arrow_link"><span></span><span><span></span>', ['#'], ['id' => 'view_desc_stage_width_min_1900', 'onclick' => 'return false', 'class' => 'view_block_description view_desc_stage']); ?>

        </div>

        <?= Html::a('Данные сегмента', ['#'], [
            'class' => 'col-xs-12 col-sm-6 col-md-6 col-lg-2 link_in_the_header',
            'data-toggle' => 'modal',
            'data-target' => '#data_segment_modal',
        ]) ?>

        <?= Html::a('Дорожная карта сегмента', ['#'], [
            'class' => 'col-xs-12 col-sm-6 col-md-6 col-lg-2 link_in_the_header text-center',
            'data-toggle' => 'modal',
            'data-target' => "#showRoadmapSegment",
        ]) ?>

    </div>


    <div class="row block_description_stage">
        <div>Наименование сегмента:</div>
        <div><?= $segment->name;?></div>
        <div>Формулировка проблемы:</div>
        <div><?= $generationProblem->description;?></div>
        <div>Формулировка ценностного предложения:</div>
        <div><?= $gcp->description;?></div>
    </div>


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



    <div class="container-fluid container-data row">

        <div class="container-fluid row">

            <div class="col-md-12" style="padding: 15px 0;">

                <?=  Html::a( '<div class="new_segment_link_block"><div>' . Html::img(['@web/images/icons/add_vector.png'], ['style' => ['width' => '35px']]) . '</div><div style="padding-left: 20px;">Новый продукт (MVP)</div></div>',
                    ['/confirm-gcp/data-availability-for-next-step', 'id' => $confirmGcp->id],
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
                    <div class="col-md-8" style="padding: 0;">Номер</div>
                </div>

            </div>

            <div class="col-md-7">Описание минимально жизнеспособного продукта</div>

            <div class="col-md-1 text-center"><div>Дата создания</div></div>

            <div class="col-md-1 text-center header_date_confirm"><div>Дата подтв.</div></div>

            <div class="col-md-2"></div>

        </div>


        <div class="block_all_problems_segment row" style="padding-left: 10px; padding-right: 10px;">

            <!--Данные для списка MVP -->
            <?php foreach ($models as $model) : ?>

                <div class="row container-one_hypothesis" style="margin: 3px 0; padding: 10px;">

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

                            <div class="col-md-8" style="padding: 0 0 0 5px;">

                                <?= $model->title; ?>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-7" id="column_mvp_description-<?=$model->id;?>">

                        <?php
                        $mvp_desc = $model->description;
                        if (mb_strlen($mvp_desc) > 180) {
                            $mvp_desc = mb_substr($mvp_desc, 0, 180) . '...';
                        }
                        ?>

                        <?= '<div title="'.$model->description.'" style="line-height: 21px;">' . $mvp_desc . '</div>'?>

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

                                    <?= Html::a('Далее', ['/confirm-mvp/view', 'id' => $model->confirm->id], [
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

                                    <?= Html::a('Подтвердить', ['/confirm-mvp/create', 'id' => $model->id], [
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

                                <?= Html::a(Html::img('/images/icons/icon_delete.png', ['style' => ['width' => '24px']]),['#'], [
                                    'class' => '',
                                    'title' => 'Удалить',
                                    'onclick' => 'return false',
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
                    <div>MVP подтвержден</div>
                </div>

                <div style="display:flex; align-items: center;">
                    <?= Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px', 'margin-right' => '8px']]);?>
                    <div>MVP не подтвержден</div>
                </div>

                <div style="display:flex; align-items: center;">
                    <?= Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px', 'margin-right' => '8px']]);?>
                    <div>MVP ожидает подтверждения</div>
                </div>

            </div>

        </div>

    <?php endif; ?>



    <?php
    // Модальное окно - создание MVP
    Modal::begin([
        'options' => [
            'id' => 'mvp_create_modal',
            'class' => 'mvp_create_modal',
        ],
        'size' => 'modal-lg',
        'header' => '<div class="text-center" style="font-size: 24px; color: #4F4F4F; font-weight: 700;">Сформулируйте описание продукта (MVP)</div>',
    ]);
    ?>



    <?php
    $form = ActiveForm::begin([
        'id' => 'mvp_create',
        'action' => "/mvp/create?id=$confirmGcp->id",
        'options' => ['class' => 'g-py-15'],
        'errorCssClass' => 'u-has-error-v1',
        'successCssClass' => 'u-has-success-v1-1',
    ]);
    ?>

    <div class="row" style="color: #4F4F4F;">

        <div class="col-md-12" style="margin-top: 10px; padding-left: 20px; padding-right: 20px;">
            Minimum Viable Product(MVP) — минимально жизнеспособный продукт, концепция минимализма программной комплектации выводимого на рынок устройства.
            Минимально жизнеспособный продукт - продукт, обладающий минимальными, но достаточными для удовлетворения первых потребителей функциями.
            Основная задача — получение обратной связи для формирования гипотез дальнейшего развития продукта.
        </div>

        <div class="col-md-12" style="margin-top: 10px;">

            <?= $form->field($newMvp, 'description', ['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->label('Описание минимально жизнеспособного продукта')->textarea([
                'rows' => 2,
                'maxlength' => 255,
                'required' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => 'Примеры: презентация, макет, программное обеспечение, опытный образец, видео и т.д.',
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


    <?php
    // Модальное окно - сообщение о том что данных недостаточно для создания MVP
    Modal::begin([
        'options' => [
            'id' => 'mvp_create_modal_error',
            'class' => 'mvp_create_modal_error',
        ],
        'size' => 'modal-md',
        'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Недостаточно данных для создания нового продукта (MVP).</h3>',
    ]);
    ?>

    <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
        Вернитесь к подтверждению ценностного предложения.
    </h4>

    <?php Modal::end(); ?>



    <?php foreach ($models as $model) : ?>

        <?php
        // Модальное окно - редактирование описания MVP
        Modal::begin([
            'options' => [
                'id' => "update_description_modal-$model->id",
                'class' => 'update_description_modal',
            ],
            'size' => 'modal-lg',
            'header' => '<h3 class="text-center" style="color: #4F4F4F; font-weight: 700;">Редактирование описания продукта (MVP) - '. $model->title .'</h3>',
        ]);
        ?>

        <?php
        $form = ActiveForm::begin([
            'id' => "form_update_description-$model->id",
            'action' => "/mvp/update?id=$model->id",
            'options' => ['class' => 'g-py-15 mvpUpdateForm'],
            'errorCssClass' => 'u-has-error-v1',
            'successCssClass' => 'u-has-success-v1-1',
        ]);
        ?>

        <div class="row" style="color: #4F4F4F;">

            <div class="col-md-12" style="margin-top: 10px; padding-left: 20px; padding-right: 20px;">
                Minimum Viable Product(MVP) — минимально жизнеспособный продукт, концепция минимализма программной комплектации выводимого на рынок устройства.
                Минимально жизнеспособный продукт - продукт, обладающий минимальными, но достаточными для удовлетворения первых потребителей функциями.
                Основная задача — получение обратной связи для формирования гипотез дальнейшего развития продукта.
            </div>

            <div class="col-md-12" style="margin-top: 10px;">

                <?= $form->field($model, 'description', ['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->label('Описание минимально жизнеспособного продукта')->textarea([
                    'rows' => 2,
                    'required' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => 'Примеры: презентация, макет, программное обеспечение, опытный образец, видео и т.д.',
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




    <!--Roadmap Project-->

    <?php

    // Модальное окно - дорожная карта проекта
    Modal::begin([
        'options' => [
            'id' => 'showRoadmapProject',
            'class' => 'showRoadmapProject',
        ],
        'size' => 'modal-lg',
        'header' => '<h2 class="text-center" style="font-size: 36px; color: #4F4F4F;">Дорожная карта проекта «' . $project->project_name . '»</h2>',
    ]);
    ?>

    <?= $project->showRoadmapProject();?>

    <?php Modal::end(); ?>


    <!--Roadmap Segment-->

    <?php

    // Модальное окно - дорожная карта сегмента
    Modal::begin([
        'options' => [
            'id' => 'showRoadmapSegment',
            'class' => 'showRoadmapSegment',
        ],
        'size' => 'modal-lg',
        'header' => '<div class="roadmap_segment_modal_header_title">
                        <h2 class="roadmap_segment_modal_header_title_h2">Дорожная карта сегмента «' . $segment->name . '»</h2>
                     </div>',
    ]);
    ?>

    <?= $segment->showRoadmapSegment();?>

    <?php Modal::end(); ?>



</div>


<?php

$script = "

    $(document).ready(function() {
        
        //Фон для модального окна (при создании MVP - недостаточно данных)
        var mvp_create_modal_error = $('#mvp_create_modal_error').find('.modal-content');
        mvp_create_modal_error.css('background-color', '#707F99');
        
        
        
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
    
    
    
    
    //При попытке добавить MVP проверяем существуют ли необходимые данные
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
                    $('#mvp_create_modal').modal('show');
                }else{
                    $('#mvp_create_modal_error').modal('show');
                }
            },
            error: function(){
                alert('Ошибка');
            }
        });
        
        //e.preventDefault();
        return false;
    });
    
    
    
    //Форма редактирования описания MVP
    $('.mvpUpdateForm').on('beforeSubmit', function(e){
    
        var id = $(this).attr('id');
        id = id.split('-');
        id = id[1];
    
        var data = $(this).serialize();
        var url = '/mvp/update?id=' + id;
    
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
                    
                    var column = $('#column_mvp_description-' + id).html('<\div title=\"' + response.description + '\">' + description + '<\/div>');
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