<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\BusinessModel */

$this->title = 'Бизнес-модель';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index', 'id' => $project->user_id]];
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
$this->params['breadcrumbs'][] = ['label' => 'Описание ' . $mvp->title, 'url' => ['mvp/view', 'id' => $mvp->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа подтверждения ' . $mvp->title, 'url' => ['confirm-mvp/view', 'id' => $confirmMvp->id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="business-model-view">

    <p>

        <span style="font-size: 30px;"><?= $this->title; ?></span>

        <?php echo Html::a( Html::img(['@web/images/icons/icon-pdf.png'], ['style' => ['width' => '30px', 'margin-bottom' => '15px', 'margin-left' => '5px']]), ['/business-model/mpdf-business-model', 'id' => $model->id], [
            //'class'=>'btn btn-default',
            'target'=>'_blank',
            'data-toggle'=>'tooltip',
            'title'=> 'Скачать в pdf',
        ]);?>

        <?= Html::a('Сводная таблица проекта', ['projects/result', 'id' => $project->id], ['class' => 'btn btn-default pull-right', 'style' => ['margin-left' => '5px']]) ?>

        <?= Html::a('Дорожная карта сегмента', ['segment/one-roadmap', 'id' => $segment->id], ['class' => 'btn btn-success pull-right']) ?>

        <?php if (User::isUserSimple(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])) : ?>

            <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary pull-right', 'style' => ['margin-right' => '5px']]) ?>

        <?php endif; ?>
    </p>

    <div style="display: flex; flex: auto; flex-wrap: wrap">
        <div>
            <div class="block-200"><h4 style="color: #3c3c3c">Потребительский сегмент</h4><?= $segment->name; ?></div>
            <div class="block-200"><h4 style="color: #3c3c3c">Потенциальное количество потребителей</h4>
                <?= ' от ' . number_format($segment->quantity_from * 1000, 0, '', ' ') .
                '<br> до ' . number_format($segment->quantity_to * 1000, 0, '', ' '); ?></div>
        </div>
        <div class="block-200"><h4 style="color: #3c3c3c">Ключевые партнеры: </h4><?= $model->partners; ?></div>
        <div>
            <div class="block-200"><h4 style="color: #3c3c3c">Ключевые виды деятельности</h4><?= mb_strtolower($segment->sort_of_activity); ?></div>
            <div class="block-200"><h4 style="color: #3c3c3c">Ключевые ресурсы</h4><?= $model->resources; ?></div>
        </div>
        <div class="block-200"><h4 style="color: #3c3c3c">Ценностное предложение</h4><?= $gcp->description; ?></div>
        <div>
            <div class="block-200"><h4 style="color: #3c3c3c">Взаимоотношения с клиентами</h4><?= $model->relations; ?></div>
            <div class="block-200"><h4 style="color: #3c3c3c">Каналы коммуникации и сбыта</h4><?= $model->distribution_of_sales; ?></div>
        </div>
        <div style="justify-content: space-between; display: flex">
        <div class="block-100"><h4 style="color: #3c3c3c">Структура издержек</h4><?= $model->cost; ?></div>
        <div class="block-100"><h4 style="color: #3c3c3c">Потоки поступления доходов</h4><?= $model->revenue; ?></div>
        </div>
    </div>

</div>
