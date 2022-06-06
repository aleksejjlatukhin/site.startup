<?php

use app\models\CommunicationTypes;
use yii\helpers\Html;
use app\modules\expert\models\form\FormCreateCommunicationResponse;
use yii\helpers\Url;
use app\models\CommunicationResponse;
use app\models\ExpertType;

?>


<?php if ($admittedExperts) : ?>

    <!--Заголовки для списка коммуникаций по проекту-->
    <div class="row headers_data_communications">
        <div class="col-md-2">Логин эксперта</div>
        <div class="col-md-2">Запрос на готовность провести экспертизу</div>
        <div class="col-md-1">Дата, время</div>
        <div class="col-md-3">Ответ на запрос</div>
        <div class="col-md-1">Дата, время</div>
        <div class="col-md-2">Назначение на проект</div>
        <div class="col-md-1">Дата, время</div>
    </div>

    <?php foreach ($admittedExperts as $admittedExpert) : ?>

        <div class="row line_data_communication">

            <?php foreach ($userCommunications = $admittedExpert->userCommunicationsForAdminTable as $key => $communication) : ?>

                <div class="row">

                    <?php if ($key == 0) : ?>
                        <div class="col-md-2 text-center">
                            <?= $admittedExpert->user->username; ?>
                        </div>
                    <?php else : ?>
                        <div class="col-md-2"></div>
                    <?php endif; ?>

                    <div class="col-md-3">

                        <div class="row">
                            <div class="col-md-8">

                                <?php if ($communication->type == CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE) : ?>

                                    <div class="text-success">Запрос сделан</div>
                                    <div class="">Доступ к проекту до:</div>
                                    <div class=""><?= date('d.m.y H:i', $communication->userAccessToProject->date_stop); ?></div>

                                    <?php if ($key == array_key_last($userCommunications)) : ?>

                                        <div class="revoke-request-button">
                                            <?= Html::a('Отозвать запрос', Url::to([
                                                '/admin/communications/send',
                                                'adressee_id' => $communication->expert->id,
                                                'project_id' => $communication->project_id,
                                                'type' => CommunicationTypes::MAIN_ADMIN_WITHDRAWS_REQUEST_ABOUT_READINESS_CONDUCT_EXPERTISE
                                            ]), [
                                                'class' => 'btn btn-danger send-communication',
                                                'id' => 'send_communication-'.$communication->expert->id,
                                                'style' => [
                                                    'display' => 'flex',
                                                    'align-items' => 'center',
                                                    'justify-content' => 'center',
                                                    'width' => '140px',
                                                    'font-size' => '18px',
                                                    'border-radius' => '8px',
                                                ]
                                            ]); ?>
                                        </div>

                                    <?php endif; ?>

                                <?php elseif ($communication->type == CommunicationTypes::MAIN_ADMIN_WITHDRAWS_REQUEST_ABOUT_READINESS_CONDUCT_EXPERTISE) : ?>
                                    <div class="text-danger">Запрос отозван</div>
                                <?php endif; ?>

                            </div>

                            <div class="col-md-4">
                                <?php if ($communication->type == CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE ||
                                    $communication->type == CommunicationTypes::MAIN_ADMIN_WITHDRAWS_REQUEST_ABOUT_READINESS_CONDUCT_EXPERTISE) : ?>
                                    <div><?= date('d.m.y H:i', $communication->created_at) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <?php if ($communication->sender_id != $admittedExpert->user_id) : ?>

                        <?php $communicationExpert = $communication->responsiveCommunication; ?>
                        <?php if ($communicationResponse = $communicationExpert->communicationResponse) : ?>

                            <div class="col-md-3">

                                <?php if ($communicationResponse->answer == CommunicationResponse::POSITIVE_RESPONSE) : ?>

                                    <div><b>Ответ: </b><span class="text-success"><?= FormCreateCommunicationResponse::getAnswers()[$communicationResponse->answer];?></span></div>

                                    <div><b>Типы деятельности: </b><?= ExpertType::getContent($communicationResponse->expert_types)?></div>

                                    <?php if ($communicationResponse->comment) : ?>
                                        <div><b>Комментарий: </b><?= $communicationResponse->comment; ?></div>
                                    <?php endif; ?>

                                <?php elseif ($communicationResponse->answer == CommunicationResponse::NEGATIVE_RESPONSE) : ?>

                                    <div><b>Ответ: </b><span class="text-danger"><?= FormCreateCommunicationResponse::getAnswers()[$communicationResponse->answer];?></span></div>

                                    <?php if ($communicationResponse->comment) : ?>
                                        <div><b>Комментарий: </b><?= $communicationResponse->comment; ?></div>
                                    <?php endif; ?>

                                <?php endif; ?>

                            </div>

                            <div class="col-md-1">
                                <?= date('d.m.Y H:i', $communicationExpert->created_at); ?>
                            </div>

                            <?php if ($communicationResponse->answer == CommunicationResponse::POSITIVE_RESPONSE) : ?>

                                <?php if ($responsiveCommunication = $communicationExpert->responsiveCommunication) : ?>

                                    <?php if ($communicationWithdrawFromProject = $responsiveCommunication->responsiveCommunication) : ?>

                                        <div class="col-md-2 text-danger">Отозван(-а) с проекта</div>
                                        <div class="col-md-1"><?= date('d.m.Y H:i', $communicationWithdrawFromProject->created_at); ?></div>

                                    <?php else : ?>

                                        <?php if ($responsiveCommunication->type == CommunicationTypes::MAIN_ADMIN_APPOINTS_EXPERT_PROJECT) : ?>

                                            <div class="col-md-2">
                                                <div class="text-success">Назначен(-а) на проект</div>
                                                <div><b>Типы деятельности: </b><?= ExpertType::getContent($responsiveCommunication->typesAccessToExpertise->types);?></div>
                                                <div>
                                                    <?= Html::a('Отозвать эксперта', Url::to([
                                                        '/admin/communications/send',
                                                        'adressee_id' => $communicationExpert->sender_id,
                                                        'project_id' => $communicationExpert->project_id,
                                                        'type' => CommunicationTypes::MAIN_ADMIN_WITHDRAWS_EXPERT_FROM_PROJECT,
                                                        'triggered_communication_id' => $responsiveCommunication->id
                                                    ]), [
                                                        'class' => 'btn btn-danger send-communication',
                                                        'id' => 'withdraws_expert_from_project-'.$communicationExpert->project_id,
                                                        'style' => [
                                                            'width' => '160px',
                                                            'font-size' => '18px',
                                                            'border-radius' => '8px',
                                                            'margin-top' => '10px'
                                                        ]
                                                    ]); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-1"><?= date('d.m.Y H:i', $responsiveCommunication->created_at); ?></div>

                                        <?php elseif ($responsiveCommunication->type == CommunicationTypes::MAIN_ADMIN_DOES_NOT_APPOINTS_EXPERT_PROJECT) : ?>

                                            <div class="col-md-2 text-danger">Отказано</div>
                                            <div class="col-md-1"><?= date('d.m.Y H:i', $responsiveCommunication->created_at); ?></div>

                                        <?php endif; ?>

                                    <?php endif; ?>

                                <?php else : ?>

                                    <div class="col-md-2">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?= Html::a('Назначить', Url::to([
                                                    '/admin/communications/get-form-types-expert',
                                                    'id' => $communicationExpert->id,
                                                ]), [
                                                    'class' => 'btn btn-success get-form-types-expert',
                                                    'style' => [
                                                        'background' => '#52BE7F',
                                                        'width' => '140px',
                                                        'font-size' => '18px',
                                                        'border-radius' => '8px',
                                                        'margin-top' => '5px'
                                                    ]
                                                ]); ?>
                                            </div>
                                            <div class="col-md-12">
                                                <?= Html::a('Отказать', Url::to([
                                                    '/admin/communications/send',
                                                    'adressee_id' => $communicationExpert->sender_id,
                                                    'project_id' => $communicationExpert->project_id,
                                                    'type' => CommunicationTypes::MAIN_ADMIN_DOES_NOT_APPOINTS_EXPERT_PROJECT,
                                                    'triggered_communication_id' => $communicationExpert->id
                                                ]), [
                                                    'class' => 'btn btn-danger send-communication',
                                                    'id' => 'appoints_does_not_expert_project-'.$communicationExpert->project_id,
                                                    'style' => [
                                                        'width' => '140px',
                                                        'font-size' => '18px',
                                                        'border-radius' => '8px',
                                                        'margin-top' => '10px'
                                                    ]
                                                ]); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-1">-----</div>

                                <?php endif; ?>

                            <?php else : ?>
                                <div class="col-md-2">-----</div>
                                <div class="col-md-1">-----</div>
                            <?php endif; ?>

                        <?php else : ?>
                            <div class="col-md-3">-----</div>
                            <div class="col-md-1">-----</div>
                            <div class="col-md-2">-----</div>
                            <div class="col-md-1">-----</div>
                        <?php endif; ?>

                    <?php endif; ?>

                </div>

                <?php if ($key != array_key_last($userCommunications)) : ?>
                    <br>
                <?php endif; ?>

            <?php endforeach; ?>
        </div>

    <?php endforeach; ?>

<?php else : ?>

    <h4 class="text-center" style="margin: 30px;">Коммуникации по данному проекту не найдены...</h4>

<?php endif; ?>