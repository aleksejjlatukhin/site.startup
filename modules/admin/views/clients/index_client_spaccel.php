<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>


<div class="row container-one_client client_container_number-<?=$client->id;?>">

    <div class="col-md-3 column-client-name" id="link_client_page-<?= $client->id;?>">

        <?php if ($client->settings->getAvatarImage()) : ?>
            <?= Html::img('/web/upload/company-'.$client->getId().'/avatar/'.$client->settings->getAvatarImage(), ['class' => 'user_picture']); ?>
        <?php else : ?>
            <?= Html::img('/images/avatar/client_default.png', ['class' => 'user_picture_default']); ?>
        <?php endif; ?>

        <div class="block-name-and-fullname">
            <div class="block-name">
                <?= Html::a($client->name, ['/admin/clients/view', 'id' => $client->id], [
                    'class' => 'block_name_link',
                    'title' => 'Перейти на страницу организации'
                ]); ?>
            </div>
            <div class="block-fullname"><?= $client->fullname; ?></div>
            <div class="block-admin-profile-link">
                <div class="bolder">Администратор</div>
                <?php $admin = $client->settings->admin; ?>
                <?= Html::a($admin->first_name.' '.$admin->middle_name.' '.$admin->second_name, ['/admin/profile/index', 'id' => $admin->id], [
                    'class' => 'block_name_link',
                    'title' => 'Перейти в профиль'
                ]); ?>
            </div>
        </div>

    </div>

    <div class="col-md-2 column-client-info-entities">
        <div>-----</div>
    </div>

    <div class="col-md-2 column-client-info-entities" style="display:flex; justify-content: center;">

        <?= Html::a( '<span class="glyphicon glyphicon-user" style="font-size: 16px;"></span><span style="margin-left: 5px;"> - '.$client->countTrackers.'</span>', Url::to(['/admin/users/admins', 'id' => $client->id]), [
            'style' => [
                'display' => 'flex',
                'align-items' => 'center',
                'justify-content' => 'center',
                'background' => '#E0E0E0',
                'width' => '110px',
                'height' => '40px',
                'font-size' => '18px',
                'border-radius' => '8px 0 0 8px',
            ],
            'class' => 'btn btn-lg btn-default',
            'title' => 'Трекеры',
        ]);?>

        <?= Html::a( '<span class="glyphicon glyphicon-user" style="font-size: 16px;"></span><span style="margin-left: 5px;"> - '.$client->countExperts.'</span>', Url::to(['/admin/users/experts', 'id' => $client->id]), [
            'style' => [
                'display' => 'flex',
                'align-items' => 'center',
                'justify-content' => 'center',
                'background' => '#E0E0E0',
                'width' => '110px',
                'height' => '40px',
                'font-size' => '18px',
                'border-radius' => '0 8px 8px 0',
            ],
            'class' => 'btn btn-lg btn-default',
            'title' => 'Эксперты',
        ]);?>

    </div>

    <div class="col-md-2 column-client-info-entities" style="display:flex; justify-content: center;">

        <?= Html::a( '<span class="glyphicon glyphicon-user" style="font-size: 16px;"></span><span style="margin-left: 5px;"> - '.$client->countUsers.'</span>', Url::to(['/admin/users/index', 'id' => $client->id]), [
            'style' => [
                'display' => 'flex',
                'align-items' => 'center',
                'justify-content' => 'center',
                'background' => '#E0E0E0',
                'width' => '100px',
                'height' => '40px',
                'font-size' => '18px',
                'border-radius' => '8px 0 0 8px',
            ],
            'class' => 'btn btn-lg btn-default',
            'title' => 'Проектанты',
        ]);?>

        <?= Html::a( 'Проекты - '.$client->countProjects, Url::to(['/admin/projects/client', 'id' => $client->id]), [
            'style' => [
                'display' => 'flex',
                'align-items' => 'center',
                'justify-content' => 'center',
                'background' => '#E0E0E0',
                'width' => '120px',
                'height' => '40px',
                'font-size' => '18px',
                'border-radius' => '0 8px 8px 0',
            ],
            'class' => 'btn btn-lg btn-default',
        ]);?>

    </div>

    <div class="col-md-3">
        <div class="row">
            <div class="col-md-7 text-center">-----</div>
            <div class="col-md-5 text-center">
                <div class="text-success">Активирован</div>
            </div>
        </div>
    </div>

</div>
