<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Projects */

$this->title = 'Редактирование: ' . $model->project_name;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['index', 'id' => $model->user_id]];
$this->params['breadcrumbs'][] = ['label' => $model->project_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="projects-update">

    <h2>Общие данные о проекте</h2>

    <?= $this->render('_form_update', [
        'model' => $model,
        'modelsAuthors' => $modelsAuthors,
    ]) ?>

</div>
