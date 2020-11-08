<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\User;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Генерация гипотез проблем сегмента';

$this->registerCssFile('@web/css/problem-index-style.css');
?>
    <div class="generation-problem-index">


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

            <div class="active_navigation_block navigation_block">
                <div class="stage_number">3</div>
                <div>Генерация гипотез проблем сегментов</div>
            </div>

            <div class="no_transition_navigation_block navigation_block">
                <div class="stage_number">4</div>
                <div>Подтверждение гипотез проблем сегментов</div>
            </div>

            <div class="no_transition_navigation_block navigation_block">
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
                <span>Сегмент:</span>
                <?= $segment->name; ?>
            </div>

            <?= Html::a('Данные сегмента', ['/segment/show-all-information', 'id' => $segment->id], [
                'class' => 'col-xs-12 col-sm-6 col-md-6 col-lg-2 openAllInformationSegment link_in_the_header',
            ]) ?>

            <?= Html::a('Дорожная карта сегмента', ['/segment/show-roadmap', 'id' => $segment->id], [
                'class' => 'col-xs-12 col-sm-6 col-md-6 col-lg-2 openRoadmapSegment link_in_the_header text-center',
            ]) ?>

        </div>



        <div class="container-fluid container-data row">

            <div class="container-fluid row">

                <div class="col-md-12" style="padding: 15px 0;">

                    <?=  Html::a( '<div class="new_segment_link_block"><div>' . Html::img(['@web/images/icons/add_vector.png'], ['style' => ['width' => '35px']]) . '</div><div style="padding-left: 20px;">Новая проблема</div></div>',
                        ['/interview/data-availability-for-next-step', 'id' => $interview->id],
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

                <div class="col-md-7">Описание гипотезы проблемы сегмента</div>

                <div class="col-md-1 text-center"><div>Дата создания</div></div>

                <div class="col-md-1 text-center header_date_confirm"><div>Дата подтв.</div></div>

                <div class="col-md-2"></div>

            </div>


            <div class="block_all_problems_segment row" style="padding-left: 10px; padding-right: 10px;">

                <!--Данные для списка проблем-->
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

                        <div class="col-md-7" id="column_problem_description-<?=$model->id;?>">

                            <?php
                            $problem_desc = $model->description;
                            if (mb_strlen($problem_desc) > 180) {
                                $problem_desc = mb_substr($model->description, 0, 180) . '...';
                            }
                            ?>

                            <?= '<div title="'.$model->description.'" style="line-height: 21px;">' . $problem_desc . '</div>'?>

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

                                        <?= Html::a('Далее', ['/confirm-problem/view', 'id' => $model->confirm->id], [
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

                                        <?= Html::a('Подтвердить', ['/confirm-problem/create', 'id' => $model->id], [
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
                                            'data-target' => '#problem_update_modal-' . $model->id,
                                        ]); ?>

                                    <?php endif; ?>

                                </div>

                                <div >

                                    <?= Html::a(Html::img('/images/icons/icon_delete.png', ['style' => ['width' => '24px']]),['/generation-problem/delete', 'id' => $model->id], [
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
                        <div>Проблема подтверждена</div>
                    </div>

                    <div style="display:flex; align-items: center;">
                        <?= Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px', 'margin-right' => '8px']]);?>
                        <div>Проблема не подтверждена</div>
                    </div>

                    <div style="display:flex; align-items: center;">
                        <?= Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px', 'margin-right' => '8px']]);?>
                        <div>Проблема ожидает подтверждения</div>
                    </div>

                </div>

            </div>

        <?php endif; ?>



        <?php
        // Модальное окно - создание ГПС
        Modal::begin([
            'options' => [
                'id' => 'problem_create_modal',
                'class' => 'problem_create_modal',
            ],
            'size' => 'modal-lg',
            'header' => '<div style="display:flex; align-items: center; justify-content: center; font-weight: 700;"><span style="font-size: 24px; color: #4F4F4F; padding-right: 10px;">Создание гипотезы проблемы сегмента</span>' . Html::a(Html::img('/images/icons/icon_info.png'), ['#'], [
                    'data-toggle' => 'modal',
                    'data-target' => "#information-table-create-problem",
                    'title' => 'Посмотреть описание',
                ]) . '</div>',
        ]);
        ?>

        <div class="row" style="color: #4F4F4F; margin-top: 10px; margin-bottom: 15px;">

            <div class="col-md-12">
                Варианты проблем, полученные от респондентов (представителей сегмента)
            </div>

        </div>

        <div class="row" style="color: #4F4F4F; padding-left: 10px; margin-bottom: 5px;">

            <div class="col-md-4 roboto_condensed_bold">
                Респонденты
            </div>

            <div class="col-md-8 roboto_condensed_bold">
                Варианты проблем
            </div>

        </div>


        <!--Список респондентов(представителей сегмента) и их вариантов проблем-->
        <div class="all_responds_problems row container-fluid" style="margin: 0;">

            <?php foreach ($responds as $respond) : ?>

                <div class="block_respond_problem row">

                    <div class="col-md-4 block_respond_problem_column">

                        <?php
                        $respond_name = $respond->name;
                        if (mb_strlen($respond_name) > 30) {
                            $respond_name = mb_substr($respond_name, 0, 30) . '...';
                        }
                        ?>
                        <?= Html::a('<div title="'.$respond->name.'">' . $respond_name . '</div>', ['#'], [
                            'class' => '',
                            'data-toggle' => 'modal',
                            'data-target' => "#respond_positive_view_modal-$respond->id",
                        ]); ?>

                    </div>

                    <div class="col-md-8 block_respond_problem_column">

                        <?php
                        $descInterview_result = $respond->descInterview->result;
                        if (mb_strlen($descInterview_result) > 70) {
                            $descInterview_result = mb_substr($descInterview_result, 0, 70) . '...';
                        }
                        ?>
                        <?= '<div title="'.$respond->descInterview->result.'">' . $descInterview_result . '</div>'; ?>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>


        <div class="row" style="color: #4F4F4F; margin-top: 20px;">

            <div class="col-md-12">
                Описание гипотезы проблемы сегмента
            </div>

        </div>


        <div class="generation-problem-form" style="margin-top: 5px;">

            <?php $form = ActiveForm::begin([
                'id' => 'gpsCreateForm',
                'action' => Url::to(['/generation-problem/create', 'id' => $interview->id]),
                'options' => ['class' => 'g-py-15'],
                'errorCssClass' => 'u-has-error-v1',
                'successCssClass' => 'u-has-success-v1-1',
            ]); ?>

            <? $placeholder = 'Напишите описание гипотезы проблемы сегмента. Примеры: 
- отсутствие путеводителя по комерциализации результатов интеллектуальной деятельности, 
- отсутствие необходимой информации по патентованию...' ?>

            <div class="row">
                <div class="col-md-12">

                    <?= $form->field($newProblem, 'description')->label(false)->textarea([
                        'rows' => 3,
                        'required' => true,
                        'placeholder' => $placeholder,
                        'class' => 'style_form_field_respond form-control',
                    ]) ?>

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

        </div>

        <?php Modal::end(); ?>



        <?php foreach ($models as $model) : ?>


            <?php
            // Модальное окно - редактирование ГПС
            Modal::begin([
                'options' => [
                    'id' => 'problem_update_modal-' . $model->id,
                    'class' => 'problem_update_modal',
                ],
                'size' => 'modal-lg',
                'header' => '<div style="display:flex; align-items: center; justify-content: center; font-weight: 700;"><span style="font-size: 24px; color: #4F4F4F; padding-right: 10px;">Редактирование гипотезы проблемы сегмента - ' . $model->title . '</span></div>',
            ]);
            ?>

            <div class="row" style="color: #4F4F4F; margin-top: 10px; margin-bottom: 15px;">

                <div class="col-md-12">
                    Варианты проблем, полученные от респондентов (представителей сегмента)
                </div>

            </div>

            <div class="row" style="color: #4F4F4F; padding-left: 10px; margin-bottom: 5px;">

                <div class="col-md-4 roboto_condensed_bold">
                    Респонденты
                </div>

                <div class="col-md-8 roboto_condensed_bold">
                    Варианты проблем
                </div>

            </div>


            <!--Список респондентов(представителей сегмента) и их вариантов проблем-->
            <div class="all_responds_problems row container-fluid" style="margin: 0;">

                <?php foreach ($responds as $respond) : ?>

                    <div class="block_respond_problem row">

                        <div class="col-md-4 block_respond_problem_column">

                            <?php
                            $respond_name = $respond->name;
                            if (mb_strlen($respond_name) > 30) {
                                $respond_name = mb_substr($respond_name, 0, 30) . '...';
                            }
                            ?>
                            <?= Html::a('<div title="'.$respond->name.'">' . $respond_name . '</div>', ['#'], [
                                'class' => '',
                                'data-toggle' => 'modal',
                                'data-target' => "#respond_positive_view_modal-$respond->id",
                            ]); ?>

                        </div>

                        <div class="col-md-8 block_respond_problem_column">

                            <?php
                            $descInterview_result = $respond->descInterview->result;
                            if (mb_strlen($descInterview_result) > 70) {
                                $descInterview_result = mb_substr($descInterview_result, 0, 70) . '...';
                            }
                            ?>
                            <?= '<div title="'.$respond->descInterview->result.'">' . $descInterview_result . '</div>'; ?>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>


            <div class="row" style="color: #4F4F4F; margin-top: 20px;">

                <div class="col-md-12">
                    Описание гипотезы проблемы сегмента
                </div>

            </div>


            <div class="generation-problem-form" style="margin-top: 5px;">

                <?php $form = ActiveForm::begin([
                    'id' => 'gpsUpdateForm-' . $model->id,
                    'action' => Url::to(['/generation-problem/update', 'id' => $model->id]),
                    'options' => ['class' => 'g-py-15 gpsUpdateForm'],
                    'errorCssClass' => 'u-has-error-v1',
                    'successCssClass' => 'u-has-success-v1-1',
                ]); ?>

                <? $placeholder = 'Напишите описание гипотезы проблемы сегмента. Примеры: 
- отсутствие путеводителя по комерциализации результатов интеллектуальной деятельности, 
- отсутствие необходимой информации по патентованию...' ?>

                <div class="row">
                    <div class="col-md-12">

                        <?= $form->field($model, 'description')->label(false)->textarea([
                            'rows' => 3,
                            'required' => true,
                            'placeholder' => $placeholder,
                            'class' => 'style_form_field_respond form-control',
                        ]) ?>

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

            </div>

            <?php Modal::end(); ?>


        <?php endforeach; ?>




        <?php
        // Модальное окно - Информационное окно в создании ГПС
        Modal::begin([
            'options' => [
                'id' => 'information-table-create-problem',
                'class' => 'information-table-create-problem',
            ],
            'size' => 'modal-md',
            'header' => '<h3 class="text-center" style="color: #F2F2F2;">Информация</h3>',
        ]);
        ?>

        <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
            Необходимо просмотреть и проанализировать все материалы интервью представителей сегмента и выявить проблемы, которые характерны для нескольких респондентов
        </h4>


        <?php Modal::end(); ?>



        <?php
        // Модальное окно - сообщение о том что данных недостаточно для создания ГПС
        Modal::begin([
            'options' => [
                'id' => 'problem_create_modal_error',
                'class' => 'problem_create_modal_error',
            ],
            'size' => 'modal-md',
            'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Недостаточно данных для создания ГПС.</h3>',
        ]);
        ?>

        <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
            Вернитесь к подтверждению сегмента.
        </h4>

        <?php Modal::end(); ?>



        <?php foreach ($responds as $respond) : ?>

            <?php $descInterview = $respond->descInterview; ?>

            <?php
            // Модальное окно - Информамация о представителях сегмента
            Modal::begin([
                'options' => [
                    'id' => "respond_positive_view_modal-$respond->id",
                    'class' => 'respond_positive_view_modal',
                ],
                'size' => 'modal-lg',
                'header' => '<div class="text-center"><span style="font-size: 24px; font-weight: 700;">Информация о интервью</span></div>',
            ]);
            ?>

            <div class="row" style="margin-bottom: 15px; margin-top: 15px; color: #4F4F4F;">

                <div class="col-md-12" style="padding: 0 20px; margin-bottom: 15px;">
                    <div style="font-weight: 700;">Респондент</div>
                    <div><?= $respond->name; ?></div>
                </div>

                <div class="col-md-12" style="padding: 0 20px; margin-bottom: 15px;">
                    <div style="font-weight: 700;">Материалы, полученные в ходе интервью</div>
                    <div><?= $descInterview->description; ?></div>
                </div>

                <div class="col-md-12" style="padding: 0 20px; margin-bottom: 15px;">
                    <div style="font-weight: 700;">Варианты проблем</div>
                    <div><?= $descInterview->result; ?></div>
                </div>

                <div class="col-md-12">

                    <p style="padding-left: 5px; font-weight: 700;">Приложенный файл</p>

                    <?php if (!empty($descInterview->interview_file)) : ?>

                        <div style="margin-top: -5px; margin-bottom: 30px;">

                            <div style="display: flex; align-items: center;">

                                <?= Html::a('Скачать файл', ['/desc-interview/download', 'id' => $descInterview->id], [
                                    'class' => "btn btn-default interview_file_view-$descInterview->id",
                                    'style' => [
                                        'display' => 'flex',
                                        'align-items' => 'center',
                                        'color' => '#FFFFFF',
                                        'justify-content' => 'center',
                                        'background' => '#707F99',
                                        'width' => '170px',
                                        'height' => '40px',
                                        'text-align' => 'left',
                                        'font-size' => '24px',
                                        'border-radius' => '8px',
                                        'margin-right' => '5px',
                                    ]

                                ]);
                                ?>

                            </div>

                            <div class="title_name_update_form" style="padding-left: 5px; padding-top: 5px; margin-bottom: -10px;"><?= $descInterview->interview_file;?></div>

                        </div>

                    <?php endif;?>

                    <?php if (empty($descInterview->interview_file)) : ?>

                        <div class="col-md-12" style="padding-left: 5px; margin-bottom: 20px;">Файл не выбран</div>

                    <?php endif;?>

                </div>

            </div>

            <?php Modal::end(); ?>

        <?php endforeach; ?>

    </div>


<?php

$script = "

    $(document).ready(function() {

        //Фон для модального окна информации при отказе в добавлении ГПС
        var info_problem_create_modal_error = $('#problem_create_modal_error').find('.modal-content');
        info_problem_create_modal_error.css('background-color', '#707F99');
        
        //Фон для модального окна информации при создании ГПС 
        var information_modal = $('#information-table-create-problem').find('.modal-content');
        information_modal.css('background-color', '#707F99');
        
        
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
    
    
    
    //При попытке добавить ГПС проверяем существуют ли необходимые данные
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
                    $('#problem_create_modal').modal('show');
                }else{
                    $('#problem_create_modal_error').modal('show');
                }
            },
            error: function(){
                alert('Ошибка');
            }
        });
        
        //e.preventDefault();
        return false;
    });
    
    
    //Редактирование гипотезы проблемы сегмента
    $('.gpsUpdateForm').on('beforeSubmit', function(e){
    
        var id = $(this).attr('id');
        id = id.split('-');
        id = id[1];
        
        var url = '/generation-problem/update?id=' + id;
        var data = $(this).serialize();
        
        $.ajax({
        
            url: url,
            data: data,
            method: 'POST',
            cache: false,
            success: function(response){
                
                var description = response.description;
                
                if (description.length > 180) {
                    description = description.substring(0, 180) + '...';
                }
                
                $('#problem_update_modal-' + id).modal('hide');
                
                var column = $('#column_problem_description-' + id).html('<\div title=\"' + response.description + '\">' + description + '<\/div>');
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