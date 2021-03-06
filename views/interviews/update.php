<?php

use app\models\User;
use app\models\StageConfirm;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\Url;
use app\models\QuestionStatus;

?>


<?php if (User::isUserSimple(Yii::$app->user->identity['username']) && $hypothesis->exist_confirm === null) : ?>

    <?php $form = ActiveForm::begin([
        'id' => 'formUpdateDescInterview',
        'action' => Url::to(['/interviews/update', 'stage' => $confirm->stage, 'id' => $model->id]),
        'options' => ['enctype' => 'multipart/form-data', 'class' => 'g-py-15'],
        'errorCssClass' => 'u-has-error-v1',
        'successCssClass' => 'u-has-success-v1-1',
    ]); ?>

    <?php if ($respond->answers) : ?>
        <?php foreach ($respond->answers as $index => $answer) : ?>

            <?php if ($answer->question->status === QuestionStatus::STATUS_ONE_STAR) : ?>

                <?= $form->field($answer, "[$index]answer", ['template' => '<div style="padding-left: 5px; color: #52be7f;">{label}</div><div>{input}</div>'])->label($answer->question->title)
                    ->textarea([
                        'row' => 2,
                        'maxlength' => true,
                        'required' => true,
                        'class' => 'style_form_field_respond form-control',
                    ]); ?>

            <?php elseif($answer->question->status === QuestionStatus::STATUS_NOT_STAR) : ?>

                <?= $form->field($answer, "[$index]answer", ['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->label($answer->question->title)
                ->textarea([
                    'row' => 2,
                    'maxlength' => true,
                    'required' => true,
                    'class' => 'style_form_field_respond form-control',
                ]); ?>

            <?php endif; ?>

        <?php endforeach; ?>
    <?php endif; ?>

    <div class="row" style="margin-bottom: 15px;">

        <div class="col-md-12">

            <p style="padding-left: 5px;"><b>Приложить файл</b> <span style="color: #BDBDBD; padding-left: 20px;">png, jpg, jpeg, pdf, txt, doc, docx, xls</span></p>


            <?php if (!empty($model->interview_file)) : ?>


                <div class="feed-exp">

                    <div style="display:flex; margin-top: -5px;margin-bottom: -30px;">

                        <?= $form->field($model, 'loadFile')
                            ->fileInput([
                                'id' => 'descInterviewUpdateFile', 'class' => 'sr-only'
                            ])->label('Выберите файл',[
                                'class'=>'btn btn-default',
                                'style' => [
                                    'display' => 'flex',
                                    'align-items' => 'center',
                                    'color' => '#FFFFFF',
                                    'justify-content' => 'center',
                                    'background' => '#707F99',
                                    'width' => '180px',
                                    'height' => '40px',
                                    'font-size' => '24px',
                                    'border-radius' => '8px',
                                ],
                            ]); ?>

                        <div class="file_name_update_form" style="padding-left: 20px; padding-top: 5px;">Файл не выбран</div>

                    </div>

                </div>


                <div style="margin-top: -5px; margin-bottom: 30px;">

                    <div style="display: flex; align-items: center;">

                        <?= Html::a('Скачать файл', ['/interviews/download', 'stage' => $confirm->stage, 'id' => $model->id], [
                            'target' => '_blank',
                            'class' => 'btn btn-default interview_file_update',
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

                        ]) . ' ' . Html::a('Удалить файл', ['/interviews/delete-file', 'stage' => $confirm->stage, 'id' => $model->id], [
                            'id' => 'link_delete_file',
                            'class' => "btn btn-default link-delete",
                            'style' => [
                                'display' => 'flex',
                                'align-items' => 'center',
                                'justify-content' => 'center',
                                'background' => '#E0E0E0',
                                'color' => '#FFFFFF',
                                'width' => '170px',
                                'height' => '40px',
                                'font-size' => '24px',
                                'border-radius' => '8px',
                            ]
                        ]); ?>

                    </div>

                    <div class="title_name_update_form" style="padding-left: 5px; padding-top: 5px; margin-bottom: -10px;"><?= $model->interview_file;?></div>

                </div>


            <?php endif;?>


            <?php if (empty($model->interview_file)) : ?>

                <div style="display:flex; margin-top: -5px;">

                    <?= $form->field($model, 'loadFile')
                        ->fileInput([
                            'id' => 'descInterviewUpdateFile', 'class' => 'sr-only'
                        ])->label('Выберите файл',[
                            'class'=>'btn btn-default',
                            'style' => [
                                'display' => 'flex',
                                'align-items' => 'center',
                                'color' => '#FFFFFF',
                                'justify-content' => 'center',
                                'background' => '#707F99',
                                'width' => '180px',
                                'height' => '40px',
                                'font-size' => '24px',
                                'border-radius' => '8px',
                            ],
                        ]); ?>

                    <div class="file_name_update_form" style="padding-left: 20px; padding-top: 5px;">Файл не выбран</div>

                </div>

            <?php endif;?>


        </div>

        <?php if ($confirm->stage == StageConfirm::STAGE_CONFIRM_SEGMENT) : ?>

            <div class="col-md-12" style="margin-top: -10px;">

                <?= $form->field($model, 'result',['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->textarea([
                    'rows' => 2,
                    'maxlength' => true,
                    'required' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => 'Опишите краткий вывод по интервью',
                ]); ?>

            </div>

            <div class="col-xs-12">

                <?php
                $selection_list = [ '0' => 'Респондент не является представителем сегмента', '1' => 'Респондент является представителем сегмента', ];
                ?>

                <?= $form->field($model, 'status', [
                    'template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>',
                ])->label('Этот респондент является представителем сегмента?')->widget(Select2::class, [
                    'data' => $selection_list,
                    'options' => ['id' => 'descInterview_status_update'],
                    'disabled' => false,  //Сделать поле неактивным
                    'hideSearch' => true, //Скрытие поиска
                ]);
                ?>

            </div>

        <?php elseif ($confirm->stage == StageConfirm::STAGE_CONFIRM_PROBLEM) : ?>

            <div class="col-md-12">

                <?php
                $selection_list = [ '0' => 'Проблемы не существует или она малозначимая', '1' => 'Проблема значимая', ];
                ?>

                <?= $form->field($model, 'status', [
                    'template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>',
                ])->label('По результатам интервью сделайте вывод о текущей проблеме')->widget(Select2::class, [
                    'data' => $selection_list,
                    'options' => ['id' => 'descInterview_status_update'],
                    'disabled' => false,  //Сделать поле неактивным
                    'hideSearch' => true, //Скрытие поиска
                ]);
                ?>

            </div>

        <?php elseif ($confirm->stage == StageConfirm::STAGE_CONFIRM_GCP) : ?>

            <div class="col-md-12">

                <?php
                $selection_list = [ '0' => 'Предложение не интересно', '1' => 'Предложение привлекательно', ];
                ?>

                <?= $form->field($model, 'status', [
                    'template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>',
                ])->label('По результатам интервью сделайте вывод о текущем ценностном предложении')->widget(Select2::class, [
                    'data' => $selection_list,
                    'options' => ['id' => 'descInterview_status_update'],
                    'disabled' => false,  //Сделать поле неактивным
                    'hideSearch' => true, //Скрытие поиска
                ]);
                ?>

            </div>

        <?php elseif ($confirm->stage == StageConfirm::STAGE_CONFIRM_MVP) : ?>

            <div class="col-md-12">

                <?php
                $selection_list = [ '0' => 'Не хочу приобретать данный продукт (MVP)', '1' => 'Хочу приобрести данный продукт (MVP)', ];
                ?>

                <?= $form->field($model, 'status', [
                    'template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>',
                ])->label('По результатам интервью сделайте вывод о текущем продукте (MVP)')->widget(Select2::class, [
                    'data' => $selection_list,
                    'options' => ['id' => 'descInterview_status_update'],
                    'disabled' => false,  //Сделать поле неактивным
                    'hideSearch' => true, //Скрытие поиска
                ]);
                ?>

            </div>

        <?php endif; ?>

        <div class="form-group col-xs-12">
            <?= Html::submitButton('Сохранить', [
                'class' => 'btn btn-success pull-right',
                'style' => [
                    'display' => 'flex',
                    'align-items' => 'center',
                    'justify-content' => 'center',
                    'background' => '#52BE7F',
                    'width' => '140px',
                    'height' => '40px',
                    'font-size' => '24px',
                    'border-radius' => '8px',
                    'margin-top' => '28px'
                ]
            ]) ?>
        </div>

    </div>

    <?php ActiveForm::end(); ?>


<?php else : ?>


    <div class="row" style="margin-bottom: 15px; color: #4F4F4F;">

        <div class="col-md-12" style="padding: 0 20px;">
            <div style="font-weight: 700;">Респондент</div>
            <div><?= $respond->name; ?></div>
        </div>

        <div class="col-md-12" style="padding: 0 20px; font-size: 24px; margin-top: 5px;">
            <div style="border-bottom: 1px solid #ccc;">Ответы на вопросы интервью</div>
        </div>

        <?php foreach ($respond->answers as $index => $answer) : ?>

            <div class="col-md-12" style="padding: 0 20px; margin-top: 10px;">

                <?php if ($answer->question->status === QuestionStatus::STATUS_ONE_STAR) : ?>
                    <div style="font-weight: 700; color: #52be7f;"><?= $answer->question->title; ?></div>
                <?php elseif($answer->question->status === QuestionStatus::STATUS_NOT_STAR) : ?>
                    <div style="font-weight: 700;"><?= $answer->question->title; ?></div>
                <?php endif; ?>

                <div><?= $answer->answer; ?></div>

            </div>

        <?php endforeach; ?>

        <?php if ($confirm->stage == StageConfirm::STAGE_CONFIRM_SEGMENT) : ?>

            <div class="col-md-12" style="padding: 0 20px; margin-bottom: 10px; margin-top: 10px;">
                <div style="font-weight: 700; border-top: 1px solid #ccc; padding-top: 10px;">Варианты проблем</div>
                <div><?= $model->result; ?></div>
            </div>

        <?php endif; ?>

        <div class="col-md-12">

            <p style="padding-left: 5px; font-weight: 700;">Приложенный файл</p>

            <?php if (!empty($model->interview_file)) : ?>

                <div style="margin-top: -5px; margin-bottom: 20px;">

                    <div style="display: flex; align-items: center;">

                        <?= Html::a('Скачать файл', ['/interviews/download', 'stage' => $confirm->stage, 'id' => $model->id], [
                            'target' => '_blank',
                            'class' => 'btn btn-default interview_file_update',
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

                    <div class="title_name_update_form" style="padding-left: 5px; padding-top: 5px; margin-bottom: -10px;"><?= $model->interview_file;?></div>

                </div>

            <?php endif;?>

            <?php if (empty($model->interview_file)) : ?>

                <div class="col-md-12" style="padding-left: 5px; margin-bottom: 20px;">Файл не выбран</div>

            <?php endif;?>

        </div>

        <?php if ($confirm->stage == StageConfirm::STAGE_CONFIRM_SEGMENT) : ?>

            <div class="col-md-12" style="padding: 0 20px; margin-bottom: 15px;">
                <div style="font-weight: 700;">Этот респондент является представителем сегмента?</div>
                <div>
                    <?php
                    if ($model->status == 1) {
                        echo 'Респондент является представителем сегмента';
                    } else {
                        echo 'Респондент не является представителем сегмента';
                    }
                    ?>
                </div>
            </div>

        <?php elseif ($confirm->stage == StageConfirm::STAGE_CONFIRM_PROBLEM) : ?>

            <div class="col-md-12" style="padding: 0 20px; margin-bottom: 5px;">
                <div style="font-weight: 700;">Вывод по результам интервью о текущей проблеме</div>
                <div>
                    <?php
                    if ($model->status == 1) {
                        echo 'Проблема значимая';
                    } else {
                        echo 'Проблемы не существует или она малозначимая';
                    }
                    ?>
                </div>
            </div>

        <?php elseif ($confirm->stage == StageConfirm::STAGE_CONFIRM_GCP) : ?>

            <div class="col-md-12" style="padding: 0 20px; margin-bottom: 5px;">
                <div style="font-weight: 700;">Вывод по результам интервью о текущем предложении</div>
                <div>
                    <?php
                    if ($model->status == 1) {
                        echo 'Предложение привлекательно';
                    } else {
                        echo 'Предложение не интересно';
                    }
                    ?>
                </div>
            </div>

        <?php elseif ($confirm->stage == StageConfirm::STAGE_CONFIRM_MVP) : ?>

            <div class="col-md-12" style="padding: 0 20px; margin-bottom: 5px;">
                <div style="font-weight: 700;">Вывод по результам интервью о текущем продукте (MVP)</div>
                <div>
                    <?php
                    if ($model->status == 1) {
                        echo 'Хочу приобрести данный продукт (MVP)';
                    } else {
                        echo 'Не хочу приобретать данный продукт (MVP)';
                    }
                    ?>
                </div>
            </div>

        <?php endif; ?>

    </div>

<?php endif; ?>
