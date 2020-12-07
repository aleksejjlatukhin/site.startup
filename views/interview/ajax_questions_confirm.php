<?php

use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<!--Список вопросов-->
<?php foreach ($questions as $q => $question) : ?>

    <div class="col-xs-12 string_question string_question-<?= $question->id; ?>">

        <div class="row style_form_field_questions">
            <div class="col-xs-11">
                <div style="display:flex;">
                    <div class="number_question" style="padding-right: 15px;"><?= ($q+1) . '. '; ?></div>
                    <div class="title_question"><?= $question->title; ?></div>
                </div>
            </div>
            <div class="col-xs-1 delete_question_link">

                <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

                    <?= Html::a(Html::img('/images/icons/icon_delete.png', ['style' => ['width' => '24px']]), [
                        Url::to(['/interview/delete-question', 'id' => $question->id])],[
                        'title' => Yii::t('yii', 'Delete'),
                        'class' => 'delete-question-confirm-segment pull-right',
                        'id' => 'delete_question-'.$question->id,
                    ]); ?>

                <?php endif; ?>

            </div>
        </div>

    </div>

<?php endforeach; ?>
