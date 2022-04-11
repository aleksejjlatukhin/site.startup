<?php

use yii\helpers\Html;

?>

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
                            <?= Html::a('Сводная таблица проекта', ['/client/expertise/get-project-summary-table', 'id' => $project->id], ['class' => 'link-menu-tasks']); ?>
                        </div>
                        <div class="col-md-3 text-center">
                            <?= Html::a('Поиск экспертов', ['/client/expertise/get-search-form-experts', 'id' => $project->id], ['class' => 'link-menu-tasks']); ?>
                        </div>
                        <div class="col-md-3 text-center">
                            <?= Html::a('Коммуникации', ['/client/communications/get-communications', 'id' => $project->id], ['class' => 'link-menu-tasks']); ?>
                        </div>
                        <div class="col-md-3 text-center">
                            <?= Html::a('Экспертизы', ['/client/expertise/get-expertise-by-project', 'id' => $project->id], ['class' => 'link-menu-tasks']); ?>
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

    <h3 class="text-center">По вашему запросу ничего не найдено...</h3>

<?php endif; ?>