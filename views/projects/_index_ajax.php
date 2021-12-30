<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\User;
use app\models\EnableExpertise;
use app\models\StageExpertise;

?>


<!--Данные для списка проектов-->
<?php foreach ($models as $model) : ?>

    <div class="row container-one_hypothesis row_hypothesis-<?= $model->id;?>">

        <div class="col-lg-3">

            <div class="project_name_table hypothesis_title">
                <?= $model->project_name; ?>
            </div>

            <div class="project_description_text" title="<?= $model->description; ?>">
                <?= $model->description; ?>
            </div>

        </div>


        <div class="col-lg-3">

            <div class="text_14_table_project" title="<?= $model->rid; ?>">
                <?= $model->rid; ?>
            </div>

        </div>

        <div class="col-lg-2">

            <div class="text_14_table_project" title="<?= $model->technology; ?>">
                <?= $model->technology; ?>
            </div>

        </div>

        <div class="col-lg-4">

            <div style="display:flex; align-items: center; justify-content: space-between; padding-right: 15px;">

                <div style="min-width: 130px;"><?= date('d.m.y', $model->created_at) . ' / ' . date('d.m.y', $model->updated_at); ?></div>

                <div class="pull-right" style="display: flex; align-items: center; justify-content: space-between;">

                    <?php if ($model->getEnableExpertise() == EnableExpertise::OFF) : ?>

                        <?= Html::a('Далее', ['#'], [
                            'disabled' => true,
                            'onclick' => 'return false;',
                            'title' => 'Необходимо разрешить экспертизу',
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
                        ]); ?>

                    <?php elseif ($model->getEnableExpertise() == EnableExpertise::ON) : ?>

                        <?= Html::a('Далее', Url::to(['/segments/index', 'id' => $model->id]), [
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
                        ]); ?>

                    <?php endif; ?>

                    <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

                        <?php if ($model->getEnableExpertise() == EnableExpertise::OFF) : ?>

                            <?= Html::a(Html::img('/images/icons/icon-enable-expertise-danger.png', ['style' => ['width' => '35px', 'margin-right' => '10px']]),['/projects/enable-expertise', 'id' => $model->id], [
                                'class' => 'link-enable-expertise',
                                'style' => ['margin-left' => '20px'],
                                'title' => 'Разрешить экспертизу',
                            ]); ?>

                            <?= Html::a(Html::img('/images/icons/update_warning_vector.png', ['style' => ['width' => '24px', 'margin-right' => '20px']]),['/projects/get-hypothesis-to-update', 'id' => $model->id], [
                                'class' => 'update-hypothesis',
                                'style' => ['margin-left' => '10px'],
                                'title' => 'Редактировать',
                            ]); ?>

                            <?= Html::a(Html::img('/images/icons/icon_delete.png', ['style' => ['width' => '24px']]),['/projects/delete', 'id' => $model->id], [
                                'class' => 'delete_hypothesis',
                                'title' => 'Удалить',
                            ]); ?>

                        <?php elseif ($model->getEnableExpertise() == EnableExpertise::ON) : ?>

                            <?= Html::a(Html::img('/images/icons/icon-enable-expertise-success.png', ['style' => ['width' => '35px', 'margin-right' => '10px']]),['/expertise/get-list', 'stage' => StageExpertise::getList()[StageExpertise::PROJECT], 'stageId' => $model->id], [
                                'class' => 'link-get-list-expertise',
                                'style' => ['margin-left' => '20px'],
                                'title' => 'Смотреть экспертизу',
                            ]); ?>

                            <?= Html::a(Html::img('/images/icons/icon_view.png', ['style' => ['width' => '28px', 'margin-right' => '42px']]),['/projects/show-all-information', 'id' => $model->id], [
                                'class' => 'openAllInformationProject',
                                'style' => ['margin-left' => '8px'],
                                'title' => 'Смотреть описание проекта',
                            ]); ?>

                        <?php endif; ?>

                    <?php elseif (User::isUserExpert(Yii::$app->user->identity['username'])) : ?>

                        <?php if ($model->getEnableExpertise() == EnableExpertise::OFF) : ?>

                            <?= Html::a(Html::img('/images/icons/icon-enable-expertise-danger.png', ['style' => ['width' => '35px', 'margin-right' => '10px']]),['#'], [
                                'onclick' => 'return false;',
                                'class' => 'link-enable-expertise',
                                'style' => ['margin-left' => '30px'],
                                'title' => 'Экспертиза не разрешена',
                            ]); ?>

                        <?php elseif ($model->getEnableExpertise() == EnableExpertise::ON) : ?>

                            <?= Html::a(Html::img('/images/icons/icon-enable-expertise-success.png', ['style' => ['width' => '35px', 'margin-right' => '10px']]),['/expertise/get-list', 'stage' => StageExpertise::getList()[StageExpertise::PROJECT], 'stageId' => $model->id], [
                                'class' => 'link-get-list-expertise',
                                'style' => ['margin-left' => '30px'],
                                'title' => 'Экспертиза',
                            ]); ?>

                        <?php endif; ?>

                        <?= Html::a(Html::img('/images/icons/icon_view.png', ['style' => ['width' => '28px', 'margin-right' => '20px']]),['/projects/show-all-information', 'id' => $model->id], [
                            'class' => 'openAllInformationProject',
                            'style' => ['margin-left' => '8px'],
                            'title' => 'Смотреть описание проекта',
                        ]); ?>

                    <?php else : ?>

                        <?php if ($model->getEnableExpertise() == EnableExpertise::OFF) : ?>

                            <?= Html::a(Html::img('/images/icons/icon-enable-expertise-danger.png', ['style' => ['width' => '35px', 'margin-right' => '10px']]),['#'], [
                                'onclick' => 'return false;',
                                'class' => 'link-enable-expertise',
                                'style' => ['margin-left' => '30px'],
                                'title' => 'Экспертиза не разрешена',
                            ]); ?>

                        <?php elseif ($model->getEnableExpertise() == EnableExpertise::ON) : ?>

                            <?= Html::a(Html::img('/images/icons/icon-enable-expertise-success.png', ['style' => ['width' => '35px', 'margin-right' => '10px']]),['/expertise/get-list', 'stage' => StageExpertise::getList()[StageExpertise::PROJECT], 'stageId' => $model->id], [
                                'class' => 'link-get-list-expertise',
                                'style' => ['margin-left' => '30px'],
                                'title' => 'Смотреть экспертизу',
                            ]); ?>

                        <?php endif; ?>

                        <?= Html::a(Html::img('/images/icons/icon_view.png', ['style' => ['width' => '28px', 'margin-right' => '20px']]),['/projects/show-all-information', 'id' => $model->id], [
                            'class' => 'openAllInformationProject',
                            'style' => ['margin-left' => '8px'],
                            'title' => 'Смотреть описание проекта',
                        ]); ?>

                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
<?php endforeach;?>
