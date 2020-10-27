<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Segment;

/* @var $this yii\web\View */
/* @var $model app\models\ConfirmGcp */

$this->title = 'Подтверждение гипотез ценностных предложений';

$this->registerCssFile('@web/css/confirm-gcp-create-style.css');
?>


<div class="confirm-gcp-create">

    <?= $this->render('_form', [
        'model' => $model,
        'gcp' => $gcp,
        'confirmProblem' => $confirmProblem,
        'generationProblem' => $generationProblem,
        'interview' => $interview,
        'segment' => $segment,
        'project' => $project,
    ]) ?>

</div>
