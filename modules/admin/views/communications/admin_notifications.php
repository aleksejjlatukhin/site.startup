<?php

use yii\helpers\Html;
use app\models\CommunicationTypes;
use app\modules\expert\models\ConversationExpert;
use yii\helpers\Url;
use app\models\TypesDuplicateCommunication;

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

                    <div class="col-xs-10">

                        <div>
                            <?= $communication->description;?>
                        </div>

                        <?php if ($communication->isNeedReadButton()) : ?>
                            <div class="read-notification">
                                Чтобы отметить уведомление как прочитанное, нажмите <?= Html::button('OK', [
                                    'id' => 'read_notification-'.$communication->id,
                                    'class' => 'btn btn-default link-read-duplicate-notification',
                                    'style' => ['border-radius' => '8px'],
                                ]);?>
                            </div>
                        <?php endif; ?>

                        <?php $source = $communication->source; ?>

                        <?php if ($source->type == CommunicationTypes::MAIN_ADMIN_APPOINTS_EXPERT_PROJECT && $communication->type == TypesDuplicateCommunication::MAIN_ADMIN_TO_EXPERT) : ?>

                            <div class="conversation-exist">

                                <?php $admin = $source->project->user->admin; ?>

                                <?php if (ConversationExpert::isExist($source->expert->id, $admin->id)) : ?>

                                    <div>В сообщениях создана беседа с экспертом.</div>

                                <?php else : ?>

                                    <div>
                                        Чтобы создать беседу с экспертом нажмите
                                        <?= Html::a('OK',
                                            Url::to([
                                                '/admin/message/create-expert-conversation',
                                                'user_id' => $admin->id,
                                                'expert_id' => $source->expert->id
                                            ]), [
                                                'id' => 'create_conversation-'.$source->id,
                                                'class' => 'btn btn-default link-create-conversation',
                                                'style' => ['border-radius' => '8px']
                                            ]);?>
                                    </div>

                                <?php endif; ?>
                            </div>

                        <?php endif; ?>

                    </div>

                    <div class="col-xs-2 text-center">
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
<?php $this->registerJsFile('@web/js/admin_notifications.js'); ?>
