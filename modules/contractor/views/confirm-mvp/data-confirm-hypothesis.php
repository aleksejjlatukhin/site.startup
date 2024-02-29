<?php

use app\models\ConfirmGcp;
use app\models\ConfirmSegment;
use app\models\Gcps;
use app\models\Mvps;
use app\models\Problems;
use app\models\Projects;
use app\models\QuestionsConfirmGcp;
use app\models\QuestionStatus;
use app\models\Segments;
use yii\helpers\Html;

/**
 * @var ConfirmGcp $model
 * @var Mvps $mvp
 * @var Gcps $gcp
 * @var Problems $problem
 * @var ConfirmSegment $confirmSegment
 * @var Segments $segment
 * @var Projects $project
 * @var QuestionsConfirmGcp[] $questions
 */

?>

<div class="block_export_link_hypothesis">

    <?= Html::a('<div style="margin-top: -15px;">Исходные данные подтверждения MVP' . '</div>', ['#'], [
        'class' => 'export_link_hypothesis', 'style' => ['cursor' => 'default'], 'onclick' => 'return false;'
    ]) ?>

</div>

<div class="row container-fluid" style="color: #4F4F4F;">

    <div class="row">
        <div class="col-md-12 bolder">Цель проекта</div>
        <div class="col-md-12"><?= $project->getPurposeProject() ?></div>
    </div>

    <div class="row">
        <div class="col-md-12 bolder">Приветствие в начале встречи</div>
        <div class="col-md-12"><?= $confirmSegment->getGreetingInterview() ?></div>
    </div>

    <div class="row">
        <div class="col-md-12 bolder">Информация о вас для респондентов</div>
        <div class="col-md-12"><?= $confirmSegment->getViewInterview() ?></div>
    </div>

    <div class="row">
        <div class="col-md-12 bolder">Причина и тема (что побудило) для проведения исследования</div>
        <div class="col-md-12"><?= $confirmSegment->getReasonInterview() ?></div>
    </div>

    <div class="row">
        <div class="col-md-12 bolder">Формулировка проблемы сегмента</div>
        <div class="col-md-12"><?= $problem->getDescription() ?></div>
    </div>

    <div class="row">
        <div class="col-md-12 bolder">Формулировка ценностного предложения</div>
        <div class="col-md-12"><?= $gcp->getDescription() ?></div>
    </div>

    <div class="row">
        <div class="col-md-12 bolder">Формулировка MVP, которое проверяем</div>
        <div class="col-md-12"><?= $mvp->getDescription() ?></div>
    </div>

    <div class="row">
        <div class="col-md-12 text-center">
            <h3>Вопросы для интервью:</h3>
        </div>
        <div class="col md-12">
            <div class="row container-fluid">
                <?php foreach ($questions as $i => $question): ?>
                    <div class="col-md-12" style="min-height: 30px;">

                        Вопрос <?= ($i+1) ?>: <span><?= $question->getTitle() ?></span>

                        <?php if ($question->getStatus() === QuestionStatus::STATUS_NOT_STAR) : ?>
                            <div class="star-passive" title="Значимость вопроса">
                                <div class="star"></div>
                            </div>
                        <?php elseif ($question->getStatus() === QuestionStatus::STATUS_ONE_STAR) : ?>
                            <div class="star-passive" title="Значимость вопроса">
                                <div class="star active"></div>
                            </div>
                        <?php endif; ?>

                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

</div>
