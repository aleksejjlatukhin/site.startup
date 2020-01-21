<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RespondsGcp */

$this->title = 'Create Responds Gcp';
$this->params['breadcrumbs'][] = ['label' => 'Responds Gcps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="responds-gcp-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
