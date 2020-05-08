<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\User;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Админка | Пользователи';
?>


    <div class="users-index">

        <h3><?= $this->title; ?></h3>

        <div class="">
            <?= Html::a('Проектанты',Url::to(['/admin/users/index']), ['class' => 'btn btn-success'])?>
            <?= Html::a('Администраторы',Url::to(['/admin/users/admins']), ['class' => 'btn btn-default'])?>
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
                    'options' => ['width' => '350'],
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
                    'header' => '<div style="text-align: center;height: 40px;line-height: 40px;">Администратор</div>',
                    'attribute' => 'id_admin',
                    'value' => function ($data){
                        if ($data->id_admin === null) {

                            return '<div style="margin: 5px;padding: 0; text-align: center;">

                                        '. Html::submitButton("<div id='adm_modal_".$data->id."' style='font-weight: 700;'>Не установлен</div>", [
                                    "class" => "btn btn-sm btn-default",
                                    "id" => "sub_adm_modal_$data->id",
                                    "data-toggle" => "modal",
                                    "data-target" => "#admin_modal_" . $data->id,
                                    "style" => ["width" => "170px"],
                                ]) .'
                                        
                                    </div>';

                        }else {

                            $admin = User::findOne([
                                'id' => $data->id_admin,
                            ]);

                            return '<div style="margin: 5px;padding: 0; text-align: center;">

                                        '. Html::submitButton('<div id="adm_modal_'.$data->id.'" style="font-weight: 700;">' . $admin->second_name . ' ' . mb_substr($admin->first_name, 0,1) . '.' . mb_substr($admin->middle_name, 0,1) . '.</div>' , [
                                    "class" => "btn btn-sm btn-success",
                                    "id" => "sub_adm_modal_$data->id",
                                    "data-toggle" => "modal",
                                    "data-target" => "#admin_modal_" . $data->id,
                                    "style" => ["width" => "170px"],
                                ]) .'
                                        
                                    </div>';
                        }
                    },
                    'format' => 'raw',
                    'options' => ['width' => '190'],
                    'enableSorting' => false,
                ],


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

                            if ($data->id_admin !== null) {

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
                            }else {

                                $string = '<div class="use-status">Не активирован</div>';

                                return '<div class="navbar-form navbar-inner" data-toggle="modal" data-target="#not-admin-for-user_' . $data->id . '" id="not-admin-user_' . $data->id . '" style="margin: 5px;padding: 0;text-align: center;">
                                        <button class="btn btn-sm btn-primary" style="width: 170px; font-weight: 700;">
                                        <div class="use-status">Не активирован</div></button></div>' .

                                        '<div class="navbar-form navbar-inner" id="not-admin_' . $data->id . '" style="margin: 5px;padding: 0;text-align: center; display: none;">
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
                            }
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
                    'options' => ['width' => '190'],
                    'enableSorting' => false,
                ],


                [
                    'header' => '<div style="text-align: center; ">Последнее изменение</div>',
                    'attribute' => 'updated_at',
                    //'format' => ['date', 'dd.MM.yyyy'],
                    'value' => function($data){
                        return '<div class="date_update_'. $data->id .'" style="text-align: center; padding-top: 10px; font-size: 13px; font-weight: 700;">' . date('d.m.yy', $data->updated_at) . '</div>';
                    },
                    'format' => 'html',
                    'options' => ['width' => '110'],
                    'enableSorting' => false,
                ],

                [
                    'header' => '<div style="text-align: center;">Дата регистрации</div>',
                    'attribute' => 'created_at',
                    //'format' => ['date', 'dd.MM.yyyy'],
                    'value' => function($data){
                        return '<div style="text-align: center; padding-top: 10px; font-size: 13px; font-weight: 700;">' . date('d.m.yy', $data->created_at) . '</div>';
                    },
                    'format' => 'html',
                    'options' => ['width' => '110'],
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




<?php

foreach ($users as $user) :


$script2 = '    

    //Выбор администратора
    $("#valueSelectAdmin_'.$user->id.'").click(function () {
    
        var urlSaveAdmin = $("#saveAdmin_'.$user->id.'").attr("href");
        
        var arrUrl = urlSaveAdmin.split("admin=");
    
        if (arrUrl.length > 1) {
            urlSaveAdmin = arrUrl.shift();
            urlSaveAdmin += "admin=";
        }    
          
        var idAdmin = $(this).val();
        var urls = urlSaveAdmin + idAdmin;
        $("#saveAdmin_'.$user->id.'").attr("href", urls);

    });
    
    
    
    //Сохранение администратора
    $("body").on("click", "#saveAdmin_'.$user->id.'", function(event) {
        event.preventDefault();
        var url = $(this).attr("href");
        var data = $(this).serialize();

        $.ajax({
        
            url: url,
            method: "GET",
            data: data,
            success: function(response){
            
                var dateUpdate = ".date_update_" + response.model["id"];
                var date = new Date().toLocaleDateString();
                $(dateUpdate).html(date);
                
                var adminFio = "";
                
                if (response.admin){
                    
                     adminFio = response.admin["second_name"] + " " + response.admin["first_name"].substring(0,1)
                     + "." + response.admin["middle_name"].substring(0,1) + ".";
                     $("#sub_adm_modal_'.$user->id.'").removeClass();
                     $("#sub_adm_modal_'.$user->id.'").addClass("btn btn-sm btn-success");
                     
                     // Разблокировать активацию пользователя
                     $("#not-admin-user_'.$user->id.'").hide();
                     $("#not-admin_'.$user->id.'").show();
                     
                
                }else {
                    //adminFio = "Не установлен";
                    //$("#sub_adm_modal_'.$user->id.'").removeClass();
                    //$("#sub_adm_modal_'.$user->id.'").addClass("btn btn-sm btn-default");
                    
                    
                     adminFio = response.admin_replace["second_name"] + " " + response.admin_replace["first_name"].substring(0,1)
                     + "." + response.admin_replace["middle_name"].substring(0,1) + ".";
                     $("#sub_adm_modal_'.$user->id.'").removeClass();
                     $("#sub_adm_modal_'.$user->id.'").addClass("btn btn-sm btn-success");
                     
                     // Разблокировать активацию пользователя
                     $("#not-admin-user_'.$user->id.'").hide();
                     $("#not-admin_'.$user->id.'").show();
                   
                }
                               
                $("#adm_modal_'.$user->id.'").html(adminFio);
                
                  
            },
            error: function(){
                alert("Ошибка");
            }
        });
        
        return false;
    });
';

$this->registerJs($script2);
?>





<?php if (($user->status === User::STATUS_NOT_ACTIVE) && ($user->id_admin === null)) : ?>
<?php

    Modal::begin([
        'options' => [
            'id' => 'not-admin-for-user_' . $user->id
        ],
        'size' => 'modal-sm',
        'header' => '<h4 style="text-align: center; margin-bottom: -5px;">Сначала необходимо назначить администратора</h4>',
        /*'toggleButton' => [
        'label' => 'Модальное окно',
        ],*/
        //'footer' => '<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>',
    ]);
    Modal::end();

?>
<?php endif; ?>






<?php if ($user->status === User::STATUS_DELETED) : ?>


<?php

    Modal::begin([
    'options' => [
    'id' => 'admin_modal_' . $user->id
    ],
    'size' => 'modal-sm',
    'header' => '<h3 style="text-align: center; margin-bottom: -5px; color: red;">Запрещено!</h3>',
    /*'toggleButton' => [
    'label' => 'Модальное окно',
    ],*/
    //'footer' => '<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>',
    ]);

?>



    <p style="text-align: center;">Попытка изменить администратора заблокированному пользователю.</p>

<?php else : ?>


<?php

    Modal::begin([
        'options' => [
            'id' => 'admin_modal_' . $user->id
        ],
        'size' => 'modal-md',
        'header' => '<h3 style="text-align: center; margin-bottom: -5px;">Назначение администратора</h3>',
        /*'toggleButton' => [
        'label' => 'Модальное окно',
        ],*/
        //'footer' => '<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>',
    ]);

?>

<?php $form = ActiveForm::begin(['id' => 'adminAddForm_' . $user->id]); ?>

    <?
        $items = ArrayHelper::map($admins,'id','username');

        if ($user->id_admin === null) {

            $params = [
                //'prompt' => 'Выберите администратора',
                'id' => 'valueSelectAdmin_' . $user->id,
            ];

        }else {

            $params = [
                //'prompt' => 'Выберите администратора',
                'id' => 'valueSelectAdmin_' . $user->id,
            ];
        }

    ?>

    <?= $form->field($user, 'id_admin', ['template' => '<div style="display:flex; margin: 15px 0;"><div style="margin: 0 auto; width: 320px;">{input}</div></div>'])->dropDownList($items,$params) ?>

    <div class="form-addAdmin" style="text-align: center;margin-top: 20px; margin-bottom: 10px;">

        <?php if ($user->id_admin !== null) : ?>

            <?= Html::a("Сохранить", ["/admin/users/add-admin", "id" => $user->id, "admin" => $user->id_admin], ["class" => "btn btn-success", "data-dismiss" => "modal", "id" => "saveAdmin_" . $user->id, "style" => ["width" => "320px"]]);?>

        <?php else: ?>

            <?= Html::a("Сохранить", ["/admin/users/add-admin", "id" => $user->id, "admin" => ""], ["class" => "btn btn-success", "data-dismiss" => "modal", "id" => "saveAdmin_" . $user->id, "style" => ["width" => "320px"]]);?>

        <?php endif; ?>

    </div>

<?php ActiveForm::end(); ?>

<?php endif; ?>


<?php
    Modal::end();

endforeach;
