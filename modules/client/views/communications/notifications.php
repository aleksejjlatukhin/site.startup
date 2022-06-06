<?php

use yii\helpers\Html;
use app\modules\expert\models\form\FormCreateCommunicationResponse;
use app\models\ProjectCommunications;
use app\models\CommunicationResponse;
use app\models\CommunicationTypes;
use yii\helpers\Url;

$this->title = 'Уведомления';
$this->registerCssFile('@web/css/notifications-style.css');

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

                    <!--Коммуникации по проекту с экспертом ProjectCommunications (ответ эксперта) -->

                    <?php $communicationResponse = $communication->communicationResponse; ?>

                    <div class="col-md-2 bolder">
                        <?= $communication->expert->username; ?>
                    </div>

                    <div class="col-md-3">
                        <?= FormCreateCommunicationResponse::getAnswers()[$communicationResponse->answer] . ' ' .
                        Html::a($communication->project->project_name, ['/projects/index', 'id' => $communication->project->user_id]); ?>
                    </div>

                    <div class="col-md-3">
                        <?php if ($communicationResponse->comment) : ?>
                            <b>Комментарий: </b><?= $communicationResponse->comment; ?>
                        <?php endif; ?>
                    </div>

                    <?php if ($communicationResponse->answer == CommunicationResponse::POSITIVE_RESPONSE) : ?>

                        <?php if ($responsiveCommunication = $communication->responsiveCommunication) : ?>

                            <?php if ($responsiveCommunication->type == CommunicationTypes::MAIN_ADMIN_APPOINTS_EXPERT_PROJECT) : ?>

                                <div class="col-md-2 text-success">Назначен(-а) на проект</div>

                            <?php elseif ($responsiveCommunication->type == CommunicationTypes::MAIN_ADMIN_DOES_NOT_APPOINTS_EXPERT_PROJECT) : ?>

                                <div class="col-md-2 text-danger">Отказано</div>

                            <?php endif; ?>

                        <?php else : ?>

                            <div class="col-md-2 response-action-to-communication">
                                <div class="row">
                                    <div class="col-md-6">
                                        <?= Html::a('Назначить', Url::to([
                                            '/client/communications/get-form-types-expert',
                                            'id' => $communication->id,
                                        ]), [
                                            'class' => 'btn btn-success get-form-types-expert',
                                            'id' => 'appoints_expert_project-'.$communication->id,
                                            'style' => [
                                                'background' => '#52BE7F',
                                                'min-width' => '100%',
                                                'font-size' => '18px',
                                                'border-radius' => '8px',
                                            ]
                                        ]); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= Html::a('Отказать', Url::to([
                                            '/client/communications/send',
                                            'adressee_id' => $communication->sender_id,
                                            'project_id' => $communication->project_id,
                                            'type' => CommunicationTypes::MAIN_ADMIN_DOES_NOT_APPOINTS_EXPERT_PROJECT,
                                            'triggered_communication_id' => $communication->id
                                        ]), [
                                            'class' => 'btn btn-danger send-communication',
                                            'id' => 'appoints_does_not_expert_project-'.$communication->project_id,
                                            'style' => [
                                                'min-width' => '100%',
                                                'font-size' => '18px',
                                                'border-radius' => '8px',
                                            ]
                                        ]); ?>
                                    </div>
                                </div>
                            </div>

                        <?php endif; ?>

                    <?php else : ?>

                        <?php if ($communication->status == ProjectCommunications::NO_READ) : ?>

                            <div class="col-md-2">
                                Чтобы отметить уведомление как прочитанное, нажмите
                                <?= Html::button('OK', [
                                    'id' => 'read_notification-'.$communication->id,
                                    'class' => 'btn btn-default link-read-notification',
                                    'style' => ['border-radius' => '8px'],
                                ]); ?>
                            </div>

                        <?php else : ?>

                            <div class="col-md-2 text-success">Прочитано</div>

                        <?php endif; ?>

                    <?php endif; ?>

                    <div class="col-md-2 text-center">
                        <?= date('d.m.Y H:i',$communication->created_at); ?>
                    </div>

                </div>

            <?php endforeach; ?>

            <div class="pagination-admin-projects-result">
                <?= \yii\widgets\LinkPager::widget([
                    'pagination' => $pages,
                    'activePageCssClass' => 'pagination_active_page',
                    'options' => ['class' => 'admin-projects-result-pagin-list'],
                ]); ?>
            </div>

        <?php else : ?>

            <h3 class="text-center">У вас пока нет уведомлений...</h3>

        <?php endif; ?>

    </div>

</div>

<!--Подключение скриптов-->
<?php $this->registerJsFile('@web/js/main_admin_notifications.js'); ?>
