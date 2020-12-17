<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\User;

?>


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
