<?php

use app\models\Authors;
use app\models\Projects;
use app\models\SortForm;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\User;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;
use app\models\ProjectSort;

/**
 * @var User $user
 * @var Projects[] $models
 * @var Authors $new_author
 * @var SortForm $sortModel
*/

$this->title = 'Проекты';
$this->registerCssFile('@web/css/projects-index-style.css');

?>
<div class="projects-index">

    <div class="row project_menu">

        <?= Html::a('Проекты', ['/projects/index', 'id' => $user->getId()], [
            'class' => 'link_in_the_header',
        ]) ?>

        <?= Html::a('Сводные таблицы', ['/projects/results', 'id' => $user->getId()], [
            'class' => 'link_in_the_header',
        ]) ?>

        <?= Html::a('Трэкшн карты', ['/projects/roadmaps', 'id' => $user->getId()], [
            'class' => 'link_in_the_header',
        ]) ?>

        <?= Html::a('Протоколы', ['/projects/reports', 'id' => $user->getId()], [
            'class' => 'link_in_the_header',
        ]) ?>

        <?= Html::a('Презентации', ['/projects/presentations', 'id' => $user->getId()], [
            'class' => 'link_in_the_header',
        ]) ?>

    </div>

    <div class="container-fluid container-data row">

        <div class="row row_header_data_generation" style="margin-top: 10px;">

            <div class="col-md-3" style="padding: 2px 0;">
                <?= Html::a('Проекты' . Html::img('/images/icons/icon_report_next.png'), ['/projects/get-instruction'],[
                    'class' => 'link_to_instruction_page open_modal_instruction_page', 'title' => 'Инструкция'
                ]) ?>
            </div>

            <?php if (!User::isUserExpert(Yii::$app->user->identity['username'])) : ?>

                <?php
                $form = ActiveForm::begin([
                    'id' => 'sorting_projects',
                    'options' => ['class' => 'g-py-15'],
                    'errorCssClass' => 'u-has-error-v1',
                    'successCssClass' => 'u-has-success-v1-1',
                ]); ?>

                <?php
                $listFields = ProjectSort::getListFields();
                $listFields = ArrayHelper::map($listFields,'id', 'name');
                ?>



                <div class="col-md-3">

                    <?= $form->field($sortModel, 'field',
                        ['template' => '<div>{input}</div>'])
                        ->widget(Select2::class, [
                            'data' => $listFields,
                            'options' => [
                                'id' => 'listFields',
                                'placeholder' => 'Выберите данные для сортировки'
                            ],
                            'hideSearch' => true, //Скрытие поиска
                        ])
                    ?>

                </div>

                <div class="col-md-3">

                    <?= $form->field($sortModel, 'type',
                        ['template' => '<div>{input}</div>'])
                        ->widget(DepDrop::class, [
                            'type' => DepDrop::TYPE_SELECT2,
                            'select2Options' => [
                                'pluginOptions' => ['allowClear' => false],
                                'hideSearch' => true,
                            ],
                            'options' => ['id' => 'listType', 'placeholder' => 'Выберите тип сортировки'],
                            'pluginOptions' => [
                                'placeholder' => false,
                                'hideSearch' => true,
                                'depends' => ['listFields'],
                                'nameParam' => 'name',
                                'url' => Url::to(['/projects/list-type-sort'])
                            ]
                        ])
                    ?>
                </div>

                <?php ActiveForm::end(); ?>

            <?php else : ?>

                <div class="col-md-6"></div>

            <?php endif; ?>

            <div class="col-md-3" style="padding: 0;">
                <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

                    <?=  Html::a( '<div class="new_hypothesis_link_block"><div>' . Html::img(['@web/images/icons/add_vector.png'], ['style' => ['width' => '35px']]) . '</div><div style="padding-left: 20px;">Новый проект</div></div>', ['/projects/get-hypothesis-to-create', 'id' => $user->getId()],
                        ['id' => 'showHypothesisToCreate', 'class' => 'new_hypothesis_link_plus pull-right']
                    ) ?>

                <?php endif; ?>
            </div>
        </div>

        <!--Заголовки для списка проектов-->
        <div class="row" style="display: flex; align-items: center; margin: 0 0 10px 0; padding: 10px;">

            <div class="col-lg-3 header_data_hypothesis">
                <div class="">Проект</div>
            </div>

            <div class="col-lg-3 header_data_hypothesis" style="padding-left: 10px;">
                Результат интеллектуальной деятельности
            </div>

            <div class="col-lg-2 header_data_hypothesis">
                Базовая технология
            </div>

            <div class="col-lg-4 header_data_hypothesis">
                Создан / Изменен
            </div>

        </div>

        <div class="block_all_projects_user">

            <!--Данные для списка проектов-->
            <?= $this->render('_index_ajax', ['models' => $models]) ?>
        </div>
    </div>

    <div class="form_authors" style="display: none;">

        <?php
        $form = ActiveForm::begin([
            'id' => 'form_authors'
        ]); ?>

        <div class="form_authors_inputs">

            <div class="row row-author row-author-" style="margin-bottom: 15px;">

                <?= $form->field($new_author, "[0]fio", [
                    'template' => '<div class="col-md-12" style="padding-left: 20px; margin-top: 15px;">{label}</div><div class="col-md-12" style="margin-bottom: 15px;">{input}</div>'
                ])->textInput([
                    'maxlength' => true,
                    'required' => true,
                    'id' => 'author_fio-',
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                    'autocomplete' => 'off'
                ]) ?>

                <?= $form->field($new_author, "[0]role", [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12" style="margin-bottom: 15px;">{input}</div>'
                ])->textInput([
                    'maxlength' => true,
                    'required' => true,
                    'id' => 'author_role-',
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                    'autocomplete' => 'off'
                ]) ?>

                <?= $form->field($new_author, "[0]experience", [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12" style="margin-bottom: 15px;">{input}</div>'
                ])->textarea([
                    'rows' => 2,
                    'id' => 'author_experience-',
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                ]) ?>

                <div class="col-md-12">

                    <?= Html::button('Удалить автора', [
                        'id' => 'remove-author-',
                        'class' => "remove-author btn btn-default",
                        'style' => [
                            'display' => 'flex',
                            'align-items' => 'center',
                            'justify-content' => 'center',
                            'background' => '#E0E0E0',
                            'color' => '#FFFFFF',
                            'width' => '200px',
                            'height' => '40px',
                            'font-size' => '24px',
                            'border-radius' => '8px',
                        ]
                    ]) ?>
                </div>
            </div>
        </div>
        <?php
        ActiveForm::end();
        ?>
    </div>

    <!--Модальные окна-->
    <?= $this->render('modal') ?>

</div>


<!--Подключение скриптов-->
<?php
$this->registerJsFile('@web/js/project_index.js');
$this->registerJsFile('@web/js/main_expertise.js');
?>