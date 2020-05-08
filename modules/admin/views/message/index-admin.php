<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\User;



$this->title = 'Админка | Сообщения | История';
?>


    <br>
    <div class="row">

        <div class="col-md-9" style="border-right: 1px solid #ccc;">

            <div class="search_box" style="border-bottom: 1px solid #ccc; padding-bottom: 40px;">
                <form id="search_form" method="get" action="<?= Url::to(['/admin/message/index'])?>">
                    <input type="hidden" name="id" value="<?= $admin['id'];?>">
                    <input class="col-md-12" value="<?= Yii::$app->request->get('query');?>" type="text" placeholder="Поиск" name="query" id="input_search">
                </form>
            </div>

            <?php if (!empty($query)) : ?>

                <br>

                <!--Беседы в запросе после перезагрузки страницы-->
                <div class="convers_post" style="display: none;margin-top: -20px;min-height: 80vh;">

                    <?php foreach ($conversations_query as $conversation) : ?>

                        <?= Html::a('
                    <div class="conversation-link" style="padding: 10px 10px; border-bottom: 1px solid #ccc;">
                    <span style="padding-right: 15px;">'.Html::img([$conversation->user['avatar_image']],['width' => '40px', 'height' => '40px', 'class' => 'round-avatar']).'</span>
                    <span>'. $conversation->user->second_name . ' ' . $conversation->user->first_name . ' ' . $conversation->user->middle_name .'</span>
                    </div>', ['/message/view', 'id' => $conversation->id], ['class' => 'conversation-link'])
                        ?>

                    <?php endforeach; ?>

                </div>

            <?php else : ?>

                <!--Беседы, у которых есть сообщения-->
                <div class="convers_exist" style="padding: 0; min-height: 80vh;">

                    <?php if ($conversations_exist) : ?>

                        <?php foreach ($conversations_exist as $conversation) : ?>

                            <?php if ($conversation->lastMessage->sender_id == $conversation->admin->id) : ?>

                                <?= Html::a('
                            <div class="conversation-link" style="display: flex; padding: 15px 0; border-bottom: 1px solid #ccc;">
                            
                                <div style="padding: 0 15px 0 10px;">'.Html::img([$conversation->user['avatar_image']],['width' => '50px', 'height' => '50px', 'class' => 'round-avatar']).'</div>
                                <div style="padding: 0 15px 0 10px; width: 100%;">
                                
                                    <div style="padding-top: 5px; display: flex; justify-content: space-between; ">
                                    
                                        <span>'. $conversation->user->second_name . ' ' . $conversation->user->first_name . ' ' . $conversation->user->middle_name .'</span>
                                        
                                        <div style="padding-bottom: 5px; display: flex">
                                            <div class="conversation-link-time">'. date('H:i', $conversation['updated_at']) .'</div>
                                            <div class="conversation-link-data">'. date('d.m.Y', $conversation['updated_at']) .'</div>
                                        </div>
                                        
                                    </div>
                                    
                                    <span>'. Html::img([$conversation->admin['avatar_image']], ['width' => '20px', 'height' => '20px', 'class' => 'round-avatar']).'</span>
                                    <span class="conversation-link-text">'. mb_substr($conversation->lastMessage->description, 0, 90) .'...</span>
                                </div>
                                
                            </div>', ['/message/view', 'id' => $conversation->id], ['class' => 'conversation-link'])
                                ?>

                            <?php else : ?>

                                <?= Html::a('
                            <div class="conversation-link" style="display: flex; padding: 15px 0; border-bottom: 1px solid #ccc;">
                            
                                <div style="padding: 0 15px 0 10px;">'.Html::img([$conversation->user['avatar_image']],['width' => '50px', 'height' => '50px', 'class' => 'round-avatar']).'</div>
                                <div style="padding: 0 15px 0 10px; width: 100%;">
                                
                                    <div style="padding-top: 5px; display: flex; justify-content: space-between; ">
                                    
                                        <span>'. $conversation->user->second_name . ' ' . $conversation->user->first_name . ' ' . $conversation->user->middle_name .'</span>
                                        
                                        <div style="display: flex">
                                            <div class="conversation-link-time">'. date('H:i', $conversation['updated_at']) .'</div>
                                            <div class="conversation-link-data">'. date('d.m.Y', $conversation['updated_at']) .'</div>
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="conversation-link-text">'. mb_substr($conversation->lastMessage->description, 0, 90) .'...</div>
                                </div>
                                
                            </div>', ['/message/view', 'id' => $conversation->id], ['class' => 'conversation-link'])
                                ?>

                            <?php endif; ?>

                        <?php endforeach; ?>

                    <?php else : ?>

                        <div  class="not_message" style="text-align: center; font-size: 13px; padding-top: 20px;">
                            <p style="font-weight: 700;">Ваша история сообщений пока пуста...</p>
                            <p>(выберите в поиске пользователя, которому Вы хотите написать сообщение)</p>
                        </div>

                    <?php endif; ?>

                </div>


                <div class="convers_all" style="display: none;min-height: 80vh;">
                    <!--Все беседы Главного Администратора-->
                    <?php foreach ($conversations as $conversation) : ?>

                        <?= Html::a('
                    <div class="conversation-link" style="padding: 10px 10px; border-bottom: 1px solid #ccc;">
                    <span style="padding-right: 15px;">'.Html::img([$conversation->user['avatar_image']],['width' => '40px', 'height' => '40px', 'class' => 'round-avatar']).'</span>
                    <span>'. $conversation->user->second_name . ' ' . $conversation->user->first_name . ' ' . $conversation->user->middle_name .'</span>
                    </div>', ['/message/view', 'id' => $conversation->id], ['class' => 'conversation-link'])
                        ?>

                    <?php endforeach; ?>
                </div>

            <?php endif; ?>

            <!--Блок для вывода бесед, которые оказались в запросе через Ajax-->
            <div class="convers_query" style="display: none; min-height: 80vh;">

            </div>

        </div>

        <div class="col-md-3">

            <p style="text-align: center; font-weight: 700; padding-bottom: 20px;">
                Сообщения | История
            </p>

            <h4 style="text-align: center; font-weight: 700;">
                Категории:
            </h4>

            <p style="text-align: center; font-weight: 700; color: green;">
                Проектанты
            </p>

            <p style="text-align: center; font-weight: 700;">
                <?= Html::a('Главный администратор', Url::to(['/admin/message/view', 'id' => $convers_main->id])) ?>
            </p>
        </div>
    </div>



<?php

$script = '


jQuery(function($){                    //Отслеживаем событие клика по полю поиска
	$(document).mouseup(function (e){ // событие клика по веб-документу
		var div = $("#search_form"); // тут указываем ID элемента
		var div1 = $(".convers_all"); // блок со всеми беседами
		var query = $("#input_search").val(); // значение поля поиск
		if (!div.is(e.target)  // если клик был не по нашему блоку
		    && div.has(e.target).length === 0 && div1.has(e.target).length === 0) { // и не по его дочерним элементам
			//div.hide(); // скрываем его
			
			if (query.length === 0) { // если поле поиск пусто
			
			    $(".convers_exist").show(); //Показываем беседы с существующими сообщениями
		        $(".convers_all").hide();  //Скрываем блок со всеми беседами
		    }
		    
		}else { //если клик был по полю поиск
		
		    $(".convers_exist").hide(); //Скрываем беседы с существующими сообщениями
            
            if (query.length === 0) { // если поле поиск пусто
            
                $(".convers_all").show(); //Показываем все беседы
            
            }
		}
	});
});



$("#input_search").on("input", function() {   //Отслеживаем запросы в поле поиска

        //Запрет на отправку формы
        this.addEventListener("keydown", function(event) {
            if(event.keyCode == 13) {
               event.preventDefault();
            }
        }); 
        
        var data = $(this).serialize();
        var url = $(this).attr("action");
        var query = $("#input_search").val();
        
        query = $.trim(query);
        
        if (query.length > 0) { // Если поле поиск не пусто
            
            $(".convers_all").hide();    //Скрываем блок со всеми беседами
            $(".convers_query").show(); // Показываем блок с беседами по запросу
            
            $.ajax({
            
                url: url,
                method: "GET",
                data: data,
                cache: true,
                success: function(response){ 
                    
                    window.history.pushState("Details", "Title", "/admin/message/index?id='.$admin["id"].'&query=" + query); // формируем URL согласно вводным данным
                    
                    $(".convers_query").empty(); // Очищаем блок от прошлого запроса
                    
                    for (var i=0; i<response["convers"].length; i++) {   // Выводим беседы по запросу
                        
                        //alert(response.users[i]["second_name"]);
                        //$(".convers_query").append("<\div class=\"item_\" style=\"padding-top: 20px;\">" + response.users[i]["second_name"] + " " + response.users[i]["first_name"] + " " + response.users[i]["middle_name"] + "<\/div>");
                        
                        $(".convers_query").append("<\a class=\"conversation-link\" href=\"/message/view?id=" + response.convers[i]["id"] + "\"><\div class=\"conversation-link\" style=\"padding: 10px 10px; border-bottom: 1px solid #ccc;\"><\span style=\"padding-right: 15px;\"><\img style=\"width: 40px; height: 40px;\", class=\"round-avatar\" src=\"" + response.users[i]["avatar_image"] + "\" ><\/span><\span>" + response.users[i]["second_name"] + " " + response.users[i]["first_name"] + " " + response.users[i]["middle_name"] + "<\/span><\/div><\/a>");
                        
                    }
                    
                    $(".convers_post").hide(); // Скрываем беседы по запросу передаваемые через параметр

                },
                error: function(){
                    alert("Ошибка");
                }
            });
            return false;
            
        }else {  // Если поле поиск пусто  
            
            window.history.pushState("Details", "Title", "/admin/message/index?id='.$admin["id"].'");
            $(".convers_all").show();
            $(".convers_query").hide();
        }
});


// Показываем беседы по запросу, которые выводим через параметр и скрываем их в Ajax-запросе, который находится выше
var querySearch = $("#input_search").val();
if (querySearch.length > 0) {
    if ($(".convers_query").html().trim() === "") {
        $(".convers_post").show();
    }
}


// Отслеживаем клик по кнопкам браузера (вперед, назад)
addEventListener("popstate",function(e){
   //alert("go Back!");
   //alert(window.location.search);
   var search = window.location.search;
   searchArr = search.split("query=");
   
   if (searchArr[1]) { // Если в URL есть запрос из поля поиска
      $("#input_search").val(decodeURI(searchArr[1])); // Декадируем строку и передаем значение в поле input
      $(".convers_exist").hide();  // Скрываем беседы с существующими сообщениями
      $(".convers_all").hide();    // Скрываем блок со всеми беседами
      $(".convers_query").show();  // Показываем беседы по запросу
        
   }else {  // Если в URL нет запроса из поля поиска
      $("#input_search").val(null); // Устанавливаем пустое значение для поля поиска
      $("#input_search").blur();    // Убираем курсор из поля поиска
      $(".convers_exist").show();   // Скрываем беседы с существующими сообщениями
      $(".convers_query").hide();   // Скрываем беседы по запросу
      $(".convers_all").hide();     // Скрываем блок со всеми беседами
      $(".convers_post").hide();    // Скрываем беседы по запросу передаваемые через параметр
   }
   
},false);






';

$this->registerJs($script);




$script2 = "

     //Автоматическое обновление страницы
     function reloadcontent() {
         $.ajax ({
             url: '". Url::to(['update-conversations', 'id' => \Yii::$app->request->get('id')])."',
             cache: false,
             success: function(response) {
        
                 if (response.convers.length > 0) {
                
                     $('.convers_exist').html('');
                     $('.not-message').empty();
                    
                    
                     for (var i = 0; i < response.convers.length; i++) {

                         if (response.last[i]['sender_id'] == response.main['id']) {
                        
                             $('.convers_exist').append('<\a class=\"conversation-link\" href=\"/message/view?id=' + response.convers[i]['id'] + '\"><\div class=\"conversation-link\" style=\"display: flex; padding: 15px 0; border-bottom: 1px solid #ccc;\"><\div style=\"padding: 0 15px 0 10px;\"><\img src=\"' + response.users[i]['avatar_image'] + '\" style=\"width: 50px; height: 50px;\" class =\"round-avatar\"><\/div><\div style=\"padding: 0 15px 0 10px; width: 100%;\"> <\div style=\"padding-top: 5px; display: flex; justify-content: space-between;\"> <\span>' + response.users[i]['second_name'] + ' ' + response.users[i]['first_name'] + ' ' + response.users[i]['middle_name'] + '<\/span><\div><\div style=\"padding-bottom: 5px; display: flex;\"><\div class=\"conversation-link-time\">' + response.times[i] + '<\/div><\div class=\"conversation-link-data\">' + response.dates[i] + '<\/div><\/div><\/div><\/div><\span><\img src=\"' + response.main['avatar_image'] + '\" style=\"width: 20px; height: 20px;\" class=\"round-avatar\"><\/span><\span style=\"padding-left: 4px;\" class=\"conversation-link-text\">' + response.last[i]['description'].substr(0, 90) + '...' + '<\/span>  <\/div>   <\/div><\/a>');
                         
                         }else {
                         
                            $('.convers_exist').append('<\a class=\"conversation-link\" href=\"/message/view?id=' + response.convers[i]['id'] + '\"><\div class=\"conversation-link\" style=\"display: flex; padding: 15px 0; border-bottom: 1px solid #ccc;\"><\div style=\"padding: 0 15px 0 10px;\"><\img src=\"' + response.users[i]['avatar_image'] + '\" style=\"width: 50px; height: 50px;\" class =\"round-avatar\"><\/div><\div style=\"padding: 0 15px 0 10px; width: 100%;\"> <\div style=\"padding-top: 5px; display: flex; justify-content: space-between;\"> <\span>' + response.users[i]['second_name'] + ' ' + response.users[i]['first_name'] + ' ' + response.users[i]['middle_name'] + '<\/span><\div><\div style=\"display: flex;\"><\div class=\"conversation-link-time\">' + response.times[i] + '<\/div><\div class=\"conversation-link-data\">' + response.dates[i] + '<\/div><\/div><\/div><\/div><\div class=\"conversation-link-text\">' + response.last[i]['description'].substr(0, 90) + '...' + '<\/div>  <\/div>   <\/div><\/a>');
                         }
                     }
                 } 
             }
         });
     }
     
     
     function timeUpdate(){  //Установка таймера на обновление страницы
        
        reloadcontent();
     }
     setInterval (timeUpdate,10000);


";

$this->registerJs($script2);