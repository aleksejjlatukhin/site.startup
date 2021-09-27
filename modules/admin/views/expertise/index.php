<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Экспертизы';

?>

<div class="row">

    <div class="col-sm-6 col-md-8" style="margin-top: 35px; padding-left: 40px;">

        <?= Html::a('Экспертизы (сортировка по экспертам)' . Html::img('/images/icons/icon_report_next.png'), ['#'],[
            'class' => 'link_to_instruction_page open_modal_instruction_page',
            'title' => 'Инструкция', 'onclick' => 'return false'
        ]); ?>

    </div>

    <div class="col-sm-6 col-md-4">
        <?= Html::a( 'Назначение экспертов на проекты',
            Url::to(['/admin/expertise/tasks']),[
            'class' => 'btn btn-success pull-right',
            'style' => [
                'display' => 'flex',
                'align-items' => 'center',
                'justify-content' => 'center',
                'background' => '#52BE7F',
                'width' => '100%',
                'max-width' => '450px',
                'min-width' => '350px',
                'height' => '40px',
                'font-size' => '24px',
                'border-radius' => '8px',
                'margin-top' => '35px',
            ],
        ]);?>
    </div>
</div>
