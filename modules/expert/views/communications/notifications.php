<?php

$this->title = 'Уведомления';
$this->registerCssFile('@web/css/notifications-style.css');

?>

<div class="row page-notifications">

    <div class="col-md-12 notifications-content">

        <?php if ($projects) : ?>

            <?php foreach ($projects as $key => $project) : ?>

                <div id="communications_project-<?= $project->id;?>">

                    <div class="container-one_hypothesis">

                        <div class="col-md-9 col-lg-10">
                            <div class="project_name_table">

                                <?= $project->project_name; ?> -<span class="project_fullname_text"><?= $project->project_fullname; ?></span>

                                <?php if ($countUnreadCommunications = $user->getCountUnreadCommunicationsByProject($project->id)) : ?>
                                    <div class="countUnreadCommunicationsByProject active pull-left"><?= $countUnreadCommunications; ?></div>
                                <?php endif; ?>

                            </div>
                        </div>

                        <div class="col-md-3 col-lg-2 informationAboutAction">
                            <b>Автор проекта:</b> <?= $project->user->username; ?>
                        </div>

                    </div>

                    <!--Блок для вывода уведомлений по проекту (коммуникаций)-->
                    <div class="hereAddProjectCommunications"></div>

                </div>

            <?php endforeach; ?>

        <?php else : ?>

            <h3 class="text-center">У вас пока нет уведомлений...</h3>

        <?php endif; ?>

    </div>

</div>

<!--Подключение скриптов-->
<?php $this->registerJsFile('@web/js/expert_notifications.js'); ?>