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
                    'contentOptions' => ['style' => ['padding' => '20px 10px', 'font-size' => '13px', 'font-weight' => '700']],
                ],

                /*[
                    'attribute' => 'id',
                    'header' => '<div style="text-align: center;height: 40px;line-height: 40px;">ID</div>',
                    'value' => function ($data){
                        return '<div class="nr">'. $data->id .'</div>';
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
                        return '<div style="padding: 10px 0; text-align: center; font-weight: 700;">' . Html::a($data->second_name . ' ' . $data->first_name . ' ' . $data->middle_name, Url::to(['/admin/profile/index', 'id' => $data->id])) . '</div>';
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

                /*[
                    'header' => '<div style="text-align: center;height: 40px;line-height: 40px;">Статус</div>',
                    'attribute' => 'status',
                    'value' => function($data) use ($form){

                        if ($data->status === User::STATUS_DELETED){

                            $string = '<div class="use-status">Заблокирован</div>';

                            return '<div class="navbar-form navbar-inner" style="margin: 5px;padding: 0;">
                                    <button style="width: 110px;"
                                            id="btn_'. $data->id .'" 
                                            class="btn btn-sm btn-danger"
                                            data-container="body"
                                            data-toggle="popover"
                                            data-trigger="focus"
                                            data-placement="bottom"
                                            data-title="Изменить статус"
                                            data-content="
                                                    <p><a class=\'update\' href=' . Url::to(['/admin/users/status-update', 'id' => $data->id, 'status' => 'active']) . ' >Активировать</a></p>
                                                ">
                                        <span id="string_'. $data->id .'" >'. $string .'</span>
                                    </button>
                                </div>';

                        }elseif ($data->status === User::STATUS_NOT_ACTIVE){

                            $string = '<div class="use-status">Не активирован</div>';

                            return '<div class="navbar-form navbar-inner" style="margin: 5px;padding: 0;">
                                    <button style="width: 110px;"
                                            id="btn_'. $data->id .'"
                                            class="btn btn-sm btn-primary"
                                            data-container="body"
                                            data-toggle="popover"
                                            data-trigger="focus"
                                            data-placement="bottom"
                                            data-title="Изменить статус"
                                            data-content="
                                                    <p><a class=\'update\' href=' . Url::to(['/admin/users/status-update', 'id' => $data->id, 'status' => 'active']) . ' >Активировать</a></p>
                                                    <p><a class=\'update\' href=' . Url::to(['/admin/users/status-update', 'id' => $data->id, 'status' => 'delete']) . ' >Заблокировать</a></p>
                                                ">
                                        <span id="string_'. $data->id .'" >'. $string .'</span>
                                    </button>
                                </div>';

                        }elseif ($data->status === User::STATUS_ACTIVE){

                            $string = '<div class="use-status">Активирован</div>';

                            return '<div class="navbar-form navbar-inner" style="margin: 5px;padding: 0;">
                                    <button style="width: 110px;"
                                            id="btn_'. $data->id .'"
                                            class="btn btn-sm btn-success"
                                            data-container="body"
                                            data-toggle="popover"
                                            data-trigger="focus"
                                            data-placement="bottom"
                                            data-title="Изменить статус"
                                            data-content="
                                                    <p><a class=\'update\' href=' . Url::to(['/admin/users/status-update', 'id' => $data->id, 'status' => 'delete']) . '>Заблокировать</a></p>
                                                ">
                                        <span id="string_'. $data->id .'" >'. $string .'</span>
                                    </button>
                                </div>';
                        }
                    },
                    'format' => 'raw',
                    'options' => ['width' => '130'],
                    'enableSorting' => false,
                ],*/


                [
                    'header' => '<div style="text-align: center;height: 40px;line-height: 40px;">Статус</div>',
                    'attribute' => 'status',
                    'value' => function($data) use ($form) {

                        if ($data->status === User::STATUS_DELETED) {

                            $string = '<div class="use-status">Заблокирован</div>';

                            return '<div class="navbar-form navbar-inner" style="margin: 5px;padding: 0;text-align: center;">
                                        <button style="width: 170px; font-weight: 700;"
                                                id="btn_' . $data->id . '" 
                                                class="btn btn-sm btn-danger"
                                                data-container="body"
                                                data-toggle="popover"
                                                data-trigger="focus"
                                                data-placement="bottom"
                                                data-title="Изменить статус"
                                                data-content="
                                                        <p><a class=\'update\' href=' . Url::to(['/admin/users/status-update', 'id' => $data->id, 'status' => 'active']) . ' >Активировать</a></p>
                                                    ">
                                            <span id="string_' . $data->id . '" >' . $string . '</span>
                                        </button>
                                    </div>';

                        } elseif ($data->status === User::STATUS_NOT_ACTIVE) {

                            $string = '<div class="use-status">Не активирован</div>';

                            return '<div class="navbar-form navbar-inner" style="margin: 5px;padding: 0;text-align: center;">
                                        <button style="width: 170px; font-weight: 700;"
                                                id="btn_' . $data->id . '"
                                                class="btn btn-sm btn-primary"
                                                data-container="body"
                                                data-toggle="popover"
                                                data-trigger="focus"
                                                data-placement="bottom"
                                                data-title="Изменить статус"
                                                data-content="
                                                        <p><a class=\'update\' href=' . Url::to(['/admin/users/status-update', 'id' => $data->id, 'status' => 'active']) . ' >Активировать</a></p>
                                                        <p><a class=\'update\' href=' . Url::to(['/admin/users/status-update', 'id' => $data->id, 'status' => 'delete']) . ' >Заблокировать</a></p>
                                                    ">
                                            <span id="string_' . $data->id . '" >' . $string . '</span>
                                        </button>
                                    </div>';

                        } elseif ($data->status === User::STATUS_ACTIVE) {

                            $string = '<div class="use-status">Активирован</div>';

                            return '<div class="navbar-form navbar-inner" style="margin: 5px;padding: 0;text-align: center;">
                                        <button style="width: 170px; font-weight: 700;"
                                                id="btn_' . $data->id . '"
                                                class="btn btn-sm btn-success"
                                                data-container="body"
                                                data-toggle="popover"
                                                data-trigger="focus"
                                                data-placement="bottom"
                                                data-title="Изменить статус"
                                                data-content="
                                                        <p><a class=\'update\' href=' . Url::to(['/admin/users/status-update', 'id' => $data->id, 'status' => 'delete']) . '>Заблокировать</a></p>
                                                    ">
                                            <span id="string_' . $data->id . '" >' . $string . '</span>
                                        </button>
                                    </div>';
                        }

                    },
                    'format' => 'raw',
                    'options' => ['width' => '220'],
                    'enableSorting' => false,
                ],

                /*[
                    'header' => '<div style="text-align: center">Последнее изменение</div>',
                    'attribute' => 'updated_at',
                    //'format' => ['date', 'dd.MM.yyyy'],
                    'value' => function($data){
                        return '<div class="date_update_'. $data->id .'" style="text-align: center; padding-top: 10px;">' . date('d.m.yy', $data->updated_at) . '</div>';
                    },
                    'format' => 'html',
                    'options' => ['width' => '120'],
                    'enableSorting' => false,
                ],*/

                /*[
                    'header' => '<div style="text-align: center">Дата регистрации</div>',
                    'attribute' => 'created_at',
                    //'format' => ['date', 'dd.MM.yyyy'],
                    'value' => function($data){
                        return '<div style="text-align: center; padding-top: 10px;">' . date('d.m.yy', $data->created_at) . '</div>';
                    },
                    'format' => 'html',
                    'options' => ['width' => '120'],
                    'enableSorting' => false,
                ],*/

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



<?php

$script = '
    //$(".use-status").click(function () {
        //var id = $(this).closest("tr").find(".nr").text();
        //alert(id);
    //});
    
    //Всплывающие  блоки
    $(\'[data-toggle="popover"]\').popover({html:true});
    
    $("body").on("click", "a.update", function(event) {
        event.preventDefault();
        var url = $(this).attr("href");
        var data = $(this).serialize();

        $.ajax({
        
            url: url,
            method: "GET",
            data: data,
            success: function(response){
                
                var dateUpdate = ".date_update_" + response.id;
                var date = new Date().toLocaleDateString();
                $(dateUpdate).html(date);
                
                
                //alert(response.status);
                var btnID = "#btn_" + response.id;
                var str = "#string_" + response.id;
                
                if (response.status === 10){
                
                    var str_href = "";
                    str_href += "<\p><\a class=\"update\" href=\"/admin/users/status-update?id=";
                    str_href += response.id;
                    str_href += "&status=delete\">Заблокировать<\/a><\p>";
                
                    $(btnID).attr("data-content", str_href);
                    $(btnID).attr("class", "btn btn-sm btn-success")
                    $(str).html("<\div class=\"use-status\" >Активирован<\/div>");
                    
                }else {
                
                    var str_href = "";
                    str_href += "<\p><\a class=\"update\" href=\"/admin/users/status-update?id=";
                    str_href += response.id;
                    str_href += "&status=active\">Активировать<\/a><\p>";
                    
                    $(btnID).attr("data-content", str_href);
                    $(btnID).attr("class", "btn btn-sm btn-danger");
                    $(str).html("<\div class=\"use-status\" >Заблокирован<\/div>");
                }
                //alert(response.status);
                
            },
            error: function(){
                alert("Ошибка");
            }
        });
        
        return false;
    });
';

$this->registerJs($script);
?>

<!--https://jsfiddle.net/FnDvL/-->

