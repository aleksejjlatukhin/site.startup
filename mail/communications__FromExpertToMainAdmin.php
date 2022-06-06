<?php

use yii\helpers\Html;
use app\modules\expert\models\form\FormCreateCommunicationResponse;

?>

<p>
    Данное письмо является ответом эксперта <b><?= $user->getUsername(); ?></b> на запрос о готовности провети экспертизу проекта <?= Html::a('«'.$communication->project->project_name.'»', Yii::$app->urlManager->createAbsoluteUrl(['/projects/index', 'id' => $communication->project->user_id, 'project_id' => $communication->project->id])); ?>
</p>

<p>
    <b>Ответ эксперта: </b> <?= FormCreateCommunicationResponse::getAnswers()[$communication->communicationResponse->answer]; ?>

    <?php if ($communication->communicationResponse->comment) : ?>
        <br>
        <b>Комментарий: </b> <?= $communication->communicationResponse->comment; ?>
    <?php endif; ?>
</p>

