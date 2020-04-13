<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\User;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Админка | Пользователи';
?>
<div class="users-admins">

    <h3><?= $this->title; ?></h3>

    <div class="">
        <?= Html::a('Проектанты',Url::to(['/admin/users/index']), ['class' => 'btn btn-default'])?>
        <?= Html::a('Администраторы',Url::to(['/admin/users/admins']), ['class' => 'btn btn-success'])?>
    </div>

    <br>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['width' => '70'],
        //'summary' => false,
        'columns' => [

            [
                'header' => '<div style="text-align: center;height: 40px;line-height: 40px;">№</div>',
                'class' => 'yii\grid\SerialColumn',
                'options' => ['width' => '20'],
            ],

            /*[
                'attribute' => 'id',
                'enableSorting' => false,
            ],*/

            [
                'header' => '<div style="text-align: center;height: 40px;line-height: 40px;">Фото</div>',
                'attribute' => 'avatar_image',
                'label' => 'Фото',
                'value' => function($data){
                    return Html::img('@web' . $data->avatar_image, ['alt' => 'Аватарка', 'style' => ['width' => '35px', 'height' => '35px']]);
                },
                'format' => 'html',
                'options' => ['width' => '45'],
                'enableSorting' => false,
            ],

            [
                'header' => '<div style="text-align: center;height: 40px;line-height: 40px;">ФИО</div>',
                'attribute' => 'fio',
                'label' => 'ФИО',
                'value' => function ($data) {
                    return $data->second_name . ' ' . $data->first_name . ' ' . $data->middle_name;
                },
                'format' => 'html',
                'options' => ['width' => '200'],
                'enableSorting' => false,
            ],

            [
                'header' => '<div style="text-align: center;height: 40px;line-height: 40px;">Логин</div>',
                'attribute' => 'username',
                'value' => function($data){
                    return '<div style="text-align: center">' . $data->username . '</div>';
                },
                'format' => 'html',
                'enableSorting' => false,
                'options' => ['width' => '120'],
            ],

            [
                'header' => '<div style="text-align: center;height: 40px;line-height: 40px;">Электронная почта</div>',
                'attribute' => 'email',
                'label' => 'Электронная почта',
                'value' => function($data){
                    return '<div style="text-align: center">' . $data->email . '</div>';
                },
                'format' => 'html',
                'enableSorting' => false,
                'options' => ['width' => '200'],
            ],

            [
                'header' => '<div style="text-align: center;height: 40px;line-height: 40px;">Телефон</div>',
                'attribute' => 'telephone',
                'value' => function($data){
                    if(!empty($data->telephone)){
                        return '<div style="text-align: center">' . $data->telephone . '</div>';
                    }
                },
                'format' => 'html',
                'options' => ['width' => '140'],
                'enableSorting' => false,
            ],

            [
                'header' => '<div style="text-align: center;height: 40px;line-height: 40px;">Статус</div>',
                'attribute' => 'status',
                'value' => function($data) use ($form){

                    $string = '';

                    if ($data->status === User::STATUS_DELETED){
                        $string = '<div style="color: red;text-align: center;">Заблокирован</div>';
                    }elseif ($data->status === User::STATUS_NOT_ACTIVE){
                        $string = '<div style="color: blue;text-align: center;">Не активирован</div>';
                    }elseif ($data->status === User::STATUS_ACTIVE){
                        $string = '<div style="color: green;text-align: center;">Активирован</div>';
                    }


                    return $string;
                },
                'format' => 'raw',
                'options' => ['width' => '150'],
                'enableSorting' => false,
            ],

            [
                'header' => '<div style="text-align: center">Последнее изменение</div>',
                'attribute' => 'updated_at',
                //'format' => ['date', 'dd.MM.yyyy'],
                'value' => function($data){
                    return '<div style="text-align: center">' . date('d.m.yy', $data->updated_at) . '</div>';
                },
                'format' => 'html',
                'options' => ['width' => '120'],
                'enableSorting' => false,
            ],

            [
                'header' => '<div style="text-align: center">Дата регистрации</div>',
                'attribute' => 'created_at',
                //'format' => ['date', 'dd.MM.yyyy'],
                'value' => function($data){
                    return '<div style="text-align: center">' . date('d.m.yy', $data->created_at) . '</div>';
                },
                'format' => 'html',
                'options' => ['width' => '120'],
                'enableSorting' => false,
            ],

        ],
    ]); ?>

</div>
