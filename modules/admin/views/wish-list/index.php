<?php

use app\models\WishList;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Списки запросов B2B компаний';
$this->registerCssFile('@web/css/wish-list-style.css');

/**
 * @var WishList[] $models
 */

?>

<div class="container-fluid">
    <div class="row hi-line-page">
        <div class="col-md-5" style="margin-top: 35px; padding-left: 25px;">
            <?= Html::a($this->title . Html::img('/images/icons/icon_report_next.png'), ['#'],[
                'class' => 'link_to_instruction_page open_modal_instruction_page',
                'title' => 'Инструкция', 'onclick' => 'return false'
            ]) ?>
        </div>
        <div class="col-md-2 pull-right">
            <?= Html::a( 'Локации', Url::to(['/admin/location/index']),[
                'class' => 'btn btn-success',
                'style' => [
                    'display' => 'flex',
                    'align-items' => 'center',
                    'justify-content' => 'center',
                    'background' => '#52BE7F',
                    'width' => '100%',
                    'min-width' => '200px',
                    'height' => '40px',
                    'font-size' => '24px',
                    'border-radius' => '8px',
                    'margin-top' => '35px',
                ],
            ]) ?>
        </div>
        <div class="col-md-2 pull-right">
            <?=  Html::a( 'Новые списки', ['/admin/wish-list/new'], [
                    'class' => 'btn btn-success',
                    'style' => [
                        'display' => 'flex',
                        'align-items' => 'center',
                        'color' => '#FFFFFF',
                        'justify-content' => 'center',
                        'background' => '#52BE7F',
                        'width' => '180px',
                        'height' => '40px',
                        'font-size' => '24px',
                        'border-radius' => '8px',
                        'margin-top' => '35px',
                    ]
                ]
            ) ?>
        </div>
        <div class="col-md-3 " style="margin-top: 30px;">
            <?=  Html::a( '<div class="new_client_request_link_block"><div>' . Html::img(['@web/images/icons/add_vector.png'], ['style' => ['width' => '35px']]) . '</div><div style="padding-left: 20px;">Новый список</div></div>',
                ['/admin/wish-list/create'], ['class' => 'new_client_request_link_plus pull-right']
            ) ?>
        </div>
    </div>
</div>

<div class="container-fluid">

    <div class="row headers_wish_lists_new">

        <div class="col-md-2">
            Размер предприятия по количеству персонала
        </div>

        <div class="col-md-2">
            Локация предприятия (город)
        </div>

        <div class="col-md-2">
            Тип предприятия
        </div>

        <div class="col-md-2">
            Тип производства
        </div>

        <div class="col-md-2">
            Сформирован
        </div>

        <div class="col-md-2">
            Организация
        </div>

    </div>

    <div class="block_all_wish_lists_new">

        <?= $this->render('index_ajax', ['models' => $models]) ?>

    </div>

</div>

<!--TODO: Добавить форму создания запроса и список всех доступных запросов.-->
<!--TODO: А далее во вкладке каждой организации admin/clients/view?id=2 добавить возможность включать настройки:-->
<!--TODO: 1. Организация разрешает видеть всем свой вишлист; 2. Spaccel разрешает организации видеть все общие вишлисты (Spaccel и всех кто выполнил 1 пункт)-->
<!--TODO: Далее Вывести страницу с вишлистом в админке организаций и возможность им создавать новые запросы-->

<!--Подключение скриптов-->
<?php $this->registerJsFile('@web/js/wish_list_index.js'); ?>