<?php

use yii\helpers\Html;

$string = '';
$default_value = '_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _';

$patent_name = $project->patent_name;
if (empty($patent_name)) {
    $patent_name = $default_value;
}

$patent_number = $project->patent_number;
if (empty($patent_number)) {
    $patent_number = $default_value;
}

$patent_date = date('d.m.Y', $project->patent_date);
if (empty($project->patent_date)) {
    $patent_date = $default_value;
}

$layout_technology = $project->layout_technology;
if (empty($layout_technology)) {
    $layout_technology = $default_value;
}

$site = $project->site;
if (empty($site)) {
    $site = $default_value;
}

$register_name = $project->register_name;
if (empty($register_name)) {
    $register_name = $default_value;
}

$register_date = date('d.m.Y', $project->register_date);
if (empty($project->register_date)) {
    $register_date = $default_value;
}

$invest_name = $project->invest_name;
if (empty($invest_name)) {
    $invest_name = $default_value;
}

$invest_amount = number_format($project->invest_amount, 0, '', ' ') . ' руб.';
if (empty($project->invest_amount)) {
    $invest_amount = $default_value;
}

$invest_date = date('d.m.Y', $project->invest_date);
if (empty($project->invest_date)) {
    $invest_date = $default_value;
}

$announcement_event = $project->announcement_event;
if (empty($announcement_event)) {
    $announcement_event = $default_value;
}

$date_of_announcement = date('d.m.Y', $project->date_of_announcement);
if (empty($project->date_of_announcement)) {
    $date_of_announcement = $default_value;
}

$string .= '<div class="row container-fluid" style="color: #4F4F4F;">';

$string .= '<div class="panel panel-default"><div class="panel-heading" style="font-size: 24px;">Описание проекта</div></div>
                    <div style="font-weight: 700;">Сокращенное наименование проекта</div><div style="margin-bottom: 10px;">'.$project->project_name.'</div>
                    <div style="font-weight: 700;">Полное наименование проекта</div><div style="margin-bottom: 10px;">'.$project->project_fullname.'</div>
                    <div style="font-weight: 700;">Описание проекта</div><div style="margin-bottom: 20px;">'.$project->description.'</div>
                    
                    <div class="panel panel-default"><div class="panel-heading" style="font-size: 24px;">Результат интеллектуальной деятельности</div></div>
                    <div style="font-weight: 700;">Результат интеллектуальной деятельности</div><div style="margin-bottom: 10px;">'.$project->rid.'</div>
                    <div style="font-weight: 700;">Суть результата интеллектуальной деятельности</div><div style="margin-bottom: 20px;">'.$project->core_rid.'</div>
                    
                    <div class="panel panel-default"><div class="panel-heading" style="font-size: 24px;">Сведения о патенте</div></div>
                    <div style="font-weight: 700;">Наименование патента</div><div style="margin-bottom: 10px;">'.$patent_name.'</div>
                    <div style="font-weight: 700;">Номер патента</div><div style="margin-bottom: 10px;">'.$patent_number.'</div>
                    <div style="font-weight: 700;">Дата получения патента</div><div style="margin-bottom: 20px;">'.$patent_date.'</div>
                    
                    <div class="panel panel-default"><div class="panel-heading" style="font-size: 24px;">Команда проекта</div></div>
                    <div style="margin-bottom: 10px;">'.$project->showListAuthors().'</div>
                    
                    <div class="panel panel-default"><div class="panel-heading" style="font-size: 24px;">Сведения о технологии</div></div>
                    <div style="font-weight: 700;">На какой технологии основан проект</div><div style="margin-bottom: 10px;">'.$project->technology.'</div>
                    <div style="font-weight: 700;">Макет базовой технологии</div><div style="margin-bottom: 20px;">'.$layout_technology.'</div>
                    
                    <div class="panel panel-default"><div class="panel-heading" style="font-size: 24px;">Регистрация юридического лица</div></div>
                    <div style="font-weight: 700;">Зарегистрированное юр. лицо</div><div style="margin-bottom: 10px;">'.$register_name.'</div>
                    <div style="font-weight: 700;">Дата регистрации</div><div style="margin-bottom: 20px;">'.$register_date.'</div>
                    
                    <div class="panel panel-default"><div class="panel-heading" style="font-size: 24px;">Адрес сайта</div></div>
                    <div style="margin-bottom: 20px;">'.$site.'</div>
                    
                    <div class="panel panel-default"><div class="panel-heading" style="font-size: 24px;">Инвестиции в проект</div></div>
                    <div style="font-weight: 700;">Инвестор</div><div style="margin-bottom: 10px;">'.$invest_name.'</div>
                    <div style="font-weight: 700;">Сумма инвестиций</div><div style="margin-bottom: 10px;">'.$invest_amount.'</div>
                    <div style="font-weight: 700;">Дата получения инвестиций</div><div style="margin-bottom: 20px;">'.$invest_date.'</div>
                    
                    <div class="panel panel-default"><div class="panel-heading" style="font-size: 24px;">Анонс проекта</div></div>
                    <div style="font-weight: 700;">Мероприятие, на котором проект анонсирован впервые</div><div style="margin-bottom: 10px;">'.$announcement_event.'</div>
                    <div style="font-weight: 700;">Дата получения инвестиций</div><div style="margin-bottom: 20px;">'.$date_of_announcement.'</div>
                    
                    <div class="panel panel-default"><div class="panel-heading" style="font-size: 24px;">Презентационные файлы</div></div>';

$string .= '<div style="margin-bottom: 20px;">';

if (!empty($project->preFiles)) {
    foreach ($project->preFiles as $file) {
        $filename = $file->file_name;
        if (mb_strlen($filename) > 35) {
            $filename = mb_substr($file->file_name, 0, 35) . '...';
        }
        $string .= '<div style="display: flex; margin: 2px 0; align-items: center;" class="one_block_file-' . $file->id . '">' .
            Html::a('<div style="display:flex; width: 100%; justify-content: space-between;"><div>' . $filename . '</div><div>' . Html::img('/images/icons/icon_export.png', ['style' => ['width' => '22px']]) . '</div></div>', ['/projects/download', 'id' => $file->id], [
                'title' => 'Скачать файл',
                'class' => 'btn btn-default prefiles',
                'style' => [
                    'display' => 'flex',
                    'align-items' => 'center',
                    'justify-content' => 'center',
                    'background' => '#E0E0E0',
                    'width' => '320px',
                    'height' => '40px',
                    'text-align' => 'left',
                    'font-size' => '14px',
                    'border-radius' => '8px',
                    'margin-right' => '5px',
                ]
            ]) . '</div>';
    }
} else {
    $string .= $default_value;
}

$string .= '</div></div>';

echo $string;