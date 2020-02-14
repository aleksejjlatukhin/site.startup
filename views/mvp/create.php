<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Mvp */

$this->title = 'Создание гипотезы MVP';
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
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mvp-create">

    <div class="row">
        <div class="col-md-8">

            <p>

                <span style="font-size: 30px;"><?= $this->title; ?></span>

                <?= Html::a('Разработка ГMVP', ['mvp/index', 'id' => $confirmGcp->id], ['class' => 'btn btn-default pull-right']) ?>

            </p>

            <p style="text-indent: 20px;">Minimum Viable Product(MVP) — минимально жизнеспособный продукт,
                концепция минимализма программной комплектации выводимого на рынок устройства.
                Минимально жизнеспособный продукт - продукт, обладающий минимальными,
                но достаточными для удовлетворения первых потребителей функциями.
                Основная задача — получение обратной связи для формирования гипотез дальнейшего развития продукта.</p>

        </div>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'models' => $models,
        'confirmGcp' => $confirmGcp,
        'gcp' => $gcp,
        'confirmProblem' => $confirmProblem,
        'generationProblem' => $generationProblem,
        'interview' => $interview,
        'segment' => $segment,
        'project' => $project,
    ]) ?>

</div>
