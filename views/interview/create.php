<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Interview */

$this->title = 'Подтверждение гипотезы целевого сегмента';

$this->registerCssFile('@web/css/interview-create-style.css');
?>

<div class="interview-create">

    <?= $this->render('_form', [
        'model' => $model,
        'segment' => $segment,
        'project' => $project,
        'newQuestions' => $newQuestions,
    ]) ?>

</div>
