<?php

use yii\helpers\Html;
use app\models\StageConfirm;
use app\models\QuestionStatus;

?>

<div class="row" style="margin-bottom: 15px; margin-top: 15px; color: #4F4F4F;">

    <div class="col-md-12" style="padding: 0 20px;">
        <div style="font-weight: 700;">Респондент</div>
        <div><?= $respond->name; ?></div>
    </div>

    <div class="col-md-12" style="padding: 0 20px; font-size: 24px; margin-top: 5px;">
        <div style="border-bottom: 1px solid #ccc;">Ответы на вопросы интервью</div>
    </div>

    <?php foreach ($respond->answers as $index => $answer) : ?>

        <div class="col-md-12" style="padding: 0 20px; margin-top: 10px;">

            <?php if ($answer->question->status === QuestionStatus::STATUS_ONE_STAR) : ?>
                <div style="font-weight: 700; color: #52be7f;"><?= $answer->question->title; ?></div>
            <?php elseif($answer->question->status === QuestionStatus::STATUS_NOT_STAR) : ?>
                <div style="font-weight: 700;"><?= $answer->question->title; ?></div>

            <?php endif; ?>
            <div><?= $answer->answer; ?></div>
        </div>

    <?php endforeach; ?>

    <div class="col-md-12" style="padding: 0 20px; margin-bottom: 10px; margin-top: 10px;">
        <div style="font-weight: 700; border-top: 1px solid #ccc; padding-top: 10px;">Варианты проблем</div>
        <div><?= $interview->result; ?></div>
    </div>

    <div class="col-md-12">

        <p style="padding-left: 5px; font-weight: 700;">Приложенный файл</p>

        <?php if (!empty($interview->interview_file)) : ?>

            <div style="margin-top: -5px; margin-bottom: 30px;">

                <div style="display: flex; align-items: center;">

                    <?= Html::a('Скачать файл', ['/interviews/download', 'stage' => StageConfirm::STAGE_CONFIRM_SEGMENT, 'id' => $interview->id], [
                        'target' => '_blank',
                        'class' => "btn btn-default interview_file_view-$interview->id",
                        'style' => [
                            'display' => 'flex',
                            'align-items' => 'center',
                            'color' => '#FFFFFF',
                            'justify-content' => 'center',
                            'background' => '#707F99',
                            'width' => '170px',
                            'height' => '40px',
                            'text-align' => 'left',
                            'font-size' => '24px',
                            'border-radius' => '8px',
                            'margin-right' => '5px',
                        ]

                    ]);
                    ?>

                </div>

                <div class="title_name_update_form" style="padding-left: 5px; padding-top: 5px; margin-bottom: -10px;"><?= $interview->interview_file;?></div>

            </div>

        <?php endif;?>

        <?php if (empty($interview->interview_file)) : ?>

            <div class="col-md-12" style="padding-left: 5px; margin-bottom: 20px; margin-top: -10px;">Файл не выбран</div>

        <?php endif;?>

    </div>

</div>
