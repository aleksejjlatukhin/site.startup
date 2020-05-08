<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use app\models\User;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Админка | Пользователи';
?>


    <div class="users-index">

        <h3><?= $this->title; ?></h3>



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
                    'contentOptions' => ['style' => ['padding' => '20px 10px', 'font-size' => '13px', 'font-weight' => '700']],
                ],

                /*[
                    'attribute' => 'id',
                    'header' => '<div style="text-align: center;height: 40px;line-height: 40px;">ID</div>',
                    'value' => function ($data){
                        return '<div class="nr" style="padding-top: 10px;">'. $data->id .'</div>';
                    },
                    'format' => 'raw',
                    'enableSorting' => false,
                ],*/

                /*[
                    'header' => '<div style="text-align: center;height: 40px;line-height: 40px;">Фото</div>',
                    'attribute' => 'avatar_image',
                    'label' => 'Фото',
                    'value' => function($data){
                        return Html::img('@web' . $data->avatar_image, ['alt' => 'Аватарка', 'style' => ['width' => '35px', 'height' => '35px']]);
                    },
                    'format' => 'html',
                    'options' => ['width' => '45'],
                    'enableSorting' => false,
                ],*/


                [
                    'header' => '<div style="text-align: center;height: 40px;line-height: 40px;">ФИО</div>',
                    'attribute' => 'fio',
                    'label' => 'ФИО',
                    'value' => function ($data) {
                        return '<div style="padding: 10px 0; text-align: center; font-weight: 700;">' . Html::a($data->second_name . ' ' . $data->first_name . ' ' . $data->middle_name, Url::to(['/admin/users/profile', 'id' => $data->id])) . '</div>';
                    },
                    'format' => 'html',
                    'options' => ['width' => '450'],
                    'enableSorting' => false,
                ],

                /*[
                    'header' => '<div style="text-align: center;height: 40px;line-height: 40px;">Логин</div>',
                    'attribute' => 'username',
                    'value' => function($data){
                        return '<div style="text-align: center; padding-top: 10px;">' . $data->username . '</div>';
                    },
                    'format' => 'html',
                    'enableSorting' => false,
                    'options' => ['width' => '110'],
                ],*/

                /*[
                    'header' => '<div style="text-align: center;height: 40px;line-height: 40px;">Электронная почта</div>',
                    'attribute' => 'email',
                    'label' => 'Электронная почта',
                    'value' => function($data){
                        return '<div style="text-align: center; padding-top: 10px;">' . $data->email . '</div>';
                    },
                    'format' => 'html',
                    'enableSorting' => false,
                    'options' => ['width' => '200'],
                ],*/

                /*[
                    'header' => '<div style="text-align: center;height: 40px;line-height: 40px;">Телефон</div>',
                    'attribute' => 'telephone',
                    'value' => function($data){
                        if(!empty($data->telephone)){
                            return '<div style="text-align: center; padding-top: 10px;">' . $data->telephone . '</div>';
                        }
                    },
                    'format' => 'html',
                    'options' => ['width' => '140'],
                    'enableSorting' => false,
                ],*/

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


                        return '<div style="padding-top: 10px; font-weight: 700; font-size: 13px;">' . $string . '</div>';
                    },
                    'format' => 'raw',
                    'options' => ['width' => '220'],
                    'enableSorting' => false,
                ],


                [
                    'header' => '<div style="text-align: center; height: 40px;line-height: 40px;">Последнее изменение</div>',
                    'attribute' => 'updated_at',
                    //'format' => ['date', 'dd.MM.yyyy'],
                    'value' => function($data){
                        return '<div class="date_update_'. $data->id .'" style="text-align: center; padding-top: 10px; font-size: 13px; font-weight: 700;">' . date('d.m.yy', $data->updated_at) . '</div>';
                    },
                    'format' => 'html',
                    'options' => ['width' => '220'],
                    'enableSorting' => false,
                ],

                [
                    'header' => '<div style="text-align: center; height: 40px;line-height: 40px;">Дата регистрации</div>',
                    'attribute' => 'created_at',
                    //'format' => ['date', 'dd.MM.yyyy'],
                    'value' => function($data){
                        return '<div style="text-align: center; padding-top: 10px; font-size: 13px; font-weight: 700;">' . date('d.m.yy', $data->created_at) . '</div>';
                    },
                    'format' => 'html',
                    'options' => ['width' => '220'],
                    'enableSorting' => false,
                ],

            ],
        ]); ?>

    </div>




