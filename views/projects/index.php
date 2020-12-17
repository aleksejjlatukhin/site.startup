<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\User;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;
use app\models\ProjectSort;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Проекты';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('@web/css/projects-index-style.css');

?>
<div class="projects-index">


    <div class="container-fluid container-data row">


        <div class="row row_header_data_generation" style="margin-top: 10px;">

            <?php
            $form = ActiveForm::begin([
                'id' => 'sorting_projects',
                'options' => ['class' => 'g-py-15'],
                'errorCssClass' => 'u-has-error-v1',
                'successCssClass' => 'u-has-success-v1-1',
            ]);
            ?>


            <?php

            $listFields = ProjectSort::getListFields();
            $listFields = ArrayHelper::map($listFields,'id', 'name');

            ?>


            <div class="col-md-3" style="font-size: 32px; font-weight: 700; padding: 0;">ПРОЕКТЫ</div>


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
                    ]);
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
                    ]);
                ?>
            </div>

            <?php
            ActiveForm::end();
            ?>

            <div class="col-md-3" style="padding: 0;">

                <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

                    <?=  Html::a( '<div class="new_hypothesis_link_block"><div>' . Html::img(['@web/images/icons/add_vector.png'], ['style' => ['width' => '35px']]) . '</div><div style="padding-left: 20px;">Новый проект</div></div>', ['/projects/get-hypothesis-to-create', 'id' => $user->id],
                        ['id' => 'showHypothesisToCreate', 'class' => 'new_hypothesis_link_plus pull-right']
                    );
                    ?>

                <?php endif; ?>

            </div>
        </div>


        <!--Заголовки для списка проектов-->
        <div class="row" style="margin: 0 0 10px 0; padding: 10px;">

            <div class="col-md-3 header_data_hypothesis">
                <div class="">Проект</div>
            </div>

            <div class="col-md-3 header_data_hypothesis" style="padding-left: 10px;">
                Результат интеллектуальной деятельности
            </div>

            <div class="col-md-2 header_data_hypothesis">
                Базовая технология
            </div>

            <div class="col-md-1 header_data_hypothesis text-center" style="margin-left: 3px;">
                Создан
            </div>

            <div class="col-md-1 header_data_hypothesis text-center" style="margin-left: 2px;">
                Изменен
            </div>

        </div>


        <div class="block_all_projects_user">

            <!--Данные для списка проектов-->
            <?php foreach ($models as $model) : ?>


                <div class="row container-one_hypothesis row_hypothesis-<?= $model->id;?>">

                    <div class="col-md-3">

                        <div class="project_name_table hypothesis_title">
                            <?= $model->project_name; ?>
                        </div>

                        <div class="project_description_text" title="<?= $model->description; ?>">
                            <?= $model->description; ?>
                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="text_14_table_project" title="<?= $model->rid; ?>">
                            <?= $model->rid; ?>
                        </div>

                    </div>

                    <div class="col-md-2">

                        <div class="text_14_table_project" title="<?= $model->technology; ?>">
                            <?= $model->technology; ?>
                        </div>

                    </div>

                    <div class="col-md-1 text-center">

                        <?= date('d.m.y', $model->created_at); ?>

                    </div>

                    <div class="col-md-1 text-center">

                        <?= date('d.m.y', $model->updated_at); ?>

                    </div>

                    <div class="col-md-2" style="padding-left: 20px; padding-right: 20px;">

                        <div class="row" style="display:flex; align-items: center; justify-content: space-between; padding-right: 15px;">

                             <?= Html::a('Далее', Url::to(['/segment/index', 'id' => $model->id]), [
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

                            <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

                                <?= Html::a(Html::img('/images/icons/update_warning_vector.png', ['style' => ['width' => '24px', 'margin-right' => '20px']]),['/projects/get-hypothesis-to-update', 'id' => $model->id], [
                                    'class' => 'update-hypothesis',
                                    'style' => ['margin-left' => '30px'],
                                    'title' => 'Редактировать',
                                ]); ?>

                                <?= Html::a(Html::img('/images/icons/icon_delete.png', ['style' => ['width' => '24px']]),['/projects/delete', 'id' => $model->id], [
                                    'class' => 'delete_hypothesis',
                                    'title' => 'Удалить',
                                ]); ?>

                            <?php else : ?>

                                <?= Html::a(Html::img('/images/icons/icon_view.png', ['style' => ['width' => '28px', 'margin-right' => '20px']]),['/projects/show-all-information', 'id' => $model->id], [
                                    'class' => 'openAllInformationProject',
                                    'style' => ['margin-left' => '30px'],
                                    'title' => 'Смотреть',
                                ]); ?>

                            <?php endif; ?>

                        </div>
                    </div>


                </div>

            <?php endforeach;?>

        </div>

    </div>



    <div class="form_authors" style="display: none;">

    <?php

    $form = ActiveForm::begin([
            'id' => 'form_authors'
    ]);

    ?>

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
                ]); ?>

                <?= $form->field($new_author, "[0]role", [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12" style="margin-bottom: 15px;">{input}</div>'
                ])->textInput([
                    'maxlength' => true,
                    'required' => true,
                    'id' => 'author_role-',
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                ]); ?>

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
                    ]); ?>
                </div>

            </div>


        </div>

    <?php
    ActiveForm::end();
    ?>

    </div>


    <!--Модальные окна-->
    <?= $this->render('modal'); ?>

</div>


<!--Подключение скриптов-->
<?php $this->registerJsFile('@web/js/project_index.js'); ?>