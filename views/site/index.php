<?php

use yii\helpers\Html;
use app\models\User;

/* @var $this yii\web\View */

$this->title = 'Главная';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">

    <?//= $user['fio'];?>
    <?/* foreach ($user['projects'] as $project) :*/?><!--
        <a href="<?/*=\yii\helpers\Url::to(['projects/view', 'id' => $project->id])*/?>"><?/*= $project['project_name']*/?></a>
    --><?/* endforeach;*/?>

    <div class="row">
        <div class="col-md-10">

            <?php
            if (User::isUserSimple(Yii::$app->user->identity['username'])){
                echo Html::a('<span style="text-transform: uppercase;font-weight: 700;">Создать проект</span>', ['projects/create', 'id' => Yii::$app->user->identity['id']], ['class' => 'btn btn-success btn-block']);
            }
            ?>

            <?php if (empty(Yii::$app->user->identity)){
                echo Html::a('<span style="text-transform: uppercase;font-weight: 700;">Создать проект</span>', ['site/login'], ['class' => 'btn btn-success btn-block']);
            }
            ?>

            <?//= Html::a('<span style="text-transform: uppercase;font-weight: 700;">Создать проект</span>', ['projects/create', 'id' => Yii::$app->user->identity['id']], ['class' => 'btn btn-success btn-block']) ?>

            <h3 style="margin-bottom: 20px;">Мы рады приветствовать вас на портале <span style="font-weight: 700;">АКСЕЛЕРАТОРА СТАРТАП-ПРОЕКТОВ</span>!</h3>

            <p><span style="font-weight: 700;">АКСЕЛЕРАТОР СТАРТАП-ПРОЕКТОВ</span> адресован основателям и командам стартапов, у кого есть идея инновационного продукта.</p>

            <p>Любая бизнес-идея имеет шанс на реализацию, если ее потребность протестирована рынком. Какая бы красивая бизнес идея ни была, она не стоит ничего, если ее ценность не подтверждена рынком! </p>

            <p>Тестирование бизнес идеи – это технология (пошаговые действия по алгоритму), которая требует точного выполнения последовательных определенный действий по взаимодействию с рынком. Неточность и формальное следование предложенному алгоритму приведет к ложным результатам и пустой трате времени.</p>

            <p><span style="font-weight: 700;">АКСЕЛЕРАТОР СТАРТАП-ПРОЕКТОВ</span> предлагает воспользоваться технологией создания и управления процессом разработки инновационного продукта, который использовали все известные вам бренды (Faсebook, VK, Google, Apple,  и т.д.).</p>

            <p><span style="font-weight: 700;">АКСЕЛЕРАТОР СТАРТАП-ПРОЕКТОВ</span> - это пошаговое руководство как вашу идею вывести на рынок. Каждый из этапов имеет свою обоснованную ценность и является неотъемлемой частью цикла разработки проекта. </p>

            <p>В рамках работы с приложением <span style="font-weight: 700;">АКСЕЛЕРАТОР СТАРТАП-ПРОЕКТОВ</span> необходимо двигаться поступательно, чтобы получить нужный эффект.  </p>
        </div>
    </div>

</div>
