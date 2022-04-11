<?php

use app\models\ClientActivation;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="col-md-3 column-client-name" id="link_client_page-<?= $client->id;?>" title="Перейти на страницу организации">

    <?php if ($client->settings->getAvatarImage()) : ?>
        <?= Html::img('/web/upload/clients/client-'.$client->getId().'/avatar/'.$client->settings->getAvatarImage(), ['class' => 'user_picture']); ?>
    <?php else : ?>
        <?= Html::img('/images/avatar/client_default.png', ['class' => 'user_picture_default']); ?>
    <?php endif; ?>

    <div class="block-name-and-fullname">
        <div class="block-name"><?= $client->name; ?></div>
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

<div class="col-md-2 column-client-info-entities mt-10">

    <?php $manager = $client->findCustomerManager()->user; ?>
    <?php if ($manager) : ?>
        <?= Html::a($manager->second_name.' '.$manager->first_name.' '.$manager->middle_name, ['/admin/clients/get-list-managers', 'clientId' => $client->id], [
            'class' => 'btn btn-lg btn-default open_change_manager_modal',
            'style' => [
                'display' => 'flex',
                'align-items' => 'center',
                'justify-content' => 'center',
                'background' => '#E0E0E0',
                'width' => '200px',
                'border-radius' => '8px',
                'overflow' => 'hidden',
                'white-space' => 'normal',
                'font-size' => '16px'
            ],
        ])?>
    <?php else : ?>
        <?= Html::a('Назначить менеджера', ['/admin/clients/get-list-managers', 'clientId' => $client->id], [
            'class' => 'btn btn-lg btn-default open_change_manager_modal',
            'style' => [
                'display' => 'flex',
                'align-items' => 'center',
                'justify-content' => 'center',
                'background' => '#E0E0E0',
                'width' => '200px',
                'border-radius' => '8px',
                'overflow' => 'hidden',
                'white-space' => 'normal',
                'font-size' => '16px'
            ],
        ])?>
    <?php endif; ?>

</div>

<div class="col-md-2 column-client-info-entities mt-10">

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

<div class="col-md-2 column-client-info-entities mt-10">

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

    <?= Html::a( 'Проекты - '.$client->countProjects, Url::to(['/admin/projects/index', 'id' => $client->id]), [
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

<div class="col-md-3 mt-10">
    <div class="row">
        <div class="col-md-7 text-center">
            <?php if ($ratesPlan = $client->findLastClientRatesPlan()) : ?>
                <?= Html::a('<div title="'.$ratesPlan->findRatesPlan()->getName().'" style="overflow: hidden; width: inherit; padding: 2px 4px;">«' . $ratesPlan->findRatesPlan()->getName() . '»</div><div>' . date('d.m.y', $ratesPlan->getDateStart()) . ' по ' . date('d.m.y', $ratesPlan->getDateEnd()) . '</div>', ['/admin/rates-plans/get-list-rates-plans', 'clientId' => $client->id], [
                    'class' => 'btn btn-lg btn-default open_change_rates_plan_modal',
                    'style' => [
                        'display' => 'flex',
                        'flex-direction' => 'column',
                        'align-items' => 'center',
                        'justify-content' => 'center',
                        'margin-top' => '15px',
                        'margin-bottom' => '10px',
                        'width' => '200px',
                        'background' => '#E0E0E0',
                        'border-radius' => '8px',
                        'font-size' => '16px',
                    ]
                ])?>
            <?php else : ?>
                <?= Html::a('Выбрать тарифный план', ['/admin/rates-plans/get-list-rates-plans', 'clientId' => $client->id], [
                    'class' => 'btn btn-lg btn-default open_change_rates_plan_modal',
                    'style' => [
                        'display' => 'flex',
                        'align-items' => 'center',
                        'justify-content' => 'center',
                        'margin-top' => '15px',
                        'margin-bottom' => '10px',
                        'width' => '200px',
                        'height' => '40px',
                        'background' => '#E0E0E0',
                        'border-radius' => '8px',
                        'font-size' => '16px',
                    ]
                ])?>
            <?php endif; ?>
        </div>
        <div class="col-md-5 text-center">
            <?php if ($client->findClientActivation()->getStatus() == ClientActivation::ACTIVE) : ?>
                <?= Html::a('Заблокировать', ['/admin/clients/change-status', 'clientId' => $client->id], [
                    'class' => 'btn btn-lg btn-danger change_status_client',
                    'style' => [
                        'display' => 'flex',
                        'align-items' => 'center',
                        'justify-content' => 'center',
                        'margin-top' => '15px',
                        'margin-bottom' => '10px',
                        'width' => '140px',
                        'height' => '40px',
                        'background' => '#d9534f',
                        'border-radius' => '8px',
                        'font-size' => '16px',
                    ]
                ])?>
            <?php else : ?>
                <?php if ($client->checkingReadinessActivation()) : ?>
                    <?= Html::a('Активировать', ['/admin/clients/change-status', 'clientId' => $client->id], [
                        'class' => 'btn btn-lg btn-success change_status_client',
                        'style' => [
                            'display' => 'flex',
                            'align-items' => 'center',
                            'justify-content' => 'center',
                            'margin-top' => '15px',
                            'margin-bottom' => '10px',
                            'width' => '140px',
                            'height' => '40px',
                            'background' => '#52BE7F',
                            'border-radius' => '8px',
                            'font-size' => '16px',
                        ]
                    ])?>
                <?php else : ?>
                    <?= Html::a('Активировать', ['#'], [
                        'class' => 'btn btn-lg btn-success',
                        'title' => 'Необходимо назначить менеджера и выбрать тарифный план',
                        'onclick' => 'return false;',
                        'disabled' => true,
                        'style' => [
                            'display' => 'flex',
                            'align-items' => 'center',
                            'justify-content' => 'center',
                            'margin-top' => '15px',
                            'margin-bottom' => '10px',
                            'width' => '140px',
                            'height' => '40px',
                            'background' => '#52BE7F',
                            'border-radius' => '8px',
                            'font-size' => '16px',
                        ]
                    ])?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
