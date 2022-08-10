<?php

$this->title = 'Админка | Профиль организации';
$this->registerCssFile('@web/css/profile-style.css');

use app\models\Client;
use app\models\ClientActivation;
use app\models\ClientRatesPlan;
use app\models\ClientSettings;
use yii\helpers\Html;

/**
 * @var Client $client
 * @var ClientSettings $clientSettings
 */

?>


<div class="client-profile">

    <div class="row profile_menu" style="height: 51px;">

    </div>

    <div class="data_client_content">

        <div class="col-md-12 col-lg-4">

            <?php if ($clientSettings->getAvatarImage()) : ?>
                <?= Html::img('/web/upload/company-'.$client->getId().'/avatar/'.$clientSettings->getAvatarImage(), ['class' => 'avatar_image']) ?>
            <?php else : ?>
                <?= Html::img('/images/avatar/default.jpg',['class' => 'avatar_image']) ?>
            <?php endif; ?>

        </div>
        
        <div class="col-md-12 col-lg-8 info_client_content">

            <div class="row">

                <div class="col-lg-4"><label style="padding-left: 10px;">Дата регистрации в Spaccel:</label><span style="padding-left: 10px;"><?= date('d.m.Y', $client->getCreatedAt()) ?></span></div>

                <div class="col-lg-4">
                    <label style="padding-left: 10px;">Тариф:</label>
                    <span style="padding-left: 10px;">
                        <?php
                        /** @var ClientRatesPlan $lastClientRatesPlan */
                        if ($lastClientRatesPlan = $client->findLastClientRatesPlan()) : ?>
                            <?= $lastClientRatesPlan->ratesPlan->getName() ?>
                        <?php else : ?>
                            Не установлен
                        <?php endif; ?>
                    </span></div>

                <div class="col-lg-4"><label style="padding-left: 10px;">Статус:</label>

                    <?php
                    /** @var ClientActivation $clientActivation */
                    $clientActivation = $client->findClientActivation();
                    if ($clientActivation->getStatus() === ClientActivation::ACTIVE) : ?>
                        <span style="padding-left: 10px;">Активирована</span>
                    <?php elseif ($clientActivation->getStatus() === ClientActivation::NO_ACTIVE) : ?>
                        <span style="padding-left: 10px;">Заблокирована</span>
                    <?php endif; ?>

                </div>

            </div>

            <div class="row">

                <div class="col-md-12" style="padding-top: 10px; padding-left: 25px;">
                    <label>Наименование организации:</label>
                    <div><?= $client->getName() ?></div>
                </div>

                <div class="col-md-12" style="padding-top: 10px; padding-left: 25px;">
                    <label>Полное наименование организации:</label>
                    <div><?= $client->getFullname() ?></div>
                </div>

                <div class="col-md-12" style="padding-top: 10px; padding-left: 25px;">
                    <label>Город, в котором находится организация:</label>
                    <div><?= $client->getCity() ?></div>
                </div>

                <div class="col-md-12" style="padding-top: 10px; padding-left: 25px;">
                    <label>Описание организации:</label>
                    <div><?= $client->getDescription() ?></div>
                </div>

                <div class="col-md-12" style="padding-top: 10px; padding-left: 25px;">
                    <?php $clientSettings->getAccessAdmin() === ClientSettings::ACCESS_ADMIN_TRUE ? $accessAdmin = 'Доступ разрешен' : $accessAdmin = 'Доступ запрещен'; ?>
                    <label>Доступ к данным организации:</label>
                    <div><?= $accessAdmin ?></div>
                </div>

            </div>
        </div>
    </div>
</div>

