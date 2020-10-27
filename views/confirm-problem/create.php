<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Segment;

/* @var $this yii\web\View */
/* @var $model app\models\ConfirmProblem */

$this->title = 'Подтверждение гипотезы проблемы сегмента';

$this->registerCssFile('@web/css/confirm-problem-create-style.css');
?>
<div class="confirm-problem-create">

    <?= $this->render('_form', [
        'model' => $model,
        'generationProblem' => $generationProblem,
        'interview' => $interview,
        'segment' => $segment,
        'project' => $project,
    ]) ?>

</div>
