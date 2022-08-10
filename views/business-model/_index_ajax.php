<?php

use app\models\BusinessModel;
use app\models\Gcps;
use app\models\ProjectCommunications;
use yii\helpers\Html;
use app\models\Segments;
use app\models\User;
use app\models\EnableExpertise;
use app\models\StageExpertise;

/**
 * @var BusinessModel $model
 * @var Gcps $gcp
 * @var Segments $segment
 */

?>


<div class="row" style="margin: 0;">

    <div class="col-lg-3" style="padding-top: 17px; padding-bottom: 17px;">
        <?= Html::a('Бизнес-модель' . Html::img('/images/icons/icon_report_next.png'), ['/business-model/get-instruction'],[
            'class' => 'link_to_instruction_page open_modal_instruction_page', 'title' => 'Инструкция'
        ]) ?>
    </div>

    <div class="col-lg-9" style="padding-top: 17px; padding-bottom: 17px;">

        <?= Html::a('Скачать', ['/business-model/mpdf-business-model', 'id' => $model->id],[
            'class' => 'btn btn-default pull-right',
            'title' => 'Скачать бизнес-модель',
            'target' => '_blank',
            'style' => [
                'color' => '#FFFFFF',
                'background' => '#669999',
                'padding' => '0 7px',
                'width' => '190px',
                'height' => '40px',
                'font-size' => '24px',
                'border-radius' => '8px',
            ],
        ]) ?>

        <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

            <?= Html::a('Редактировать', ['/business-model/get-hypothesis-to-update', 'id' => $model->getId()], [
                'class' => 'btn btn-default update-hypothesis pull-right',
                'title' => 'Редактировать бизнес-модель',
                'style' => [
                    'color' => '#FFFFFF',
                    'background' => '#C2C2C2',
                    'padding' => '0 7px',
                    'width' => '190px',
                    'height' => '40px',
                    'font-size' => '24px',
                    'border-radius' => '8px',
                ],
            ]) ?>

            <?php if ($model->getEnableExpertise() === EnableExpertise::OFF) : ?>

                <?=  Html::a( 'Экспертиза',
                    ['/business-model/enable-expertise', 'id' => $model->getId()], [
                        'class' => 'btn btn-warning link-enable-expertise pull-right',
                        'title' => 'Разрешить экспертизу',
                        'style' => [
                            'padding' => '0 7px',
                            'width' => '190px',
                            'height' => '40px',
                            'font-size' => '24px',
                            'border-radius' => '8px',
                        ]
                    ]) ?>

            <?php elseif ($model->getEnableExpertise() === EnableExpertise::ON) : ?>

                <?=  Html::a( 'Экспертиза',
                    ['/expertise/get-list', 'stage' => StageExpertise::getList()[StageExpertise::BUSINESS_MODEL], 'stageId' => $model->getId()], [
                        'class' => 'btn btn-default link-get-list-expertise pull-right',
                        'title' => 'Смотреть экспертизу',
                        'style' => [
                            'color' => '#FFFFFF',
                            'background' => '#52BE7F',
                            'padding' => '0 7px',
                            'width' => '190px',
                            'height' => '40px',
                            'font-size' => '24px',
                            'border-radius' => '8px',
                        ],
                    ]) ?>

            <?php endif; ?>

        <?php elseif (User::isUserExpert(Yii::$app->user->identity['username'])) : ?>

            <?php if ($model->getEnableExpertise() === EnableExpertise::OFF) : ?>

                <?=  Html::a( 'Экспертиза', ['#'], [
                    'onclick' => 'return false;',
                    'class' => 'btn btn-default pull-right',
                    'title' => 'Экспертиза не разрешена',
                    'style' => [
                        'color' => '#FFFFFF',
                        'background' => '#f5a4a4',
                        'padding' => '0 7px',
                        'width' => '190px',
                        'height' => '40px',
                        'font-size' => '24px',
                        'border-radius' => '8px',
                    ]
                ]) ?>

            <?php elseif ($model->getEnableExpertise() === EnableExpertise::ON && ProjectCommunications::checkOfAccessToCarryingExpertise(Yii::$app->user->getId(), $model->getProjectId())) : ?>

                <?=  Html::a( 'Экспертиза',
                    ['/expertise/get-list', 'stage' => StageExpertise::getList()[StageExpertise::BUSINESS_MODEL], 'stageId' => $model->getId()], [
                        'class' => 'btn btn-default link-get-list-expertise pull-right',
                        'title' => 'Экспертиза',
                        'style' => [
                            'color' => '#FFFFFF',
                            'background' => '#52BE7F',
                            'padding' => '0 7px',
                            'width' => '190px',
                            'height' => '40px',
                            'font-size' => '24px',
                            'border-radius' => '8px',
                        ]
                    ]) ?>

            <?php endif; ?>

        <?php else : ?>

            <?php if ($model->getEnableExpertise() === EnableExpertise::OFF) : ?>

                <?=  Html::a( 'Экспертиза', ['#'], [
                    'onclick' => 'return false;',
                    'class' => 'btn btn-default pull-right',
                    'title' => 'Экспертиза не разрешена',
                    'style' => [
                        'color' => '#FFFFFF',
                        'background' => '#f5a4a4',
                        'padding' => '0 7px',
                        'width' => '190px',
                        'height' => '40px',
                        'font-size' => '24px',
                        'border-radius' => '8px',
                    ]
                ]) ?>

            <?php elseif ($model->getEnableExpertise() === EnableExpertise::ON) : ?>

                <?=  Html::a( 'Экспертиза',
                    ['/expertise/get-list', 'stage' => StageExpertise::getList()[StageExpertise::BUSINESS_MODEL], 'stageId' => $model->getId()], [
                        'class' => 'btn btn-default link-get-list-expertise pull-right',
                        'title' => 'Экспертиза',
                        'style' => [
                            'color' => '#FFFFFF',
                            'background' => '#52BE7F',
                            'padding' => '0 7px',
                            'width' => '190px',
                            'height' => '40px',
                            'font-size' => '24px',
                            'border-radius' => '8px',
                        ]
                    ]) ?>

            <?php endif; ?>

        <?php endif; ?>

    </div>

</div>

<div class="blocks_business_model">

    <div class="block_20_business_model">

        <div class="desc_block_20">
            <h5>Ключевые партнеры</h5>
            <div><?= $model->getPartners() ?></div>
        </div>

    </div>

    <div class="block_20_business_model">

        <div class="desc_block_20">

            <h5>Ключевые направления</h5>

            <div class="mini_header_desc_block">Тип взаимодейстивия с рынком:</div>
            <?php
            if ($segment->getTypeOfInteractionBetweenSubjects() === Segments::TYPE_B2C) {
                echo 'В2С (бизнес-клиент)';
            } else {
                echo 'B2B (бизнес-бизнес)';
            }
            ?>

            <div class="mini_header_desc_block">Сфера деятельности:</div>
            <?= $segment->getFieldOfActivity() ?>

            <div class="mini_header_desc_block">Вид / специализация деятельности:</div>
            <?= $segment->getSortOfActivity() ?>

        </div>

        <div class="desc_block_20">
            <h5>Ключевые ресурсы</h5>
            <div><?= $model->getResources() ?></div>
        </div>

    </div>

    <div class="block_20_business_model">

        <div class="desc_block_20">
            <h5>Ценностное предложение</h5>
            <?= $gcp->getDescription() ?>
        </div>

    </div>

    <div class="block_20_business_model">

        <div class="desc_block_20">
            <h5>Взаимоотношения с клиентами</h5>
            <div><?= $model->getRelations() ?></div>
        </div>

        <div class="desc_block_20">
            <h5>Каналы коммуникации и сбыта</h5>
            <div><?= $model->getDistributionOfSales() ?></div>
        </div>

    </div>

    <div class="block_20_business_model">

        <div class="desc_block_20">

            <h5>Потребительский сегмент</h5>

            <div class="mini_header_desc_block">Наименование:</div>
            <?= $segment->getName() ?>

            <div class="mini_header_desc_block">Краткое описание:</div>
            <?= $segment->getDescription() ?>

            <div class="mini_header_desc_block">Потенциальное количество потребителей:</div>
            <?= ' от ' . number_format($segment->getQuantityFrom() * 1000, 0, '', ' ') .
            ' до ' . number_format($segment->getQuantityTo() * 1000, 0, '', ' ') . ' человек' ?>

            <div class="mini_header_desc_block">Объем рынка:</div>
            <?= number_format($segment->getMarketVolume() * 1000000, 0, '', ' ') . ' рублей' ?>

        </div>
    </div>

</div>

<div class="blocks_business_model">

    <div class="block_50_business_model">

        <div class="desc_block_50">
            <h5>Структура издержек</h5>
            <div><?= $model->getCost() ?></div>
        </div>

    </div>

    <div class="block_50_business_model">

        <div class="desc_block_50">
            <h5>Потоки поступления доходов</h5>
            <div><?= $model->getRevenue() ?></div>
        </div>

    </div>

</div>
