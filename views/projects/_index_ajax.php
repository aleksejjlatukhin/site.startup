<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\User;

?>


<!--Данные для списка проектов-->
<?php foreach ($models as $model) : ?>


    <div class="row container-one_respond row_hypothesis-<?= $model->id;?>" style="margin: 3px 0; padding: 0;">

        <div class="col-md-3">

            <div class="project_name_table hypothesis_title">
                <?= $model->project_name; ?>
            </div>

            <div class="project_description_text">
                <?php

                $description = $model->description;
                if (mb_strlen($description) > 50) {
                    $description = mb_substr($description, 0, 50) . '...';
                }

                echo '<div title="'.$model->description.'">' . $description . '</div>';

                ?>
            </div>

        </div>


        <div class="col-md-3">

            <?php

            $rid = $model->rid;

            if (mb_strlen($rid) > 80) {
                $rid = mb_substr($rid, 0, 80)  . ' ...';
            }

            echo '<div class="text_14_table_project" title="' . $model->rid . '">' . $rid . '</div>';

            ?>

        </div>

        <div class="col-md-2">

            <?php

            $technology = $model->technology;

            if (mb_strlen($technology) > 50) {
                $technology = mb_substr($technology, 0, 50) . ' ...';
            }

            echo '<div class="text_14_table_project" title="' . $model->technology . '">' . $technology . '</div>';

            ?>

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

                <?php else : ?>

                    <?= Html::a(Html::img('/images/icons/icon_view.png', ['style' => ['width' => '28px', 'margin-right' => '20px']]),['/projects/show-all-information', 'id' => $model->id], [
                        'class' => 'openAllInformationProject',
                        'style' => ['margin-left' => '30px'],
                        'title' => 'Смотреть',
                    ]); ?>

                <?php endif; ?>

                <?= Html::a(Html::img('/images/icons/icon_delete.png', ['style' => ['width' => '24px']]),['/projects/delete', 'id' => $model->id], [
                    'class' => 'delete_hypothesis',
                    'title' => 'Удалить',
                ]); ?>

            </div>
        </div>


    </div>

<?php endforeach;?>
