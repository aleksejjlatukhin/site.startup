<?php

use yii\helpers\Html;
use app\models\ExpertType;
use app\models\CommunicationTypes;
use app\models\ProjectCommunications;
use yii\helpers\Url;

?>

<!--Поиск экспертов-->
<?php if ($experts) : ?>

    <!--Заголовки для списка экспертов-->
    <div class="row headers_data_experts">

        <div class="col-md-3">Логин эксперта</div>

        <div class="col-md-3">Сфера профессиональной компетенции</div>

        <div class="col-md-3">Тип экпертной деятельности</div>

        <div class="col-md-2">Ключевые слова</div>

        <div class="col-md-1"></div>

    </div>

    <?php foreach ($experts as $expert) : ?>

        <div class="row container-one_user user_container_number-<?=$expert->id;?>">

            <div class="col-md-3 column-user-fio" id="link_user_profile-<?= $expert->id;?>" title="Перейти в профиль эксперта">

                <!--Проверка существования аватарки-->
                <?php if ($expert->avatar_image) : ?>
                    <?= Html::img('/web/upload/user-'.$expert->id.'/avatar/'.$expert->avatar_image, ['class' => 'user_picture']); ?>
                <?php else : ?>
                    <?= Html::img('/images/icons/button_user_menu.png', ['class' => 'user_picture_default']); ?>
                <?php endif; ?>

                <!--Проверка онлайн статуса-->
                <?php if ($expert->checkOnline === true) : ?>
                    <div class="checkStatusOnlineUser active"></div>
                <?php else : ?>
                    <div class="checkStatusOnlineUser"></div>
                <?php endif; ?>

                <div class="block-fio-and-date-last-visit">
                    <div class="block-fio"><?= $expert->username; ?></div>
                    <div class="block-date-last-visit">
                        <?php if($expert->checkOnline !== true && $expert->checkOnline !== false) : ?>
                            Пользователь был в сети <?= $expert->checkOnline;?>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            <div class="col-md-3 text_description" title="<?= $expert->expertInfo->scope_professional_competence; ?>">
                <?= $expert->expertInfo->scope_professional_competence; ?>
            </div>

            <div class="col-md-3 text_description" title="<?= ExpertType::getContent($expert->expertInfo->type); ?>">
                <?= ExpertType::getContent($expert->expertInfo->type); ?>
            </div>

            <div class="col-md-2 text_description" title="<?= $expert->keywords->description; ?>">
                <?= $expert->keywords->description; ?>
            </div>

            <div class="col-md-1">

                <div class="row pull-right">

                    <?php if (ProjectCommunications::isNeedAskExpert($expert->id, $project_id)) : ?>

                        <?= Html::a('Сделать запрос', Url::to([
                            '/client/communications/send',
                            'adressee_id' => $expert->id,
                            'project_id' => $project_id,
                            'type' => CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE
                        ]), [
                            'class' => 'btn btn-default send-communication',
                            'id' => 'send_communication-'.$expert->id,
                            'style' => [
                                'display' => 'flex',
                                'align-items' => 'center',
                                'justify-content' => 'center',
                                'color' => '#FFFFFF',
                                'background' => '#52BE7F',
                                'width' => '140px',
                                'height' => '40px',
                                'font-size' => '18px',
                                'border-radius' => '8px',
                            ]
                        ]); ?>

                    <?php else : ?>

                        <div class="text-success">Запрос сделан</div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    <?php endforeach; ?>

<?php else : ?>

    <h4 class="text-center">По вашему запросу не найдены эксперты...</h4>

<?php endif; ?>
