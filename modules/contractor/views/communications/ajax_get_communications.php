<?php

use app\models\ContractorCommunications;
use yii\helpers\Html;
use app\modules\contractor\models\form\FormCreateCommunicationResponse;
use app\models\ContractorCommunicationTypes;
use app\models\ContractorCommunicationResponse;

/**
 * @var ContractorCommunications[] $communications
 */

?>

<!--Заголовки для списка уведомлений по проекту-->
<div class="row headers_data_notifications">
    <div class="col-md-1">Дата и время</div>
    <div class="col-md-3">Руководитель проекта</div>
    <div class="col-md-6">Уведомление</div>
    <div class="col-md-1">Тип (статус)</div>
    <div class="col-md-1">Доступ к проекту</div>
</div>

<?php foreach ($communications as $communication) : ?>

    <div class="row line_data_notifications">
        <div class="col-md-1 text-center">
            <?= date('d.m.Y H:i',$communication->getCreatedAt()) ?>
        </div>
        <div class="col-md-3 text-center"><?= $communication->user->getUsername() ?></div>
        <div class="col-md-6">

            <div>
                <?= $communication->getDescription() ?>
            </div>

            <?php if ($communication->isNeedShowButtonAnswer()) : ?>
                <div class="notification-response">
                    Чтобы ответить на уведомление, нажмите <?= Html::button('ПРОДОЛЖИТЬ', [
                        'id' => 'notification_response-'.$communication->getId(),
                        'class' => 'btn btn-default link-notification-response',
                        'style' => ['border-radius' => '8px'],
                    ]) ?>
                </div>
            <?php endif; ?>

            <?php if ($communication->isNeedReadButton()) : ?>
                <div class="read-notification">
                    Чтобы отметить уведомление как прочитанное, нажмите <?= Html::button('OK', [
                        'id' => 'read_notification-'.$communication->getId(),
                        'class' => 'btn btn-default link-read-notification',
                        'style' => ['border-radius' => '8px'],
                    ]) ?>
                </div>
            <?php endif; ?>

            <?php if ($responsive = $communication->responsiveCommunication) : ?>
                <?php if ($responsive->getType() === ContractorCommunicationTypes::CONTRACTOR_ANSWERS_QUESTION_ABOUT_READINESS_TO_JOIN_PROJECT) : ?>
                    <div>
                        <b>Ответ: </b>
                        <?= FormCreateCommunicationResponse::getAnswers()[$responsive->communicationResponse->getAnswer()] ?>
                    </div>
                    <?php if ($responsive->communicationResponse->getAnswer() === ContractorCommunicationResponse::POSITIVE_RESPONSE) : ?>
                        <div>
                            <b>Вид деятельности: </b>
                            <?= $responsive->activity->getTitle() ?>
                        </div>
                    <?php endif; ?>
                    <div>
                        <b>Комментарий: </b>
                        <?= $responsive->communicationResponse->getComment() ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

        </div>
        <div class="col-md-1 text-center">
            <?= $communication->getNotificationStatus() ?: '-' ?>
        </div>
        <div class="col-md-1 text-center">
            <?= $communication->getAccessStatus() ?: '-' ?>
        </div>
    </div>

<?php endforeach; ?>
