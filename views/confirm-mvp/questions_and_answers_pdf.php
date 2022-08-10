<?php

use app\models\AnswersQuestionsConfirmMvp;
use app\models\QuestionsConfirmMvp;
use app\models\QuestionStatus;
use yii\helpers\Html;

/**
 * @var QuestionsConfirmMvp[] $questions
 * @var AnswersQuestionsConfirmMvp $answer
 */

?>

<!--Ответы респондентов на вопросы интервью-->
<?php foreach ($questions as $i => $question) : ?>

    <table>

        <tr>
            <td colspan="2" style="color: #ffffff; background: #707F99; font-size: 18px; margin: 2px 0; padding: 10px;">

                Вопрос <?= ($i+1) ?>: <?= $question->getTitle() ?>

                <?php if ($question->getStatus() === QuestionStatus::STATUS_NOT_STAR) : ?>
                    <?= Html::img('/web/images/icons/icon_gray_star.png', ['style' => ['width' => '20px']]) ?>
                <?php elseif ($question->getStatus() === QuestionStatus::STATUS_ONE_STAR) : ?>
                    <?= Html::img('/web/images/icons/icon_golden_star.png', ['style' => ['width' => '20px']]) ?>
                <?php endif; ?>

            </td>
        </tr>

        <?php foreach ($question->answers as $answer) : ?>

            <tr style="color: #4F4F4F; background: #F2F2F2;">
                <td style="width: 200px; font-size: 16px; padding: 10px;"><?= $answer->respond->getName() ?></td>
                <td style="width: 480px; font-size: 13px; padding: 10px;"><?= $answer->getAnswer() ?></td>
            </tr>

        <?php endforeach; ?>

    </table>

<?php endforeach; ?>



