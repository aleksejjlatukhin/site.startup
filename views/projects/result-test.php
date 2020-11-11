<?php

use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

//use PhpOffice\PhpSpreadsheet\Spreadsheet;
//use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
//use kartik\export\ExportMenu;
?>

<?php

$this->title = 'Сводная таблица проекта "' . mb_strtolower($project->project_name) . '"';

?>

<div class="table-project-kartik">

    <?

    $gridColumns = [

        //['class' => 'kartik\grid\SerialColumn'],

        [
            'attribute' => 'segment',
            'label' => 'Сегмент',
            'header' => '<div class="font-header-table" style="font-size: 12px; font-weight: 500;">Наименование сегмента</div>',
            'groupOddCssClass' => 'kv',
            'groupEvenCssClass' => 'kv',
            'options' => ['colspan' => 1],
            //'header' => false,
            //'width' => '350px',
            'value' => function ($model, $key, $index, $widget) {

                $str = "";
                $substrings = mb_str_split($model->segment->name, 18);
                foreach ($substrings as $s => $substring) {
                    if ($s !== count($substrings)-1 ) {

                        $str .= $substring . " - <br> ";

                    }else {

                        $str .= $substring;
                    }

                }

                return '<div style="padding: 0 5px;">' . Html::a($str, Url::to(['/segment/view', 'id' => $model->segment->id]), ['class' => 'table-kartik-link', 'target'=>'_blank',]) . '</div>';
            },
            'format' => 'raw',
            'hiddenFromExport' => true, // Убрать столбец при скачивании
            'group' => true,  // enable grouping
            //'groupedRow' => true, // Группировка по строке
        ],

        [
            'attribute' => 'segment_export',
            'label' => 'Сегмент',
            'header' => '<div class="font-header-table" style="font-size: 12px; font-weight: 500;">Наименование сегмента</div>',
            'groupOddCssClass' => 'kv',
            'groupEvenCssClass' => 'kv',
            'width' => '230px',
            'options' => ['colspan' => 1],
            'value' => function ($model, $key, $index, $widget) {

                return '<div class="table-kartik-link">' . $model->segment->name . '</div>';
            },
            'format' => 'html',
            'hidden' => true, //Скрыть столбец со станицы, при этом при скачивании он будет виден
            'group' => true,  // enable grouping
            //'groupedRow' => true, // Группировка по строке
        ],

        [
            'attribute' => 'date_segment',
            'label' => 'Дата',
            'header' => '<div class="font-header-table" style="font-size: 12px; font-weight: 500;">Дата</div>',
            'groupOddCssClass' => 'kv',
            'groupEvenCssClass' => 'kv',
            //'width' => '120px',
            'options' => ['colspan' => 1],
            'value' => function ($model) {

                if ($model->segment->created_at) {

                    return '<div class="text-center" style="color: #8c8c8c;">'. date('d.m.y', $model->segment->created_at) .'</div>';
                }else {
                    return '<div class="text-center" style="color: #8c8c8c;">__.__.__</div>';
                }


            },
            'format' => 'html',
            'hiddenFromExport' => true, // Убрать столбец при скачивании
            'group' => true,  // enable grouping
            'subGroupOf' => 0 // supplier column index is the parent group
        ],


        [
            'attribute' => 'date_segment-export',
            'label' => 'Дата',
            'header' => '<div class="font-header-table" style="font-size: 12px; font-weight: 500;">Дата</div>',
            'groupOddCssClass' => 'kv',
            'groupEvenCssClass' => 'kv',
            'width' => '80px',
            'options' => ['colspan' => 1],
            'value' => function ($model) {

                if ($model->segment->created_at) {

                    return '<div class="text-center" style="color: #8c8c8c;">'. date('d.m.y', $model->segment->created_at) .'</div>';
                }else {
                    return '<div class="text-center" style="color: #8c8c8c;">__.__.__</div>';
                }


            },
            'format' => 'html',
            'hidden' => true, //Скрыть столбец со станицы, при этом при скачивании он будет виден
            'group' => true,  // enable grouping
            'subGroupOf' => 0 // supplier column index is the parent group
        ],


        [
            'attribute' => 'gps',
            'label' => 'Гипотеза',
            'header' => '<div class="font-header-table" style="font-size: 12px; font-weight: 500;">Гипотеза</div>',
            'groupOddCssClass' => 'kv',
            'groupEvenCssClass' => 'kv',
            //'width' => '180px',
            'options' => ['colspan' => 1],
            'value' => function ($model) {
                if (empty($model->segment->interview)){

                    return Html::a( Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px', 'margin-left' => '6px']]),
                        ['/interview/create', 'id' => $model->segment->id], ['target'=>'_blank',]);

                } elseif (empty($model->problem) && !empty($model->segment->interview)) {

                    return Html::a( Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px', 'margin-left' => '6px']]),
                        ['/generation-problem/create', 'id' => $model->segment->interview->id], ['target'=>'_blank',]);

                } elseif ($model->problem->title) {

                    return '<div class="text-center">' . Html::a($model->problem->title, Url::to(['/generation-problem/view', 'id' => $model->problem->id]), [
                            'class' => 'table-kartik-link',
                            'target'=>'_blank',
                            'data-toggle'=>'tooltip',
                            'title'=> $model->problem->description,
                        ]) . '</div>';

                } else {

                    return '';
                }
            },
            'format' => 'raw',
            'hiddenFromExport' => true, // Убрать столбец при скачивании
            //'group' => true,  // enable grouping
            //'subGroupOf' => 0, // supplier column index is the parent group
        ],

        [
            'attribute' => 'gps_export',
            'label' => 'Гипотеза',
            'header' => '<div class="font-header-table" style="font-size: 12px; font-weight: 500;">Гипотеза</div>',
            'groupOddCssClass' => 'kv',
            'groupEvenCssClass' => 'kv',
            'width' => '100px',
            'options' => ['colspan' => 1],
            'value' => function ($model) {
                if (empty($model->problem)) {

                    return '<div><span class="skip-export-pdf"> >> </span><span class="skip-export-xls skip-export-html">' . Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]) . '</span></div>';

                } elseif ($model->problem->title) {

                    return '<div class="text-center"><span>' . $model->problem->title . '</span></div>';

                } else {

                    return '';
                }
            },
            'format' => 'html',
            'hidden' => true, //Скрыть столбец со станицы, при этом при скачивании он будет виден
            //'group' => true,  // enable grouping
            //'subGroupOf' => 0, // supplier column index is the parent group
        ],

        [
            'attribute' => 'date_gps',
            'label' => 'Дата',
            'header' => '<div class="font-header-table" style="font-size: 12px; font-weight: 500;">Дата</div>',
            'groupOddCssClass' => 'kv',
            'groupEvenCssClass' => 'kv',
            //'width' => '120px',
            'options' => ['colspan' => 1],
            'value' => function ($model) {

                if ($model->problem && ($model->problem->created_at !== null)) {

                    return '<div class="text-center" style="color: #8c8c8c;">'. date('d.m.y', $model->problem->created_at) .'</div>';
                }
            },
            'format' => 'html',
            //'group' => true,  // enable grouping
            //'subGroupOf' => 2 // supplier column index is the parent group
        ],

        [
            'attribute' => 'status_gps',
            'label' => 'Подтверждение',
            'header' => '<div class="font-header-table" style="font-size: 12px;font-weight: 500;">Подтверждение</div>',
            'groupOddCssClass' => 'kv',
            'groupEvenCssClass' => 'kv',
            //'width' => '250px',
            'options' => ['colspan' => 1],
            'value' => function ($model) {
                if (($model->problem->exist_confirm === 1) && ($model->problem->time_confirm !== null)) {

                    //Если подтверждение ГЦП положительное выводим следующее
                    return '<div class="text-center"><span style="margin-right: 10px;">'. Html::a(
                            Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px',]]),
                            ['/confirm-problem/view', 'id' => $model->problem->confirm->id], ['target'=>'_blank'])
                        .'</span><span class="">'. date('d.m.y', $model->problem->time_confirm) .'</span></div>';

                }elseif ($model->problem->exist_confirm === 0) {

                    //Если подтверждение ГЦП отрицательное выводим следующее
                    return '<div class="text-center"> <span style="margin-right: 10px;">'. Html::a(
                            Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px',]]),
                            ['/confirm-problem/view', 'id' => $model->problem->confirm->id], ['target'=>'_blank'])
                        .'</span><span class="" >'. date('d.m.y', $model->problem->time_confirm) .'</span></div>';

                }elseif ($model->problem && $model->problem->exist_confirm === null) {

                    return Html::a( Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px', 'margin-left' => '6px']]),
                        ['/confirm-problem/create', 'id' => $model->problem->id], ['target'=>'_blank']);
                }
            },
            'format' => 'raw',
            'hiddenFromExport' => true, // Убрать столбец при скачивании
            //'group' => true,  // enable grouping
            //'subGroupOf' => 2, // supplier column index is the parent group
        ],

        [
            'attribute' => 'status_gps_export',
            'label' => 'Подтверждение',
            'header' => '<div class="font-header-table" style="font-size: 12px;font-weight: 500;">Подтверждение</div>',
            'groupOddCssClass' => 'kv',
            'groupEvenCssClass' => 'kv',
            'width' => '130px',
            'options' => ['colspan' => 1],
            'value' => function ($model) {
                if (($model->problem->exist_confirm === 1) && ($model->problem->time_confirm !== null)) {

                    //Если подтверждение ГЦП положительное выводим следующее
                    return '<div class="text-center"> <span style="margin-right: 10px;" class="skip-export-pdf">+</span><span class="skip-export-xls skip-export-html">'. Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px', 'margin-right' => '10px']]) .'</span><span class="" >'. date('d.m.y', $model->problem->time_confirm) .'</span></div>';

                }elseif ($model->problem->exist_confirm === 0) {

                    //Если подтверждение ГЦП отрицательное выводим следующее
                    return '<div class="text-center"> <span style="margin-right: 10px;" class="skip-export-pdf">-</span><span class="skip-export-xls skip-export-html">'. Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px', 'margin-right' => '10px']]) .'</span><span class="" >'. date('d.m.y', $model->problem->time_confirm) .'</span></div>';

                }elseif ($model->problem && $model->problem->exist_confirm === null) {

                    return '<div> <span class="skip-export-pdf"> >> </span><span class="skip-export-xls skip-export-html">'. Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]) .'</span></div>';
                }
            },
            'format' => 'html',
            'hidden' => true, //Скрыть столбец со станицы, при этом при скачивании он будет виден
            //'group' => true,  // enable grouping
            //'subGroupOf' => 2, // supplier column index is the parent group
        ],

        /*[
            'attribute' => 'date_gps_confirm',
            'label' => 'Дата',
            //'width' => '250px',
            'value' => function ($model) {
                if ($model->problem->exist_confirm !== null) {
                    return '<span class="" style="">'. date('d.m.yy', $model->problem->time_confirm) .'</span>';
                }
            },
            'format' => 'html',
            'group' => true,  // enable grouping
            'subGroupOf' => 2 // supplier column index is the parent group
        ],*/

        [
            'attribute' => 'gcp',
            'label' => 'Гипотеза',
            'header' => '<div class="font-header-table" style="font-size: 12px;font-weight: 500;">Гипотеза</div>',
            'groupOddCssClass' => 'kv',
            'groupEvenCssClass' => 'kv',
            //'width' => '180px',
            'options' => ['colspan' => 1],
            'value' => function ($model) {
                if (empty($model->problem->gcps) && $model->problem->exist_confirm === 1) {

                    return Html::a( Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px', 'margin-left' => '6px']]),
                        ['/gcp/create', 'id' => $model->problem->confirm->id], ['target'=>'_blank']);

                } elseif ($model->gcp->title) {

                    return '<div class="text-center">' . Html::a($model->gcp->title, Url::to(['/gcp/view', 'id' => $model->gcp->id]), [
                            'class' => 'table-kartik-link',
                            'target'=>'_blank',
                            'data-toggle'=>'tooltip',
                            'title'=> $model->gcp->description,
                        ]) . '</div>';

                } else {

                    return '';
                }
            },
            'format' => 'raw',
            'hiddenFromExport' => true,
            //'group' => true,
            //'subGroupOf' => 5,
        ],

        [
            'attribute' => 'gcp_export',
            'label' => 'Гипотеза',
            'header' => '<div class="font-header-table" style="font-size: 12px;font-weight: 500;">Гипотеза</div>',
            'groupOddCssClass' => 'kv',
            'groupEvenCssClass' => 'kv',
            'width' => '100px',
            'options' => ['colspan' => 1],
            'value' => function ($model) {
                if (empty($model->problem->gcps) && $model->problem->exist_confirm === 1) {

                    return '<span class="skip-export-pdf"> >> </span><span class="skip-export-xls skip-export-html">' . Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]) . '</span>';

                } elseif ($model->gcp->title) {

                    return '<div class="text-center">' . $model->gcp->title . '</div>';

                } else {

                    return '';
                }
            },
            'format' => 'html',
            'hidden' => true,
            //'group' => true,
            //'subGroupOf' => 5,
        ],

        [
            'attribute' => 'date_gcp',
            'label' => 'Дата',
            'header' => '<div class="font-header-table" style="font-size: 12px;font-weight: 500;">Дата</div>',
            'groupOddCssClass' => 'kv',
            'groupEvenCssClass' => 'kv',
            //'width' => '120px',
            'options' => ['colspan' => 1],
            'value' => function ($model) {

                if ($model->gcp && ($model->gcp->created_at !== null)) {

                    return '<div class="text-center" style="color: #8c8c8c;">'. date('d.m.y', $model->gcp->created_at) .'</div>';
                }
            },
            'format' => 'html',
            //'group' => true,  // enable grouping
            //'subGroupOf' => 7, // supplier column index is the parent group
        ],

        [
            'attribute' => 'status_gcp',
            'label' => 'Подтверждение',
            'header' => '<div class="font-header-table" style="font-size: 12px;font-weight: 500;">Подтверждение</div>',
            'groupOddCssClass' => 'kv',
            'groupEvenCssClass' => 'kv',
            //'width' => '180px',
            'options' => ['colspan' => 1],
            'value' => function ($model) {
                if (($model->gcp->exist_confirm === 1) && ($model->gcp->time_confirm !== null)) {

                    //Если подтверждение ГЦП положительное выводим следующее
                    return '<div class="text-center"><span style="margin-right: 10px;">'. Html::a(
                            Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px',]]),
                            ['/confirm-gcp/view', 'id' => $model->gcp->confirm->id], ['target'=>'_blank'])
                        .'</span><span class="">'. date('d.m.y', $model->gcp->time_confirm) .'</span></div>';

                } elseif ($model->gcp->exist_confirm === 0) {

                    //Если подтверждение ГЦП отрицательное выводим следующее
                    return '<div class="text-center"><span style="margin-right: 10px;">'. Html::a(
                            Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px',]]),
                            ['/confirm-gcp/view', 'id' => $model->gcp->confirm->id], ['target'=>'_blank'])
                        .'</span><span class="">'. date('d.m.y', $model->gcp->time_confirm) .'</span></div>';

                } elseif ($model->gcp && $model->gcp->exist_confirm === null) {

                    return Html::a( Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px', 'margin-left' => '6px']]),
                        ['/confirm-gcp/create', 'id' => $model->gcp->id], ['target'=>'_blank']);
                }
            },
            'format' => 'raw',
            'hiddenFromExport' => true, // Убрать столбец при скачивании
            //'group' => true,  // enable grouping
            //'subGroupOf' => 7, // supplier column index is the parent group
        ],

        [
            'attribute' => 'status_gcp_export',
            'label' => 'Подтверждение',
            'header' => '<div class="font-header-table" style="font-size: 12px;font-weight: 500;">Подтверждение</div>',
            'groupOddCssClass' => 'kv',
            'groupEvenCssClass' => 'kv',
            'width' => '130px',
            'options' => ['colspan' => 1],
            'value' => function ($model) {
                if (($model->gcp->exist_confirm === 1) && ($model->gcp->time_confirm !== null)) {

                    //Если подтверждение ГЦП положительное выводим следующее
                    return '<div class="text-center"> <span style="margin-right: 10px;" class="skip-export-pdf">+</span><span class="skip-export-xls skip-export-html">'. Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px', 'margin-right' => '10px']]) .'</span><span class="" >'. date('d.m.y', $model->gcp->time_confirm) .'</span></div>';

                } elseif ($model->gcp->exist_confirm === 0) {

                    //Если подтверждение ГЦП отрицательное выводим следующее
                    return '<div class="text-center"> <span style="margin-right: 10px;" class="skip-export-pdf">-</span><span class="skip-export-xls skip-export-html">'. Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px', 'margin-right' => '10px']]) .'</span><span class="" >'. date('d.m.y', $model->gcp->time_confirm) .'</span></div>';

                } elseif ($model->gcp && $model->gcp->exist_confirm === null) {

                    return '<div> <span class="skip-export-pdf"> >> </span><span class="skip-export-xls skip-export-html">'. Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]) .'</span></div>';
                }
            },
            'format' => 'html',
            'hidden' => true, //Скрыть столбец со станицы, при этом при скачивании он будет виден
            //'group' => true,  // enable grouping
            //'subGroupOf' => 7, // supplier column index is the parent group
        ],

        /*[
            'attribute' => 'date_gcp_confirm',
            'label' => 'Дата',
            //'width' => '250px',
            'value' => function ($model) {
                if ($model->gcp->exist_confirm !== null) {
                    return '<span class="" style="">'. date('d.m.yy', strtotime($model->gcp->date_confirm)) .'</span>';
                }
            },
            'format' => 'html',
            'group' => true,  // enable grouping
            'subGroupOf' => 7, // supplier column index is the parent group
        ],*/

        [
            'attribute' => 'mvp',
            'label' => 'Гипотеза',
            'header' => '<div class="font-header-table" style="font-size: 12px;font-weight: 500;">Гипотеза</div>',
            'groupOddCssClass' => 'kv',
            'groupEvenCssClass' => 'kv',
            //'width' => '180px',
            'options' => ['colspan' => 1],
            'value' => function ($model) {
                if (empty($model->gcp->mvps) && $model->gcp->exist_confirm === 1) {

                    return Html::a( Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px', 'margin-left' => '6px']]),
                        ['/mvp/create', 'id' => $model->gcp->confirm->id], ['target'=>'_blank',]);

                } elseif ($model->mvp->title) {

                    return '<div class="text-center">' . Html::a($model->mvp->title, Url::to(['/mvp/view', 'id' => $model->mvp->id]), [
                            'class' => 'table-kartik-link',
                            'target'=>'_blank',
                            'data-toggle'=>'tooltip',
                            'title'=> $model->mvp->description,
                        ]) . '</div>';

                } else {

                    return '';
                }
            },
            'format' => 'raw',
            'hiddenFromExport' => true,
            //'group' => true,  // enable grouping
            //'subGroupOf' => 1 // supplier column index is the parent group
        ],

        [
            'attribute' => 'mvp_export',
            'label' => 'Гипотеза',
            'header' => '<div class="font-header-table" style="font-size: 12px;font-weight: 500;">Гипотеза</div>',
            'groupOddCssClass' => 'kv',
            'groupEvenCssClass' => 'kv',
            'width' => '100px',
            'options' => ['colspan' => 1],
            'value' => function ($model) {
                if (empty($model->gcp->mvps) && $model->gcp->exist_confirm === 1) {

                    return '<span class="skip-export-pdf"> >> </span><span class="skip-export-xls skip-export-html">' . Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]) . '</span>';

                } elseif ($model->mvp->title) {

                    return '<div class="text-center">' . $model->mvp->title . '</div>';

                } else {

                    return '';
                }
            },
            'format' => 'html',
            'hidden' => true,
            //'group' => true,  // enable grouping
            //'subGroupOf' => 1 // supplier column index is the parent group
        ],

        [
            'attribute' => 'date_mvp',
            'label' => 'Дата',
            'header' => '<div class="font-header-table" style="font-size: 12px;font-weight: 500;">Дата</div>',
            'groupOddCssClass' => 'kv',
            'groupEvenCssClass' => 'kv',
            //'width' => '120px',
            'options' => ['colspan' => 1],
            'value' => function ($model) {

                if ($model->mvp) {

                    return '<div class="text-center" style="color: #8c8c8c;">'. date('d.m.y', $model->mvp->created_at) .'</div>';
                }
            },
            'format' => 'html',
            //'group' => true,  // enable grouping
            //'subGroupOf' => 5, // supplier column index is the parent group
        ],

        [
            'attribute' => 'status_mvp',
            'label' => 'Подтверждение',
            'header' => '<div class="font-header-table" style="font-size: 12px;font-weight: 500;">Подтверждение</div>',
            'groupOddCssClass' => 'kv',
            'groupEvenCssClass' => 'kv',
            //'width' => '180px',
            'options' => ['colspan' => 1],
            'value' => function ($model) {
                if ($model->mvp->exist_confirm === 1) {

                    //Если подтверждение ГЦП положительное выводим следующее
                    return '<div class="text-center"><span style="margin-right: 10px;">'. Html::a(
                            Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px']]),
                            ['/confirm-mvp/view', 'id' => $model->mvp->confirm->id], ['target'=>'_blank',])
                        .'</span><span class="" >'. date('d.m.y', $model->mvp->time_confirm) .'</span></div>';

                } elseif ($model->mvp->exist_confirm === 0) {

                    //Если подтверждение ГЦП отрицательное выводим следующее
                    return '<div class="text-center"><span style="margin-right: 10px;">'. Html::a(
                            Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px',]]),
                            ['/confirm-mvp/view', 'id' => $model->mvp->confirm->id], ['target'=>'_blank',])
                        .'</span><span class="" >'. date('d.m.y', $model->mvp->time_confirm) .'</span></div>';

                } elseif ($model->mvp && $model->mvp->exist_confirm === null) {

                    return Html::a( Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px', 'margin-left' => '6px']]),
                        ['/confirm-mvp/create', 'id' => $model->mvp->id], ['target'=>'_blank',]);
                }
            },
            'format' => 'raw',
            'hiddenFromExport' => true, // Убрать столбец при скачивании
            //'group' => true,  // enable grouping
            //'subGroupOf' => 5, // supplier column index is the parent group
        ],

        [
            'attribute' => 'status_mvp_export',
            'label' => 'Подтверждение',
            'header' => '<div class="font-header-table" style="font-size: 12px;font-weight: 500;">Подтверждение</div>',
            'groupOddCssClass' => 'kv',
            'groupEvenCssClass' => 'kv',
            'width' => '130px',
            'options' => ['colspan' => 1],
            'value' => function ($model) {
                if ($model->mvp->exist_confirm === 1) {

                    //Если подтверждение ГЦП положительное выводим следующее
                    return '<div class="text-center"><span style="margin-right: 10px;" class="skip-export-pdf">+</span><span class="skip-export-xls skip-export-html">'. Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px', 'margin-right' => '10px']]) .'</span><span class="" >'. date('d.m.y', $model->mvp->time_confirm) .'</span></div>';

                } elseif ($model->mvp->exist_confirm === 0) {

                    //Если подтверждение ГЦП отрицательное выводим следующее
                    return '<div class="text-center"><span style="margin-right: 10px;" class="skip-export-pdf">-</span><span class="skip-export-xls skip-export-html">'. Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px', 'margin-right' => '10px']]) .'</span><span class="" >'. date('d.m.y', $model->mvp->time_confirm) .'</span></div>';

                } elseif ($model->mvp && $model->mvp->exist_confirm === null) {

                    return '<div> <span class="skip-export-pdf"> >> </span><span class="skip-export-xls skip-export-html">'. Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]) .'</span></div>';
                }
            },
            'format' => 'html',
            'hidden' => true, //Скрыть столбец со станицы, при этом при скачивании он будет виден
            //'group' => true,  // enable grouping
            //'subGroupOf' => 5, // supplier column index is the parent group
        ],

        /*[
            'attribute' => 'date_mvp_confirm',
            'label' => 'Дата',
            //'width' => '250px',
            'value' => function ($model) {
                if ($model->gmvp->exist_confirm !== null) {
                    return '<span class="" style="">'. date('d.m.yy', strtotime($model->gmvp->date_confirm)) .'</span>';
                }
            },
            'format' => 'html',
            //'group' => true,  // enable grouping
            //'subGroupOf' => 5, // supplier column index is the parent group
        ],*/

        [
            'attribute' => 'businessModel',
            'label' => 'Бизнес-модель',
            'header' => '<div class="font-header-table" style="font-size: 12px;font-weight: 500;">Модель и презентация</div>',
            'groupOddCssClass' => 'kv',
            'groupEvenCssClass' => 'kv',
            'options' => ['colspan' => 1],
            'value' => function ($model) {

                if ($model->mvp->exist_confirm === 1){

                    if ($model->id) {

                        return '<div style="display: flex; justify-content: space-around;">' . Html::a(Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px']]), ['/business-model/view', 'id' => $model->id], [
                                'target'=>'_blank',
                                //'data-toggle'=>'tooltip',
                                //'title'=> 'Открыть страницу бизнес-модели',
                            ]) .
                            Html::a(Html::img('@web/images/icons/icon-pdf-export.png', ['style' => ['width' => '20px',]]), ['/projects/mpdf-business-model', 'id' => $model->id], [
                                'target'=>'_blank',
                                //'data-toggle'=>'tooltip',
                                //'title'=> 'Скачать презентацию',
                                ]) . '</div>';

                    } else {

                        return '<div style="padding-left: 25px;">' . Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]), ['/business-model/create', 'id' => $model->mvp->confirm->id], [
                                'target'=>'_blank',
                                //'data-toggle'=>'tooltip',
                                //'title'=> 'Создать бизнес-модель',
                            ]) . '</div>';
                    }
                }
            },
            'format' => 'raw',
            'hiddenFromExport' => true, // Убрать столбец при скачивании
            //'group' => true,  // enable grouping
            //'subGroupOf' => 1 // supplier column index is the parent group
        ],


        [
            'attribute' => 'businessModel-export',
            'label' => 'Бизнес-модель',
            'header' => '<div class="font-header-table" style="font-size: 12px;font-weight: 500;">Модель и презентация</div>',
            'groupOddCssClass' => 'kv',
            'groupEvenCssClass' => 'kv',
            'options' => ['colspan' => 1],
            'width' => '180px',
            'value' => function ($model) {

                if ($model->mvp->exist_confirm === 1){

                    if ($model->id) {

                        return '<div class="skip-export-pdf">БМ | Презентация</div>
                                <div class="skip-export-xls skip-export-html">' . Html::img('@web/images/icons/positive-offer.png', ['class' => 'positive-business-model-export', 'style' => ['width' => '20px', 'margin' => '0 40px 0 20px']]) .
                            Html::img('@web/images/icons/icon-pdf-export.png', ['class' => 'presentation-business-model-export', 'style' => ['width' => '20px']]) . '</div>';

                    } else {

                        return '<div class="skip-export-pdf"> >> </div>
                            <div class="skip-export-xls skip-export-html">' . Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px',  'margin-left' => '20px']]) . '</div>';
                    }
                }
            },
            'format' => 'raw',
            'hidden' => true, //Скрыть столбец со станицы, при этом при скачивании он будет виден
            //'group' => true,  // enable grouping
            //'subGroupOf' => 1 // supplier column index is the parent group
        ],
    ];




    // export menu
    /*echo ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => $gridColumns,
            'onRenderDataCell' => function(PhpOffice\PhpSpreadsheet\Cell\Cell $cell, $content, $model, $key, $index, kartik\export\ExportMenu $widget) {
                $column = $cell->getColumn();
                $columnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($column) - 1;
                $value = '@web/images/icons/cross delete.png';
                if(file_exists($value)) {   // change the condition as you prefer*/
    /* @var PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $worksheet */
    /*$firstRow = 2;  // skip header row
    $imageName = "Image name";      // Add a name
    $imageDescription = "Image description";    // Add a description
    $padding = 5;
    $imageWidth = 60;   // Image width
    $imageHeight = 60;  // Image height
    $cellID = $column . ($index + $firstRow);   // Get cell identifier
    $worksheet = $cell->getWorksheet();
    $worksheet->getRowDimension($index + $firstRow)->setRowHeight($imageHeight + ($padding * 2));
    $worksheet->getColumnDimension($column)->setAutoSize(false);
    $worksheet->getColumnDimension($column)->setWidth($imageWidth + ($padding * 2));
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setName($imageName);
    $drawing->setDescription($imageDescription);
    $drawing->setPath($value); // put your path and image here
    $drawing->setCoordinates($cellID);
    $drawing->setOffsetX(200);
    $drawing->setWidth($imageWidth);
    $drawing->setHeight($imageHeight);
    $drawing->setWidthAndHeight($imageWidth, $imageHeight);
    $drawing->setWorksheet($worksheet);

}
},
'dropdownOptions' => [
'label' => 'Export All',
'class' => 'btn btn-secondary'
]
]) . "<hr>\n";*/




    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'showPageSummary' => true,
        //'pjax' => true,
        'striped' => false,
        'bordered' => false,
        'condensed' => true,
        'summary' => false,
        'hover' => true,

        'panel' => [
            'type' => 'default',
            'heading' => false,
            //'headingOptions' => ['class' => 'style-head-table-kartik-top'],
            'before' => '<div style="font-size: 30px; font-weight: 700; color: #F2F2F2;">Проект: «'. $project->project_name . '» ' . Html::a('Посмотреть описание', ['/projects/view', 'id' => $project->id], ['style' => ['font-size' => '12px', 'color' => '#F2F2F2', 'font-weight' => '300']]) .'</div>',
            'beforeOptions' => ['class' => 'style-head-table-kartik-top']
        ],

        'toolbar' => [
            '{export}',
        ],

        'exportContainer' => ['class' => 'btn-group-sm', 'style' => ['padding' => '5px 5px']],
        //'toggleDataContainer' => ['class' => 'btn-group mr-2'],

        'export'=>[
            'showConfirmAlert'=>false,
            'target'=>GridView::TARGET_BLANK,
            'label' => '<span class="font-header-table" style="font-weight: 700;">Экпорт таблицы</span>',
            'options' => ['title' => false],
        ],

        'columns' => $gridColumns,

        'exportConfig' => [
            GridView::PDF => [

                'filename' => 'Сводная_таблица_проекта_«'. $project_filename . '»',

                'config' => [

                    'marginRight' => 10,
                    'marginLeft' => 10,
                    //'cssInline' => '.positive-business-model-export{margin-right: 20px;}' .
                        //'.presentation-business-model-export{margin-left: 20px;}',

                    'methods' => [
                        'SetHeader' => ['<div style="color: #3c3c3c;">Сводная таблица проекта «'.$project->project_name.'»</div>||<div style="color: #3c3c3c;">Сгенерировано: ' . date("H:i d.m.Y") . '</div>'],
                        'SetFooter' => ['<div style="color: #3c3c3c;">Страница {PAGENO}</div>'],
                    ],

                    'options' => [
                        //'title' => 'Сводная таблица проекта «'.$project->project_name.'»',
                        //'subject' => Yii::t('kvgrid', 'PDF export generated by kartik-v/yii2-grid extension'),
                        //'keywords' => Yii::t('kvgrid', 'krajee, grid, export, yii2-grid, pdf')
                    ],

                    //'contentBefore' => '',
                    //'contentAfter' => '',
                ],

            ],
            GridView::EXCEL => [
                'filename' => 'Сводная_таблица_проекта_«'. $project_filename . '»',
            ],
            GridView::HTML => [
                'filename' => 'Сводная_таблица_проекта_«'. $project_filename . '»',
            ],
        ],

        //'floatHeader'=>true,
        //'floatHeaderOptions'=>['top'=>'50'],
        'headerRowOptions' => ['class' => 'style-head-table-kartik-bottom'],

        'beforeHeader' => [
            [
                'columns' => [
                    ['content' =>  Html::a(Html::img('@web/images/icons/icon-plus.png', ['style' => ['width' => '30px', 'margin-right' => '10px', 'margin-left' => '5px']]), ['/segment/index', 'id' => $project->id], ['target'=>'_blank',]) . ' Сегмент', 'options' => ['colspan' => 2, 'class' => 'font-segment-header-table']],
                    ['content' => 'Проблема сегмента', 'options' => ['colspan' => 3, 'class' => 'font-header-table', 'style' => ['padding-top' => '10px', 'padding-bottom' => '10px', 'text-align' => 'center']]],
                    ['content' => 'Ценностное предложение', 'options' => ['colspan' => 3, 'class' => 'font-header-table', 'style' => ['padding-top' => '10px', 'padding-bottom' => '10px', 'text-align' => 'center']]],
                    ['content' => 'Гипотеза MVP (продукт)', 'options' => ['colspan' => 3, 'class' => 'font-header-table', 'style' => ['padding-top' => '10px', 'padding-bottom' => '10px', 'text-align' => 'center']]],
                    ['content' => 'Бизнес-модель', 'options' => ['colspan' => 1, 'class' => 'font-header-table', 'style' => ['padding-top' => '10px', 'padding-bottom' => '10px', 'text-align' => 'center']]],
                ],

                'options' => [
                    'class' => 'style-header-table-kartik',
                ]
            ]
        ],
    ]);

    ?>

</div>