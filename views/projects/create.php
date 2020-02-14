<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Projects */

$this->title = 'Создание проекта';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="projects-create">

    <h2>Общие данные о проекте</h2>

    <?= $this->render('_form', [
        'model' => $model,
        'modelsConcept' => $modelsConcept,
        'modelsAuthors' => $modelsAuthors,
    ]) ?>

</div>
