<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$project = \app\models\Projects::findOne(\Yii::$app->request->get('id'));


$this->title = 'Админка | Профиль | Презентационные файлы проекта ' . '"' . mb_strtolower($project->project_name) . '"';

?>

<div class="row">

    <?= $this->render('menu_user', [
        'user' => $user,
    ]) ?>


    <div class="user-index col-md-9" style="padding-left: 0;">

        <h5 class="d-inline p-2" style="font-weight: 700;text-transform: uppercase;text-align: center; background-color: #0972a5;color: #fff; height: 50px; line-height: 50px;margin-bottom: 0;">
            <div class="row">

                <?= Html::encode($this->title) ?>

            </div>
        </h5>

        <br>

        <?php if (!empty($model->preFiles)) : ?>

            <ul style="text-decoration: none;padding: 0;list-style: none;">
                <?php foreach ($model->preFiles as $file) : ?>
                    <li style="padding: 2px 0;">
                        <?= Html::a($file->file_name, ['/projects/download', 'id' => $file->id], ['class' => 'btn btn-default'])?>
                    </li>
                <?php endforeach;?>
            </ul>

        <?php else: ?>

            <p style="text-align: center;">Файлы пока не добавлены...</p>

        <?php endif; ?>

        <script>

            $( ".catalog" ).dcAccordion({speed:300});

        </script>

    </div>

</div>