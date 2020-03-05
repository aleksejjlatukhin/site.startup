<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Данные отсутствуют';

?>

<?= $this->render('menu_user', [
    'user' => $user,
]) ?>


<div class="user-index col-md-9" style="padding-left: 0;">

    <h5 class="d-inline p-2" style="font-weight: 700;text-transform: uppercase;text-align: center; background-color: #0972a5;color: #fff; height: 50px; line-height: 50px;margin-bottom: 0;">
        <div class="row">

            <?= Html::encode($this->title) ?>

        </div>
    </h5>

    <p style="text-align: center;padding-top: 20px;">У Вас пока нет проектов...</p>

    <div style="text-align: center;">

        <?= Html::a('Создать проект', Url::to(['/projects/create']), ['class' => 'btn btn-md btn-success'])?>

    </div>

    <script>

        $( ".catalog" ).dcAccordion({speed:300});

    </script>

</div>
