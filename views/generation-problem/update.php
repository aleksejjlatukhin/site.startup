<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>

<div class="form-update-problem">

    <div class="form-problem">

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


        <? //Список респондентов(представителей сегмента) и их вариантов проблем ?>
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
                        <?= Html::a('<div title="'.$respond->name.'">' . $respond_name . '</div>', ['/generation-problem/get-interview-respond', 'id' => $respond->id], [
                            'class' => 'get_interview_respond',
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

        <div class="generation-problem-form" style="margin-top: 20px;">

            <?php $form = ActiveForm::begin([
                'id' => 'hypothesisUpdateForm',
                'action' => Url::to(['/generation-problem/update', 'id' => $model->id]),
                'options' => ['class' => 'g-py-15 hypothesisUpdateForm'],
                'errorCssClass' => 'u-has-error-v1',
                'successCssClass' => 'u-has-success-v1-1',
            ]); ?>

            <div class="row">

                <div class="col-md-12">

                    <? $placeholder = 'Напишите описание гипотезы проблемы сегмента. Примеры: 
- отсутствие путеводителя по комерциализации результатов интеллектуальной деятельности, 
- отсутствие необходимой информации по патентованию...' ?>

                    <?= $form->field($model, 'description', ['template' => '<div style="padding-left: 15px;">{label}</div><div>{input}</div>'])->textarea([
                        'rows' => 3,
                        'maxlength' => true,
                        'required' => true,
                        'placeholder' => $placeholder,
                        'class' => 'style_form_field_respond form-control',
                    ]) ?>

                </div>

                <div class="col-md-12">

                    <? $placeholder = 'Что нужно сделать, чтобы проверить гипотезу? Какие следует задать вопросы, чтобы проверить гипотезу. Например: каким инструментом пользуются сейчас и какие инструменты пробовали, по каким критериям подбирали инструмент, как проходит процесс учета финансов, какую информацию анализируют.' ?>

                    <?= $form->field($model, 'action_to_check', ['template' => '<div style="padding-left: 15px;">{label}</div><div>{input}</div>'])->textarea([
                        'rows' => 3,
                        'maxlength' => true,
                        'required' => true,
                        'placeholder' => $placeholder,
                        'class' => 'style_form_field_respond form-control',
                    ]) ?>

                </div>

                <div class="col-md-12">

                    <? $placeholder = 'Какой результат покажет, что гипотеза верна? Например: Больше 80% опрошенных ответят, что гибкость настройки для них является ключевым фактором выбора инструмента.' ?>

                    <?= $form->field($model, 'result_metric', ['template' => '<div style="padding-left: 15px;">{label}</div><div>{input}</div>'])->textarea([
                        'rows' => 3,
                        'maxlength' => true,
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

    </div>

</div>
