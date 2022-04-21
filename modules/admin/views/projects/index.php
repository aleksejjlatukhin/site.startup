<?php
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
?>

<?php

$this->title = 'Портфель проектов';

?>

<div class="admin-projects-result">

    <?php
    $form = ActiveForm::begin([
        'options' => ['class' => 'g-py-15'],
        'errorCssClass' => 'u-has-error-v1',
        'successCssClass' => 'u-has-success-v1-1',
    ]);

    ?>

    <div class="row" style="display:flex; align-items: center;">
        <div class="col-md-10" style="font-size: 32px; text-transform: uppercase;"><?= $this->title; ?></div>
        <div class="col-md-2">
            <div class="row pull-right select_count_projects">

                <div class="col-md-4" style="padding: 0;">
                    <div class="pull-right" style="padding-top: 5px; font-size: 18px;">Показывать:</div>
                </div>

                <div class="col-md-8" style="">
                    <?= $form->field($sortModel, 'field',
                        ['template' => '<div style="padding-top: 15px;">{input}</div>'])
                        ->widget(Select2::class, [
                            'data' => $show_count_projects,
                            'options' => ['id' => 'field_count_projects',],
                            'hideSearch' => true, //Скрытие поиска
                        ]);
                    ?>
                </div>

            </div>
        </div>
    </div>

    <?php
    ActiveForm::end();
    ?>


    <div class="containerHeaderDataOfTableResultProject">
        <div class="headerDataOfTableResultProject">
            <div class="blocks_for_double_header_level">

                <div class="one_block_for_double_header_level">

                    <div class="text-center stage">Сегмент</div>

                    <div class="columns_stage">
                        <div class="text-center column_segment_name">Наименование</div>
                        <div class="text-center regular_column">Статус</div>
                        <div class="text-center regular_column">Дата генер.</div>
                        <div class="text-center regular_column">Дата подтв.</div>
                    </div>

                </div>


                <div class="one_block_for_double_header_level">

                    <div class="text-center stage">Проблемы сегмента</div>

                    <div class="columns_stage">
                        <div class="text-center first_regular_column_of_stage">Обознач.</div>
                        <div class="text-center regular_column">Статус</div>
                        <div class="text-center regular_column">Дата генер.</div>
                        <div class="text-center regular_column">Дата подтв.</div>
                    </div>

                </div>


                <div class="one_block_for_double_header_level">

                    <div class="text-center stage">Ценностные предложения</div>

                    <div class="columns_stage">
                        <div class="text-center regular_column first_regular_column_of_stage">Обознач.</div>
                        <div class="text-center regular_column">Статус</div>
                        <div class="text-center regular_column">Дата генер.</div>
                        <div class="text-center regular_column">Дата подтв.</div>
                    </div>

                </div>


                <div class="one_block_for_double_header_level">

                    <div class="text-center stage">MVP (продукт)</div>

                    <div class="columns_stage">
                        <div class="text-center first_regular_column_of_stage">Обознач.</div>
                        <div class="text-center regular_column">Статус</div>
                        <div class="text-center regular_column">Дата генер.</div>
                        <div class="text-center regular_column">Дата подтв.</div>
                    </div>

                </div>

            </div>

            <div class="blocks_for_single_header_level text-center">
                <div class="">Бизнес-модель</div>
            </div>

        </div>
    </div>


    <div class="allContainersDataOfTableResultProject">

    </div>

</div>


<!--Подключение скриптов-->
<?php if (!$pageClientProjects) : ?>
    <?php $this->registerJsFile('@web/js/admin_project_portfolio_index.js'); ?>
<?php else : ?>
    <?php $this->registerJsFile('@web/js/admin_client_project_portfolio_index.js'); ?>
<?php endif; ?>