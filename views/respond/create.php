<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Respond */

$this->title = 'Create Respond';
$this->params['breadcrumbs'][] = ['label' => 'Responds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="respond-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
