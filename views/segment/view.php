<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;
use app\models\Segment;

/* @var $this yii\web\View */
/* @var $model app\models\Segment */

$this->title = 'Сегмент: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index', 'id' => $project->user_id]];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = $model->name;
\yii\web\YiiAsset::register($this);
?>
<div class="segment-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <?php if (User::isUserSimple(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])) : ?>

        <p>

            <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?/*= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы точно хотите удалить сегмент ' . $model->name . '?',
                    'method' => 'post',
                ],
            ]) */?>

            <? if (!empty($model->creat_date)) {
                    echo Html::a('Дорожная карта сегмента', ['segment/one-roadmap', 'id' => $model->id], ['class' => 'btn btn-default pull-right']);
            }?>

            <?php if(!($model->interview)) : ?>
                <?= Html::a('Переход к генерации ГПС* >>', ['interview/create', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
            <?php else: ?>
                <?= Html::a('Генерация ГПС* >>', ['interview/view', 'id' => $model->interview->id], ['class' => 'btn btn-success']) ?>
            <?php endif;?>

        </p>

    <?php else : ?>

        <p>

            <? if (!empty($model->creat_date)) {

                echo Html::a('Дорожная карта сегмента', ['segment/one-roadmap', 'id' => $model->id], ['class' => 'btn btn-default']);

            }?>

            <?php if(!($model->interview)) : ?>

                <p style="margin-top: 20px;"></p>

            <?php else: ?>

                <?= Html::a('Генерация ГПС* >>', ['interview/view', 'id' => $model->interview->id], ['class' => 'btn btn-success']) ?>

            <?php endif;?>

        </p>

    <?php endif; ?>

    <?= $model->allInformation; ?>
    
    <div style="font-style: italic"><span class="bolder">Генерация ГПС*</span> - генерация гипотез проблем сегмента</div>

</div>
