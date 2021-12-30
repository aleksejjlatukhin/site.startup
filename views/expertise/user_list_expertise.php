<?php

use app\models\forms\expertise\FormExpertiseManyAnswer;
use app\models\forms\expertise\FormExpertiseSingleAnswer;
use app\models\ExpertType;


?>

<div class="" style="margin-bottom: 20px;">

    <?php if ($data) : ?>

        <?php foreach ($data as $item) : ?>

            <div class="row container-fluid" style="margin: 5px -10px; background: #E0E0E0; border-radius: 12px;">

                <div class="row container-fluid" style="border-bottom: 1px solid #ccc; padding-bottom: 15px; padding-top: 15px;">
                    <div class="col-xs-8">
                        <div class="row">
                            <div class="col-xs-2 text-center"><?= date('d.m.y', $item['updated_at']); ?></div>
                            <div class="col-xs-10">Тип деятельности: <?= ExpertType::getContent($item['type']); ?></div>
                        </div>
                    </div>
                    <div class="col-xs-4">Эксперт: <?= $item['fio_expert']; ?></div>
                </div>

                <!--Заголовки для списка экспертиз-->
                <div class="row container-fluid bolder" style="padding-bottom: 10px; padding-top: 10px;">
                    <div class="col-xs-8">
                        <div class="row">
                            <div class="col-xs-2 text-center">Баллы</div>
                            <div class="col-xs-10">Оценка эксперта</div>
                        </div>
                    </div>
                    <div class="col-xs-4">Комментарий</div>
                </div>

                <div class="row container-fluid" style="border-bottom: 1px solid #ccc; padding-bottom: 10px;">

                    <?php if ($item['form'] instanceof FormExpertiseSingleAnswer) : ?>

                        <div class="col-xs-8">
                            <div class="row">
                                <div class="col-xs-2 text-center">
                                    <?= $item['general_estimation_by_one']; ?>
                                </div>
                                <div class="col-xs-10">
                                    <?php $arr = (array)$item['form']->getAnswerOptions(); echo $arr[$item['general_estimation_by_one']];?>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <?= $item['comment']; ?>
                        </div>

                    <?php elseif ($item['form'] instanceof FormExpertiseManyAnswer) : ?>

                        <div class="col-xs-8">

                            <div class="row">
                                <div class="col-xs-2"></div>
                                <div class="col-xs-10"><h4 class="bolder">Качество подготовки интервью</h4></div>
                            </div>

                            <?php foreach ((array)$item['form']->getAnswerOptions('preparation_interview_quality') as $i => $answerOption) : ?>
                                <div class="row">
                                    <div class="col-xs-2 text-center">
                                        <?= $item['form']->checkboxesPreparationInterviewQuality[$i][0]; ?>
                                    </div>
                                    <div class="col-xs-10">
                                        <?= $answerOption[$item['form']->checkboxesPreparationInterviewQuality[$i][0]]; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <div class="row">
                                <div class="col-xs-2"></div>
                                <div class="col-xs-10"><h4 class="bolder">Качество проведения интервью</h4></div>
                            </div>

                            <?php foreach ((array)$item['form']->getAnswerOptions('conducting_interview_quality') as $i => $answerOption) : ?>
                                <div class="row">
                                    <div class="col-xs-2 text-center">
                                        <?= $item['form']->checkboxesConductingInterviewQuality[$i][0]; ?>
                                    </div>
                                    <div class="col-xs-10">
                                        <?= $answerOption[$item['form']->checkboxesConductingInterviewQuality[$i][0]]; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                        <div class="col-xs-4">
                            <?= $item['comment']; ?>
                        </div>

                    <?php endif; ?>

                </div>

                <div class="row container-fluid" style="padding-bottom: 15px; padding-top: 15px;">
                    <div class="col-xs-12 bolder">Общий балл за экспертизу: <?= $item['general_estimation_by_one']; ?></div>
                </div>
            </div>

        <?php endforeach; ?>

    <?php else : ?>

        <h4 class="text-center bolder">По данному этапу проекта не проводилась экспертиза...</h4>

    <?php endif; ?>

</div>
