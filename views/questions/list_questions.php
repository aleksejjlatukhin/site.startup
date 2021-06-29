<?php

use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\QuestionStatus;

?>

<!--Список вопросов-->
<?php foreach ($questions as $q => $question) : ?>

    <div class="col-xs-12 string_question string_question-<?= $question->id; ?>">

        <div class="row style_form_field_questions">
            <div class="col-xs-8 col-sm-9 col-md-9 col-lg-10">
                <div style="display:flex;">
                    <div class="number_question" style="padding-right: 15px;"><?= ($q+1) . '. '; ?></div>
                    <div class="title_question"><?= $question->title; ?></div>
                </div>
            </div>
            <div class="col-xs-4 col-sm-3 col-md-3 col-lg-2 delete_question_link">

                <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

                    <?= Html::a(Html::img('/images/icons/icon_delete.png', ['style' => ['width' => '24px']]), [
                        Url::to(['/questions/delete', 'stage' => $question->confirm->stage, 'id' => $question->id])],[
                        'title' => Yii::t('yii', 'Delete'),
                        'class' => 'delete-question-confirm-hypothesis pull-right',
                        'id' => 'delete_question-'.$question->id,
                    ]); ?>

                    <?= Html::a(Html::img('/images/icons/icon_update.png', ['style' => ['width' => '24px', 'margin-top' => '3px', ]]), [
                        Url::to(['/questions/get-form-update', 'stage' => $question->confirm->stage, 'id' => $question->id])], [
                        'class' => 'showQuestionUpdateForm pull-right',
                        'style' => ['margin-right' => '20px'],
                        'title' => 'Редактировать вопрос',
                    ]); ?>

                    <?php if ($question->status === QuestionStatus::STATUS_NOT_STAR) : ?>
                        <?= Html::a('<div class="star"></div>', Url::to(['/questions/change-status', 'stage' => $question->confirm->stage, 'id' => $question->id]), [
                            'class' => 'star-link', 'title' => 'Значимость вопроса'
                        ]); ?>
                    <?php elseif ($question->status === QuestionStatus::STATUS_ONE_STAR) : ?>
                        <?= Html::a('<div class="star active"></div>', Url::to(['/questions/change-status', 'stage' => $question->confirm->stage, 'id' => $question->id]), [
                            'class' => 'star-link', 'title' => 'Значимость вопроса'
                        ]); ?>
                    <?php endif; ?>

                <?php else : ?>

                    <?php if ($question->status === QuestionStatus::STATUS_NOT_STAR) : ?>
                        <div class="star-passive" title="Значимость вопроса">
                            <div class="star"></div>
                        </div>
                    <?php elseif ($question->status === QuestionStatus::STATUS_ONE_STAR) : ?>
                        <div class="star-passive" title="Значимость вопроса">
                            <div class="star active"></div>
                        </div>
                    <?php endif; ?>

                <? endif; ?>

            </div>
        </div>

    </div>

<?php endforeach; ?>
