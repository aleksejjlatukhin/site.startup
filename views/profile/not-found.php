<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Персональные данные';

?>

<?= $this->render('menu_user', [
    'user' => $user,
]) ?>


<div class="user-index col-md-9">

    <p style="text-align: center;padding-top: 20px;">У Вас пока нет проектов...</p>


    <script>

        $( ".catalog" ).dcAccordion({speed:300});

    </script>

</div>
