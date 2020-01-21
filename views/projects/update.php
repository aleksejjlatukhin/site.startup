<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Projects */

$this->title = 'Редактирование: ' . $model->project_name;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->project_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="projects-update">

    <h1>Общие данные о проекте</h1>

    <?= $this->render('_form_update', [
        'model' => $model,
        'modelsConcept' => $modelsConcept,
        'modelsAuthors' => $modelsAuthors,
    ]) ?>

</div>
