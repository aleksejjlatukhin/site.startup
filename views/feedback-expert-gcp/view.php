<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\FeedbackExpertGcp */

$this->title = 'Описание отзыва №' . mb_substr($model->title, -2, 3);
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
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="feedback-expert-gcp-view">

    <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

        <h2><?= Html::encode($this->title) ?></h2>

        <p>
            <?= Html::a('<< Программа подтверждения', ['confirm-gcp/view', 'id' => $model->confirm_gcp_id], ['class' => 'btn btn-default']) ?>
            <?= Html::a('Редактировать отзыв', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        </p>

    <?php else : ?>

        <h2>
            <span style="margin-right: 30px;"><?= Html::encode($this->title) ?></span>
            <?= Html::a('<< Программа подтверждения', ['confirm-gcp/view', 'id' => $model->confirm_gcp_id], ['class' => 'btn btn-sm btn-default']) ?>
        </h2>

    <?php endif; ?>


    <div class="row">
        <div class="col-md-8">

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'name',
                    'position',

                    [
                        'attribute' => 'feedback_file',
                        'value' => function($model){
                            if (!empty($model->feedback_file)){
                                $string = '';
                                $string .= Html::a($model->feedback_file, ['download', 'id' => $model->id], ['class' => '']);
                                return $string;
                            }
                        },
                        'format' => 'html',
                    ],

                    'comment',

                    [
                        'attribute' => 'date_feedback',
                        'format' => ['date', 'dd.MM.yyyy'],
                    ],
                ],
            ]) ?>

        </div>
    </div>

</div>
