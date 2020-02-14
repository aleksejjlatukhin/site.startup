<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mvp */

$this->title = 'Описание ' . $model->title;
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
\yii\web\YiiAsset::register($this);
?>
<div class="mvp-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <p>
        <?= Html::a('Редактирование', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

        <?php if (empty($model->confirm)) : ?>
            <?= Html::a('Подтвердить ГMVP >>', ['confirm-mvp/create', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?php else: ?>
            <?= Html::a('Подтверждение ГMVP', ['confirm-mvp/view', 'id' => $model->confirm->id], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>

        <?php if ($model->exist_confirm == 1){

            if (!empty($model->confirm->business)){

                echo Html::a('Показать бизнес-модель >>', ['business-model/view', 'id' => $model->confirm->business->id], ['class' => 'btn btn-default']);

            }else{

                echo Html::a('Генерация бизнес-модели >>', ['business-model/create', 'id' => $model->confirm->id], ['class' => 'btn btn-default']);
            }

        }?>

    </p>

    <div class="row">
        <div class="col-md-8">

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'description:ntext',

                    [
                        'attribute' => 'date_create',
                        'format' => ['date', 'dd.MM.yyyy'],
                    ],

                    [
                        'attribute' => 'exist_confirm',
                        'label' => 'Результаты подтверждения',
                        'visible' => ($model->exist_confirm !== null),
                        'value' => function($model){

                            $c = 0; $d = 0; $e = 0;

                            $responds = $model->confirm->responds;

                            foreach ($responds as $respond){
                                if ($respond->descInterview->status === 0){
                                    $c++;
                                }
                                if ($respond->descInterview->status === 1){
                                    $d++;
                                }
                                if ($respond->descInterview->status === 2){
                                    $e++;
                                }
                            }

                            return '<p>"Хочу купить": <span style="color: green">' . $e . '</span></p>
                <p>"Привлекательно": <span style="color: blue">' . $d . '</span></p>
                <p>"Не интересно": <span style="color: red">' . $c . '</span></p>';
                        },
                        'format' => 'html',
                    ],

                    [
                        'attribute' => 'statuses',
                        'label' => 'Статус подтверждения',
                        'visible' => ($model->exist_confirm !== null),
                        'value' => function($model) {
                            if ($model->exist_confirm == 0) {
                                return '<span style="color:red">ГMVP не подтверждена</span>';
                            }
                            if ($model->exist_confirm == 1) {
                                return '<span style="color:green">ГMVP подтверждена</span>';
                            }
                        },
                        'format' => 'html',
                    ],

                    [
                        'attribute' => 'date_confirm',
                        'visible' => ($model->date_confirm !== null),
                        'format' => ['date', 'dd.MM.yyyy'],
                    ],
                ],
            ]) ?>

        </div>
    </div>

</div>
