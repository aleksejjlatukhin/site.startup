<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

?>

<div class="col-md-3">

    <ul class="catalog" style = "background: #ccc;padding-top: 1px; min-height: 100vh; list-style: none;padding-left: 20px;">

        <li style="margin: -10px 0 20px 0;">
            <h3>Профиль пользователя</h3>
        </li>

        <li style="padding-bottom: 3px;">
            <?= Html::a('Данные пользователя', Url::to(['/profile/index', 'id' => $user['id']]))?>
        </li>

        <?php if (!empty($user->projects)) : ?>

            <li style="padding-bottom: 3px;"><a href="#">Сводные таблицы</a>
                <ul style="list-style: none;padding: 0 5px;">
                    <?php foreach ($user->projects as $project) : ?>

                        <li style="margin-bottom: -3px;">
                            <?= Html::a(' - ' . $project->project_name, Url::to(['/profile/project', 'id' => $project->id]))?>
                        </li>

                    <?php endforeach; ?>
                </ul>
            </li>

        <?php else : ?>

            <li style="margin-bottom: 3px;">
                <?= Html::a('Сводные таблицы', Url::to(['/profile/not-found', 'id' => $user['id']]))?>
            </li>

        <?php endif; ?>


        <?php if (!empty($user->projects)) : ?>

            <li style="padding-bottom: 3px;"><a href="#">Дорожные карты</a>
                <ul style="list-style: none;padding: 0 5px;">
                    <?php foreach ($user->projects as $project) : ?>

                        <li style="margin-bottom: -3px;">
                            <?= Html::a(' - ' . $project->project_name, Url::to(['/profile/roadmap', 'id' => $project->id]))?>
                        </li>

                    <?php endforeach; ?>
                </ul>
            </li>

        <?php else : ?>

            <li style="margin-bottom: 3px;">
                <?= Html::a('Дорожные карты', Url::to(['/profile/not-found', 'id' => $user['id']]))?>
            </li>

        <?php endif; ?>



        <?php if (!empty($user->projects)) : ?>

            <li style="padding-bottom: 3px;"><a href="#">Презентации</a>
                <ul style="list-style: none;padding: 0 5px;">
                    <?php foreach ($user->projects as $project) : ?>

                        <li style="margin-bottom: -3px;">
                            <?= Html::a(' - ' . $project->project_name, Url::to(['/profile/prefiles', 'id' => $project->id]))?>
                        </li>

                    <?php endforeach; ?>
                </ul>
            </li>

        <?php else : ?>

            <li style="margin-bottom: 3px;">
                <?= Html::a('Презентации', Url::to(['/profile/not-found', 'id' => $user['id']]))?>
            </li>

        <?php endif; ?>

    </ul>
</div>

