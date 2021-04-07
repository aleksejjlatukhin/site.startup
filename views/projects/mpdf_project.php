<?php

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

?>

<div class="project-view-export">

    <!--Описание проекта-->
    <div class="header_section">Описание проекта</div>

    <div class="section_content">
        <h4>Сокращенное наименование проекта</h4>
        <div><?= $project->project_name; ?></div>
        <h4>Полное наименование проекта</h4>
        <div><?= $project->project_fullname; ?></div>
        <h4>Описание проекта</h4>
        <div><?= $project->description; ?></div>
        <h4>Цель проекта</h4>
        <div><?= $project->purpose_project; ?></div>
    </div>

    <!--Результат интеллектуальной деятельности-->
    <div class="header_section">Результат интеллектуальной деятельности</div>

    <div class="section_content">
        <h4>Результат интеллектуальной деятельности</h4>
        <div><?= $project->rid; ?></div>
        <h4>Суть результата интеллектуальной деятельности</h4>
        <div><?= $project->core_rid; ?></div>
    </div>

    <!--Сведения о патенте-->
    <div class="header_section">Сведения о патенте</div>

    <div class="section_content">
        <h4>Наименование патента</h4>
        <div><?= $patent_name; ?></div>
        <h4>Номер патента</h4>
        <div><?= $patent_number; ?></div>
        <h4>Дата получения патента</h4>
        <div><?= $patent_date; ?></div>
    </div>

    <!--Команда проекта-->
    <div class="header_section">Команда проекта</div>
    
    <div class="section_content">
        <?= $project->showListAuthors(); ?>
    </div>

    <!--Сведения о технологии-->
    <div class="header_section">Сведения о технологии</div>

    <div class="section_content">
        <h4>На какой технологии основан проект</h4>
        <div><?= $project->technology; ?></div>
        <h4>Макет базовой технологии</h4>
        <div><?= $layout_technology; ?></div>
    </div>

    <!--Регистрация юридического лица-->
    <div class="header_section">Регистрация юридического лица</div>

    <div class="section_content">
        <h4>Зарегистрированное юр. лицо</h4>
        <div><?= $register_name; ?></div>
        <h4>Дата регистрации</h4>
        <div><?= $register_date; ?></div>
    </div>

    <!--Адрес сайта-->
    <div class="header_section">Адрес сайта</div>

    <div class="section_content">
        <div><?= $site; ?></div>
    </div>

    <!--Инвестиции в проект-->
    <div class="header_section">Инвестиции в проект</div>

    <div class="section_content">
        <h4>Инвестор</h4>
        <div><?= $invest_name; ?></div>
        <h4>Сумма инвестиций</h4>
        <div><?= $invest_amount; ?></div>
        <h4>Дата получения инвестиций</h4>
        <div><?= $invest_date; ?></div>
    </div>

    <!--Анонс проекта-->
    <div class="header_section">Анонс проекта</div>

    <div class="section_content">
        <h4>Мероприятие, на котором проект анонсирован впервые</h4>
        <div><?= $announcement_event; ?></div>
        <h4>Дата анонсирования проекта</h4>
        <div><?= $date_of_announcement; ?></div>
    </div>

</div>
