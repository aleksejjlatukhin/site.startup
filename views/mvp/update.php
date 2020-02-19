<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Mvp */

$this->title = 'Редактирование ' . $model->title ;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index']];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа генерации ГПС', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $generationProblem->title, 'url' => ['generation-problem/view', 'id' => $generationProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа подтверждения ' . $generationProblem->title, 'url' => ['confirm-problem/view', 'id' => $confirmProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Разработка ГЦП', 'url' => ['gcp/index', 'id' => $confirmProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $gcp->title, 'url' => ['gcp/view', 'id' => $gcp->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа подтверждения ' . $gcp->title, 'url' => ['confirm-gcp/view', 'id' => $confirmGcp->id]];
$this->params['breadcrumbs'][] = ['label' => 'Разработка ГMVP', 'url' => ['mvp/index', 'id' => $confirmGcp->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание ' . $model->title, 'url' => ['mvp/view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="mvp-update">

    <div class="row">
        <div class="col-md-8">

            <h2><?= 'Внесите изменения в формулировку ' . $model->title ?></h2>

            <p style="text-indent: 20px;">Minimum Viable Product(MVP) — минимально жизнеспособный продукт,
                концепция минимализма программной комплектации выводимого на рынок устройства.
                Минимально жизнеспособный продукт - продукт, обладающий минимальными,
                но достаточными для удовлетворения первых потребителей функциями.
                Основная задача — получение обратной связи для формирования гипотез дальнейшего развития продукта.</p>

        </div>
    </div>

    <?= $this->render('_form_update', [
        'model' => $model,
    ]) ?>

</div>