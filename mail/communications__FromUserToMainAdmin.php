<?php

use app\models\ProjectCommunications;
use yii\helpers\Html;

/**
 * @var ProjectCommunications $communication
*/

?>


<p>
    Проектант, разрешил эспертизу <?= $communication->user->getUsername() ?>, разрешил эспертизу по этапу «описание проекта: <?= Html::a($communication->project->getProjectName(), Yii::$app->urlManager->createAbsoluteUrl(['/projects/index', 'id' => $communication->project->getUserId(), 'project_id' => $communication->getProjectId()]))?>». Вы можете назначить эксперта на этот проект.
</p>
