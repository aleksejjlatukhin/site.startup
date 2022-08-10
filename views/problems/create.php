<?php

use app\models\ConfirmSegment;
use app\models\forms\FormCreateProblem;
use app\models\RespondsSegment;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\select2\Select2;
use app\models\Problems;

/**
 * @var ConfirmSegment $confirmSegment
 * @var FormCreateProblem $model
 * @var RespondsSegment[] $responds
 */

?>


<style>
    .select2-container--krajee .select2-selection {
        font-size: 16px;
        height: 40px;
        padding-left: 15px;
        padding-top: 8px;
        padding-bottom: 15px;
        border-radius: 12px;
    }
    .select2-container--krajee .select2-selection--single .select2-selection__arrow {
        height: 39px;
    }
</style>


<div class="form-create-problem">

    <div class="form-problem">

        <div class="row" style="color: #4F4F4F; margin-top: 10px; margin-bottom: 15px;">

            <div class="col-md-12">
                <div class="pull-left" style="padding: 0 10px; border-bottom: 1px solid;">Варианты проблем, полученные от респондентов (представителей сегмента)</div>
            </div>

        </div>

        <div class="row" style="color: #4F4F4F; padding-left: 10px; margin-bottom: 5px; font-weight: 700;">

            <div class="col-md-4">
                Респонденты
            </div>

            <div class="col-md-8">
                Варианты проблем
            </div>

        </div>


        <!--Список респондентов(представителей сегмента) и их вариантов проблем-->
        <div class="all_responds_problems row container-fluid" style="margin: 0;">

            <?php foreach ($responds as $respond) : ?>

                <div class="block_respond_problem row">

                    <div class="col-md-4 block_respond_problem_column">

                        <?php
                        $respond_name = $respond->getName();
                        if (mb_strlen($respond_name) > 30) {
                            $respond_name = mb_substr($respond_name, 0, 30) . '...';
                        }
                        ?>
                        <?= Html::a('<div title="'.$respond->getName().'">' . $respond_name . '</div>', ['/problems/get-interview-respond', 'id' => $respond->getId()], [
                            'class' => 'get_interview_respond',
                        ]) ?>

                    </div>

                    <div class="col-md-8 block_respond_problem_column">

                        <?php
                        $interview_result = $respond->interview->getResult();
                        if (mb_strlen($interview_result) > 70) {
                            $interview_result = mb_substr($interview_result, 0, 70) . '...';
                        }
                        ?>
                        <?= '<div title="'.$respond->interview->getResult().'">' . $interview_result . '</div>' ?>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

        <div class="generation-problem-form" style="margin-top: 20px;">

            <?php $form = ActiveForm::begin([
                'id' => 'hypothesisCreateForm',
                'action' => Url::to(['/problems/create', 'id' => $confirmSegment->getId()]),
                'options' => ['class' => 'g-py-15 hypothesisCreateForm'],
                'errorCssClass' => 'u-has-error-v1',
                'successCssClass' => 'u-has-success-v1-1',
            ]); ?>

            <div class="row">

                <div class="col-md-12">

                    <?php $placeholder = 'Напишите описание гипотезы проблемы сегмента. Примеры: 
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

                <div class="col-xs-12">

                    <?= $form->field($model, 'indicator_positive_passage', [
                        'template' => '<div style="padding-left: 15px;">{label}</div><div>{input}</div>',
                    ])->widget(Select2::class, [
                        'data' => Problems::getValuesForSelectIndicatorPositivePassage(),
                        'options' => ['id' => 'indicator_positive_passage'],
                        'disabled' => false,  //Сделать поле неактивным
                        'hideSearch' => true, //Скрытие поиска
                    ]) ?>

                </div>

                <h4 class="col-md-12 text-center bolder" style="margin-bottom: 20px;">Вопросы для проверки гипотезы проблемы и ответы на них:</h4>

                <div class="container-expectedResults">
                    <div class="row container-fluid item-expectedResults item-expectedResults-form-create">
                        <div class="rowExpectedResults row-expectedResults-form-create-0">

                            <div class="col-md-6 field-EXR">

                                <?= $form->field($model, "_expectedResultsInterview[0][question]", ['template' => '{input}'])->textarea([
                                    'rows' => 2,
                                    'maxlength' => true,
                                    'required' => true,
                                    'placeholder' => 'Напишите вопрос',
                                    'id' => '_expectedResults_question-0',
                                    'class' => 'style_form_field_respond form-control',
                                ]) ?>

                            </div>

                            <div class="col-md-6 field-EXR">

                                <?= $form->field($model, "_expectedResultsInterview[0][answer]", ['template' => '{input}'])->textarea([
                                    'rows' => 2,
                                    'maxlength' => true,
                                    'required' => true,
                                    'placeholder' => 'Напишите ответ',
                                    'id' => '_expectedResults_answer-0',
                                    'class' => 'style_form_field_respond form-control',
                                ]) ?>

                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <?= Html::button('Добавить вопрос/ответ', [
                        'id' => 'add_expectedResults_create_form',
                        'class' => "btn btn-default add_expectedResults_create_form",
                        'style' => [
                            'display' => 'flex',
                            'align-items' => 'center',
                            'color' => '#FFFFFF',
                            'justify-content' => 'center',
                            'background' => '#707F99',
                            'width' => '170px',
                            'height' => '40px',
                            'text-align' => 'left',
                            'font-size' => '16px',
                            'border-radius' => '8px',
                            'margin-right' => '5px',
                        ]
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