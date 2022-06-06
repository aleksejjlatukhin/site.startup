<?php

use yii\helpers\Html;
use app\modules\expert\models\form\FormCreateCommunicationResponse;
use app\models\CommunicationTypes;
use app\modules\expert\models\ConversationExpert;
use yii\helpers\Url;
use app\models\ExpertType;
use app\models\CommunicationResponse;

?>

<!--Заголовки для списка уведомлений по проекту-->
<div class="row headers_data_notifications">
    <div class="col-md-1">Дата и время</div>
    <div class="col-md-9">Уведомление</div>
    <div class="col-md-1">Тип (статус)</div>
    <div class="col-md-1">Доступ к проекту</div>
</div>

<?php foreach ($communications as $communication) : ?>

    <div class="row line_data_notifications">
        <div class="col-md-1 text-center">
            <?= date('d.m.Y H:i',$communication->created_at); ?>
        </div>
        <div class="col-md-9">

            <div>
                <?= $communication->descriptionPattern; ?>
            </div>

            <?php if ($communication->isNeedShowButtonAnswer()) : ?>
                <div class="notification-response">
                    Чтобы ответить на уведомление, нажмите <?= Html::button('ПРОДОЛЖИТЬ', [
                        'id' => 'notification_response-'.$communication->id,
                        'class' => 'btn btn-default link-notification-response',
                        'style' => ['border-radius' => '8px'],
                    ]);?>
                </div>
            <?php endif; ?>

            <?php if ($communication->isNeedReadButton()) : ?>
                <div class="read-notification">
                    Чтобы отметить уведомление как прочитанное, нажмите <?= Html::button('OK', [
                        'id' => 'read_notification-'.$communication->id,
                        'class' => 'btn btn-default link-read-notification',
                        'style' => ['border-radius' => '8px'],
                    ]);?>
                </div>
            <?php endif; ?>

            <?php if ($responsive = $communication->responsiveCommunication) : ?>
                <?php if ($responsive->type == CommunicationTypes::EXPERT_ANSWERS_QUESTION_ABOUT_READINESS_CONDUCT_EXPERTISE) : ?>
                    <div>
                        <b>Ответ: </b>
                        <?= FormCreateCommunicationResponse::getAnswers()[$responsive->communicationResponse->answer]; ?>
                    </div>
                    <?php if ($responsive->communicationResponse->answer == CommunicationResponse::POSITIVE_RESPONSE) : ?>
                        <div>
                            <b>Указанные типы экпертной деятельности: </b>
                            <?= ExpertType::getContent($responsive->communicationResponse->expert_types); ?>
                        </div>
                    <?php endif; ?>
                    <div>
                        <b>Комментарий: </b>
                        <?= $responsive->communicationResponse->comment; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>


            <?php if ($communication->type == CommunicationTypes::MAIN_ADMIN_APPOINTS_EXPERT_PROJECT) : ?>

                <div class="conversation-exist">

                    <?php $admin = $communication->project->user->admin; ?>

                    Трекер проекта:
                    <span class="bolder">
                        <?= $admin->username; ?>
                    </span>

                    <?php if (ConversationExpert::isExist($communication->expert->id, $admin->id)) : ?>

                        <div>В сообщениях создана беседа с трекером.</div>

                    <?php else : ?>

                        <div>
                            Чтобы создать беседу с трекером нажмите
                            <?= Html::a('OK',
                                Url::to([
                                    '/expert/message/create-expert-conversation',
                                    'user_id' => $admin->id,
                                    'expert_id' => $communication->expert->id
                                ]), [
                                    'id' => 'create_conversation-'.$communication->id,
                                    'class' => 'btn btn-default link-create-conversation',
                                    'style' => ['border-radius' => '8px']
                                ]);?>
                        </div>

                    <?php endif; ?>
                </div>

            <?php endif; ?>

        </div>
        <div class="col-md-1 text-center">
            <?= $communication->notificationStatus; ?>
        </div>
        <div class="col-md-1 text-center">
            <?= $communication->accessStatus; ?>
        </div>
    </div>

<?php endforeach; ?>
