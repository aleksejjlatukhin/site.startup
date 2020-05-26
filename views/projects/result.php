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

                return '<div style="padding: 0 5px;">' . Html::a($str, Url::to(['/segment/view', 'id' => $model->segment->id]), ['class' => 'table-kartik-link']) . '</div>';
            },
            'format' => 'html',
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
            //'width' => '350px',
            'options' => ['colspan' => 1],
            'value' => function ($model, $key, $index, $widget) {

                return '<span class="table-kartik-link">' . $model->segment->name . '</span>';
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

                return '<div class="text-center" style="color: #8c8c8c;">'. date('d.m.y', strtotime($model->segment->creat_date)) .'</div>';
            },
            'format' => 'html',
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
                if (empty($model->problem)) {

                    return Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px', 'margin-left' => '6px']]);

                } elseif ($model->problem->title) {

                    return '<div class="text-center">' . Html::a($model->problem->title, Url::to(['/generation-problem/view', 'id' => $model->problem->id]), ['class' => 'table-kartik-link']) . '</div>';

                } else {

                    return '';
                }
            },
            'format' => 'html',
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
            //'width' => '180px',
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

                if ($model->problem && ($model->problem->date_gps !== null)) {

                    return '<div class="text-center" style="color: #8c8c8c;">'. date('d.m.y', strtotime($model->problem->date_gps)) .'</div>';
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
                if (($model->problem->exist_confirm === 1) && ($model->problem->date_confirm !== null)) {

                    //Если подтверждение ГЦП положительное выводим следующее
                    return '<div class="text-center"><span style="margin-right: 10px;">'. Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px',]]) .'</span><span class="">'. date('d.m.y', strtotime($model->problem->date_confirm)) .'</span></div>';

                }elseif ($model->problem->exist_confirm === 0) {

                    //Если подтверждение ГЦП отрицательное выводим следующее
                    return '<div class="text-center"> <span style="margin-right: 10px;">'. Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px',]]) .'</span><span class="" >'. date('d.m.y', strtotime($model->problem->date_confirm)) .'</span></div>';

                }elseif ($model->problem && $model->problem->exist_confirm === null) {

                    return Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px', 'margin-left' => '6px']]);
                }
            },
            'format' => 'html',
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
            //'width' => '250px',
            'options' => ['colspan' => 1],
            'value' => function ($model) {
                if (($model->problem->exist_confirm === 1) && ($model->problem->date_confirm !== null)) {

                    //Если подтверждение ГЦП положительное выводим следующее
                    return '<div class="text-center"> <span style="margin-right: 10px;" class="skip-export-pdf">+</span><span style="margin-right: 10px;" class="skip-export-xls skip-export-html">'. Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px',]]) .'</span><span class="" >'. date('d.m.y', strtotime($model->problem->date_confirm)) .'</span></div>';

                }elseif ($model->problem->exist_confirm === 0) {

                    //Если подтверждение ГЦП отрицательное выводим следующее
                    return '<div class="text-center"> <span style="margin-right: 10px;" class="skip-export-pdf">-</span><span style="margin-right: 10px;" class="skip-export-xls skip-export-html">'. Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px',]]) .'</span><span class="" >'. date('d.m.y', strtotime($model->problem->date_confirm)) .'</span></div>';

                }elseif ($model->problem && $model->problem->exist_confirm === null) {

                    return '<div> <span class="skip-export-pdf"> >> </span><span class="skip-export-xls skip-export-html">'. Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px', 'margin-left' => '6px']]) .'</span></div>';
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
                    return '<span class="" style="">'. date('d.m.yy', strtotime($model->problem->date_confirm)) .'</span>';
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

                    return Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px', 'margin-left' => '6px']]);

                } elseif ($model->gcp->title) {

                    return '<div class="text-center">' . Html::a($model->gcp->title, Url::to(['/gcp/view', 'id' => $model->gcp->id]), ['class' => 'table-kartik-link']) . '</div>';

                } else {

                    return '';
                }
            },
            'format' => 'html',
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
            //'width' => '180px',
            'options' => ['colspan' => 1],
            'value' => function ($model) {
                if (empty($model->problem->gcps) && $model->problem->exist_confirm === 1) {

                    return '<span class="skip-export-pdf"> >> </span><span class="skip-export-xls skip-export-html">' . Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px', 'margin-left' => '6px']]) . '</span>';

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

                if ($model->gcp && ($model->gcp->date_create !== null)) {

                    return '<div class="text-center" style="color: #8c8c8c;">'. date('d.m.y', strtotime($model->gcp->date_create)) .'</div>';
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
                if (($model->gcp->exist_confirm === 1) && ($model->gcp->date_confirm !== null)) {

                    //Если подтверждение ГЦП положительное выводим следующее
                    return '<div class="text-center"><span style="margin-right: 10px;">'. Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px',]]) .'</span><span class="">'. date('d.m.y', strtotime($model->gcp->date_confirm)) .'</span></div>';

                } elseif ($model->gcp->exist_confirm === 0) {

                    //Если подтверждение ГЦП отрицательное выводим следующее
                    return '<div class="text-center"><span style="margin-right: 10px;">'. Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px',]]) .'</span><span class="">'. date('d.m.y', strtotime($model->gcp->date_confirm)) .'</span></div>';

                } elseif ($model->gcp && $model->gcp->exist_confirm === null) {

                    return Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px', 'margin-left' => '6px']]);
                }
            },
            'format' => 'html',
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
            //'width' => '180px',
            'options' => ['colspan' => 1],
            'value' => function ($model) {
                if (($model->gcp->exist_confirm === 1) && ($model->gcp->date_confirm !== null)) {

                    //Если подтверждение ГЦП положительное выводим следующее
                    return '<div class="text-center"> <span style="margin-right: 10px;" class="skip-export-pdf">+</span><span style="margin-right: 10px;" class="skip-export-xls skip-export-html">'. Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px',]]) .'</span><span class="" >'. date('d.m.y', strtotime($model->gcp->date_confirm)) .'</span></div>';

                } elseif ($model->gcp->exist_confirm === 0) {

                    //Если подтверждение ГЦП отрицательное выводим следующее
                    return '<div class="text-center"> <span style="margin-right: 10px;" class="skip-export-pdf">-</span><span style="margin-right: 10px;" class="skip-export-xls skip-export-html">'. Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px',]]) .'</span><span class="" >'. date('d.m.y', strtotime($model->gcp->date_confirm)) .'</span></div>';

                } elseif ($model->gcp && $model->gcp->exist_confirm === null) {

                    return '<div> <span class="skip-export-pdf"> >> </span><span class="skip-export-xls skip-export-html">'. Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px', 'margin-left' => '6px']]) .'</span></div>';
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

                    return Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px', 'margin-left' => '6px']]);

                } elseif ($model->gmvp->title) {

                    return '<div class="text-center">' . Html::a($model->gmvp->title, Url::to(['/mvp/view', 'id' => $model->gmvp->id]), ['class' => 'table-kartik-link']) . '</div>';

                } else {

                    return '';
                }
            },
            'format' => 'html',
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
            //'width' => '180px',
            'options' => ['colspan' => 1],
            'value' => function ($model) {
                if (empty($model->gcp->mvps) && $model->gcp->exist_confirm === 1) {

                    return '<span class="skip-export-pdf"> >> </span><span class="skip-export-xls skip-export-html">' . Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px', 'margin-left' => '6px']]) . '</span>';

                } elseif ($model->gmvp->title) {

                    return '<div class="text-center">' . $model->gmvp->title . '</div>';

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

                if ($model->gmvp) {

                    return '<div class="text-center" style="color: #8c8c8c;">'. date('d.m.y', strtotime($model->gmvp->date_create)) .'</div>';
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
                if ($model->gmvp->exist_confirm === 1) {

                    //Если подтверждение ГЦП положительное выводим следующее
                    return '<div class="text-center"><span style="margin-right: 10px;">'. Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px',]]) .'</span><span class="" >'. date('d.m.y', strtotime($model->gmvp->date_confirm)) .'</span></div>';

                } elseif ($model->gmvp->exist_confirm === 0) {

                    //Если подтверждение ГЦП отрицательное выводим следующее
                    return '<div class="text-center"><span style="margin-right: 10px;">'. Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px',]]) .'</span><span class="" >'. date('d.m.y', strtotime($model->gmvp->date_confirm)) .'</span></div>';

                } elseif ($model->gmvp && $model->gmvp->exist_confirm === null) {

                    return Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px', 'margin-left' => '6px']]);
                }
            },
            'format' => 'html',
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
            //'width' => '180px',
            'options' => ['colspan' => 1],
            'value' => function ($model) {
                if ($model->gmvp->exist_confirm === 1) {

                    //Если подтверждение ГЦП положительное выводим следующее
                    return '<div class="text-center"><span style="margin-right: 10px;" class="skip-export-pdf">+</span><span style="margin-right: 10px;" class="skip-export-xls skip-export-html">'. Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px',]]) .'</span><span class="" >'. date('d.m.y', strtotime($model->gmvp->date_confirm)) .'</span></div>';

                } elseif ($model->gmvp->exist_confirm === 0) {

                    //Если подтверждение ГЦП отрицательное выводим следующее
                    return '<div class="text-center"><span style="margin-right: 10px;" class="skip-export-pdf">-</span><span style="margin-right: 10px;" class="skip-export-xls skip-export-html">'. Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px',]]) .'</span><span class="" >'. date('d.m.y', strtotime($model->gmvp->date_confirm)) .'</span></div>';

                } elseif ($model->gmvp && $model->gmvp->exist_confirm === null) {

                    return '<div> <span class="skip-export-pdf"> >> </span><span class="skip-export-xls skip-export-html">'. Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px', 'margin-left' => '6px']]) .'</span></div>';
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
            //'header' => false,
            //'width' => '120px',
            'options' => ['colspan' => 1],
            'value' => function ($model) {

                if ($model->gmvp->exist_confirm === 1){

                    if ($model->id) {

                        return '<div class="text-center">' . Html::a(Html::img('@web/images/icons/icon-view-model.png', ['style' => ['width' => '20px', 'height' => '20px']]), ['business-model/view', 'id' => $model->id]) . '</div>';

                    } else {

                        return '<div class="text-center">' . Html::a(Html::img('@web/images/icons/icon-create-model.png', ['style' => ['width' => '20px', 'height' => '20px']]), ['business-model/create', 'id' => $model->gmvp->confirm->id]) . '</div>';
                    }
                }
            },
            'format' => 'html',
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
        'pjax' => true,
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
            'target'=>GridView::TARGET_BLANK
        ],

        'columns' => $gridColumns,

        'exportConfig' => [
            GridView::PDF => [

            ],
            GridView::EXCEL => [

            ],
            GridView::HTML => [

            ],
        ],

        //'floatHeader'=>true,
        //'floatHeaderOptions'=>['top'=>'50'],
        'headerRowOptions' => ['class' => 'style-head-table-kartik-bottom'],

        'beforeHeader' => [
            [
                'columns' => [
                    ['content' =>  Html::a(Html::img('@web/images/icons/icon-plus.png', ['style' => ['width' => '30px', 'margin-right' => '10px', 'margin-left' => '5px']]), ['/segment/index', 'id' => $project->id]) . ' Сегмент', 'options' => ['colspan' => 2, 'class' => 'font-segment-header-table']],
                    ['content' => 'Проблема сегмента', 'options' => ['colspan' => 3, 'class' => 'font-header-table', 'style' => ['padding' => '10px 0']]],
                    ['content' => 'Ценностное предложение', 'options' => ['colspan' => 3, 'class' => 'font-header-table', 'style' => ['padding' => '10px 0']]],
                    ['content' => 'Гипотеза MVP (продукт)', 'options' => ['colspan' => 3, 'class' => 'font-header-table', 'style' => ['padding' => '10px 0']]],
                    ['content' => 'Бизнес-модель', 'options' => ['colspan' => 1, 'class' => 'font-header-table', 'style' => ['padding' => '10px 0']]],
                ],

                'options' => [
                    'class' => 'style-header-table-kartik',
                ]
            ]
        ],
    ]);

    ?>

</div>