<?php

use app\models\DuplicateCommunications;
use yii\helpers\Html;

$this->title = 'Уведомления';
$this->registerCssFile('@web/css/notifications-style.css');

/**
 * @var DuplicateCommunications[] $communications
 */

?>

<div class="row page-notifications">

    <div class="col-md-12 notifications-content">

        <?php if ($communications) : ?>

            <!--Заголовки для списка уведомлений по проекту-->
            <div class="row headers_data_notifications">
                <div class="col-xs-10">Уведомления</div>
                <div class="col-xs-2">Дата и время</div>
            </div>

            <?php foreach ($communications as $key => $communication) : ?>

                <div class="row line_data_notifications">

                    <div class="col-xs-10">

                        <div>
                            <?= $communication->getDescription() ?>
                        </div>

                        <?php if ($communication->isNeedReadButton()) : ?>
                            <div class="read-notification">
                                Чтобы отметить уведомление как прочитанное, нажмите <?= Html::button('OK', [
                                    'id' => 'read_notification-'.$communication->getId(),
                                    'class' => 'btn btn-default link-read-notification',
                                    'style' => ['border-radius' => '8px'],
                                ]) ?>
                            </div>
                        <?php endif; ?>

                    </div>

                    <div class="col-xs-2 text-center">
                        <?= date('d.m.Y H:i',$communication->getCreatedAt()) ?>
                    </div>

                </div>

            <?php endforeach; ?>

        <?php else : ?>

            <h3 class="text-center">У вас пока нет уведомлений...</h3>

        <?php endif; ?>

    </div>
</div>


<!--Подключение скриптов-->
<?php $this->registerJsFile('@web/js/user_notifications.js'); ?>