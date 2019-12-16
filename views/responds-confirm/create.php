<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RespondsConfirm */

$this->title = 'Create Responds Confirm';
$this->params['breadcrumbs'][] = ['label' => 'Responds Confirms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="responds-confirm-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
