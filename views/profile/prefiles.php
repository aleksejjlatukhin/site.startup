<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$project = \app\models\Projects::findOne(\Yii::$app->request->get('id'));


$this->title = 'Презентационные файлы проекта ' . '"' . mb_strtolower($project->project_name) . '"';

?>

<?= $this->render('menu_user', [
    'user' => $user,
]) ?>


<div class="user-index col-md-9">

    <br>

    <?php if (!empty($model->preFiles)) : ?>

        <ul style="text-decoration: none;padding: 0;">
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
