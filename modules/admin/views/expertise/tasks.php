<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'Назначение экспертов на проекты';
$this->registerCssFile('@web/css/expertise-tasks-style.css');
?>


<style>
    .select2-container--krajee .select2-selection--multiple {
        font-size: 16px;
        border-radius: 12px;
        border: 1px solid #828282;
        height: 100%;
        padding-bottom: 2px;
        padding-top: 2px;
    }
    .select2-container--krajee .select2-selection--multiple .select2-selection__choice,
    .select2-container--krajee .select2-selection {
        font-size: 16px;
    }
</style>


<div class="row expertise-tasks">

    <div class="col-md-7" style="margin-bottom: 15px; padding-left: 40px;">

        <?= Html::a($this->title . Html::img('/images/icons/icon_report_next.png'), ['#'],[
            'class' => 'link_to_instruction_page open_modal_instruction_page',
            'title' => 'Инструкция', 'onclick' => 'return false'
        ]); ?>

    </div>

    <div class="col-md-2">

        <?= Html::button( 'Поиск проектов',[
            'id' => 'show_search_tasks',
            'class' => 'btn btn-default',
            'style' => [
                'display' => 'flex',
                'align-items' => 'center',
                'justify-content' => 'center',
                'background' => '#669999',
                'color' => '#FFFFFF',
                'width' => '100%',
                'height' => '40px',
                'font-size' => '24px',
                'border-radius' => '8px',
                'margin-bottom' => '15px'
            ],
        ]);?>

    </div>

    <div class="col-md-3">

        <?= Html::a( 'Настройки коммуникаций',
            Url::to(['/admin/communications/settings']),[
            'class' => 'btn btn-success',
            'style' => [
                'display' => 'flex',
                'align-items' => 'center',
                'justify-content' => 'center',
                'background' => '#52BE7F',
                'width' => '100%',
                'height' => '40px',
                'font-size' => '24px',
                'border-radius' => '8px',
                'margin-bottom' => '15px'
            ],
        ]);?>

    </div>

    <div class="col-md-12 search-block">

        <?php $form = ActiveForm::begin([
            'id' => 'search_expertise_tasks',
            'action' => Url::to(['/admin/expertise/tasks']),
            'options' => ['class' => 'g-py-15'],
            'errorCssClass' => 'u-has-error-v1',
            'successCssClass' => 'u-has-success-v1-1',
        ]); ?>

        <?= $form->field($searchForm, 'search', ['template' => '{input}'])
            ->textInput([
                'id' => 'search_tasks',
                'placeholder' => 'Поиск по названию и автору проекта (необходимо ввести не менее 5 символов)',
                'class' => 'style_form_field_respond',
                'minlength' => 5,
                'autocomplete' => 'off'])
            ->label(false);
        ?>

        <?php ActiveForm::end(); ?>

    </div>

    <div class="col-md-12 expertise-tasks-content">

        <?php if ($projects) : ?>

            <?php foreach ($projects as $project) : ?>

                <div id="expertise_task-<?= $project->id;?>">

                    <div class="container-one_hypothesis">

                        <div class="col-md-9 col-lg-10">
                            <div class="project_name_table">
                                <?= $project->project_name; ?> -<span class="project_fullname_text"><?= $project->project_fullname; ?></span>
                            </div>
                        </div>

                        <div class="col-md-3 col-lg-2 informationAboutAction">
                            <b>Автор проекта:</b> <?= $project->user->second_name.' '.$project->user->first_name.' '.$project->user->middle_name; ?>
                        </div>

                    </div>

                    <div class="hereAddDataOfProject">
                        <!--Меню по экспертизам проекта-->
                        <div class="block-links-menu-tasks">
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <?= Html::a('Сводная таблица проекта', ['/admin/expertise/get-project-summary-table', 'id' => $project->id], ['class' => 'link-menu-tasks']); ?>
                                </div>
                                <div class="col-md-3 text-center">
                                    <?= Html::a('Поиск экспертов', ['/admin/expertise/get-search-form-experts', 'id' => $project->id], ['class' => 'link-menu-tasks']); ?>
                                </div>
                                <div class="col-md-3 text-center">
                                    <?= Html::a('Коммуникации', ['/admin/communications/get-communications', 'id' => $project->id], ['class' => 'link-menu-tasks']); ?>
                                </div>
                                <div class="col-md-3 text-center">
                                    <?= Html::a('Экспертизы', ['/admin/expertise/get-expertise-by-project', 'id' => $project->id], ['class' => 'link-menu-tasks']); ?>
                                </div>
                            </div>
                        </div>

                        <!--Блок для вывода контента меню-->
                        <div class="block-tasks-content"></div>
                    </div>

                </div>

            <?php endforeach; ?>

            <div class="pagination-admin-projects-result">
                <?= \yii\widgets\LinkPager::widget([
                    'pagination' => $pages,
                    'activePageCssClass' => 'pagination_active_page',
                    'options' => ['class' => 'admin-projects-result-pagin-list'],
                ]); ?>
            </div>

        <?php else : ?>

            <h3 class="text-center">Пока нет проектов...</h3>

        <?php endif; ?>

    </div>

</div>


<!--Подключение скриптов-->
<?php $this->registerJsFile('@web/js/expertise_task.js'); ?>
