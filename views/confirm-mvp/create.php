<?php

use yii\helpers\Html;
use  yii\widgets\DetailView;
use app\models\Segment;

/* @var $this yii\web\View */
/* @var $model app\models\ConfirmMvp */

$this->title = 'Подтверждение MVP';

$this->registerCssFile('@web/css/confirm-mvp-create-style.css');

?>
<div class="confirm-mvp-create">

    <?= $this->render('_form', [
        'model' => $model,
        'mvp' => $mvp,
        'confirmGcp' => $confirmGcp,
        'gcp' => $gcp,
        'confirmProblem' => $confirmProblem,
        'generationProblem' => $generationProblem,
        'interview' => $interview,
        'segment' => $segment,
        'project' => $project,
    ]) ?>

</div>
