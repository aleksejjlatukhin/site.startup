<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Данные пользователя';

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

    <div style="display:flex;border-bottom: 1px solid #ccc;padding: 20px 0 10px 0;">

        <div class="col-md-4">Дата регистрации: <span style="font-weight: 700;"><?= date('d.m.Y', $user['created_at']); ?></span></div>
        <div class="col-md-4">Последнее изменение: <span style="font-weight: 700;"><?= date('d.m.Y', $user['updated_at']); ?></span></div>
        <div class="col-md-4">Статус:
            <span style="font-weight: 700;">
                <? if ($user['status'] == 10) echo '<span style="color: green;">активирован</span>'; ?>
                <? if ($user['status'] == 1) echo '<span style="color: #0972a5;">не активирован</span>'; ?>
                <? if ($user['status'] == 0) echo '<span style="color: red;">заблокирован</span>'; ?>
            </span>
        </div>
    </div>

    <div style="display:flex;border-bottom: 1px solid #ccc;padding: 10px 0;">

        <div class="col-md-4">Фамилия: <span style="font-weight: 700;"><?= $user['second_name']; ?></span></div>
        <div class="col-md-4">Имя: <span style="font-weight: 700;"><?= $user['first_name']; ?></span></div>
        <div class="col-md-4">Отчество: <span style="font-weight: 700;"><?= $user['middle_name']; ?></span></div>

    </div>

    <div style="display:flex;border-bottom: 1px solid #ccc;padding: 10px 0;">

        <div class="col-md-4">Логин: <span style="font-weight: 700;"><?= $user['username']; ?></span></div>
        <div class="col-md-4">Эл.почта: <span style="font-weight: 700;"><?= $user['email']; ?></span></div>
        <div class="col-md-4">Телефон: <span style="font-weight: 700;"><?= $user['telephone']; ?></span></div>

    </div>




    <script>

        $( ".catalog" ).dcAccordion({speed:300});

    </script>

</div>
