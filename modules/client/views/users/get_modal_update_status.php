<?php

use app\models\User;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\helpers\Html;

?>

<style>
    .select2-container--krajee .select2-selection {
        font-size: 16px;
        height: 40px;
        padding-left: 15px;
        padding-top: 8px;
        padding-bottom: 15px;
        border-radius: 12px;
    }
    .select2-container--krajee .select2-selection--single .select2-selection__arrow {
        height: 39px;
    }
</style>

<?php if ($model->role === User::ROLE_USER) : ?>

    <?php if ($model->status === User::STATUS_NOT_ACTIVE) : ?>

        <?php if ($model->id_admin === null) : ?>

            <h4 class="row text-center">Сначала необходимо назначить трекера</h4>

        <?php elseif ($model->id_admin !== null) : ?>

            <?php $form = ActiveForm::begin([
                'id' => 'formStatusUpdate',
                'action' => Url::to(['/client/users/status-update', 'id' => $model->id]),
                'options' => ['class' => 'g-py-15'],
                'errorCssClass' => 'u-has-error-v1',
                'successCssClass' => 'u-has-success-v1-1',
            ]); ?>

            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <?= $form->field($model, 'status', [
                        'template' => '{input}',
                    ])->widget(Select2::class, [
                        'data' => [User::STATUS_ACTIVE => 'Активировать пользователя', User::STATUS_DELETED => 'Заблокировать пользователя'],
                        'options' => ['id' => 'selectStatusUpdate'],
                        'disabled' => false,  //Сделать поле неактивным
                        'hideSearch' => true, //Скрытие поиска
                    ]); ?>
                </div>
                <div class="col-md-3"></div>
            </div>

            <div class="row" style="display:flex; justify-content: center;">
                <?= Html::submitButton('Сохранить', [
                    'style' => [
                        'display' => 'flex',
                        'align-items' => 'center',
                        'justify-content' => 'center',
                        'background' => '#52BE7F',
                        'width' => '180px',
                        'height' => '40px',
                        'font-size' => '24px',
                        'border-radius' => '8px',
                    ],
                    'class' => 'btn btn-lg btn-success',
                ]) ?>
            </div>

            <?php ActiveForm::end(); ?>

        <?php endif; ?>

    <?php elseif ($model->status === User::STATUS_ACTIVE) : ?>

        <?php $form = ActiveForm::begin([
            'id' => 'formStatusUpdate',
            'action' => Url::to(['/client/users/status-update', 'id' => $model->id]),
            'options' => ['class' => 'g-py-15'],
            'errorCssClass' => 'u-has-error-v1',
            'successCssClass' => 'u-has-success-v1-1',
        ]); ?>

        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <?= $form->field($model, 'status', [
                    'template' => '{input}',
                ])->widget(Select2::class, [
                    'data' => [User::STATUS_DELETED => 'Заблокировать пользователя'],
                    'options' => ['id' => 'selectStatusUpdate'],
                    'disabled' => false,  //Сделать поле неактивным
                    'hideSearch' => true, //Скрытие поиска
                ]); ?>
            </div>
            <div class="col-md-3"></div>
        </div>

        <div class="row" style="display:flex; justify-content: center;">
            <?= Html::submitButton('Сохранить', [
                'style' => [
                    'display' => 'flex',
                    'align-items' => 'center',
                    'justify-content' => 'center',
                    'background' => '#52BE7F',
                    'width' => '180px',
                    'height' => '40px',
                    'font-size' => '24px',
                    'border-radius' => '8px',
                ],
                'class' => 'btn btn-lg btn-success',
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>

    <?php elseif ($model->status === User::STATUS_DELETED) : ?>

        <?php $form = ActiveForm::begin([
            'id' => 'formStatusUpdate',
            'action' => Url::to(['/client/users/status-update', 'id' => $model->id]),
            'options' => ['class' => 'g-py-15'],
            'errorCssClass' => 'u-has-error-v1',
            'successCssClass' => 'u-has-success-v1-1',
        ]); ?>

        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <?= $form->field($model, 'status', [
                    'template' => '{input}',
                ])->widget(Select2::class, [
                    'data' => [User::STATUS_ACTIVE => 'Активировать пользователя', User::STATUS_REMOVE => 'Удалить пользователя'],
                    'options' => ['id' => 'selectStatusUpdate'],
                    'disabled' => false,  //Сделать поле неактивным
                    'hideSearch' => true, //Скрытие поиска
                ]); ?>
            </div>
            <div class="col-md-3"></div>
        </div>

        <div class="row" style="display:flex; justify-content: center;">
            <?= Html::submitButton('Сохранить', [
                'style' => [
                    'display' => 'flex',
                    'align-items' => 'center',
                    'justify-content' => 'center',
                    'background' => '#52BE7F',
                    'width' => '180px',
                    'height' => '40px',
                    'font-size' => '24px',
                    'border-radius' => '8px',
                ],
                'class' => 'btn btn-lg btn-success',
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>

    <?php endif; ?>

<?php elseif ($model->role === User::ROLE_ADMIN) : ?>

    <?php if ($users = User::findAll(['id_admin' => $model->id])) : ?>

        <h4 class="text-center">Запрещено изменять статус трекера, у которого есть пользователи.</h4>

    <?php else : ?>

        <?php if ($model->status === User::STATUS_NOT_ACTIVE) : ?>

            <?php $form = ActiveForm::begin([
                'id' => 'formStatusUpdate',
                'action' => Url::to(['/client/users/status-update', 'id' => $model->id]),
                'options' => ['class' => 'g-py-15'],
                'errorCssClass' => 'u-has-error-v1',
                'successCssClass' => 'u-has-success-v1-1',
            ]); ?>

            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <?= $form->field($model, 'status', [
                        'template' => '{input}',
                    ])->widget(Select2::class, [
                        'data' => [User::STATUS_ACTIVE => 'Активировать трекера', User::STATUS_DELETED => 'Заблокировать трекера'],
                        'options' => ['id' => 'selectStatusUpdate'],
                        'disabled' => false,  //Сделать поле неактивным
                        'hideSearch' => true, //Скрытие поиска
                    ]); ?>
                </div>
                <div class="col-md-3"></div>
            </div>

            <div class="row" style="display:flex; justify-content: center;">
                <?= Html::submitButton('Сохранить', [
                    'style' => [
                        'display' => 'flex',
                        'align-items' => 'center',
                        'justify-content' => 'center',
                        'background' => '#52BE7F',
                        'width' => '180px',
                        'height' => '40px',
                        'font-size' => '24px',
                        'border-radius' => '8px',
                    ],
                    'class' => 'btn btn-lg btn-success',
                ]) ?>
            </div>

            <?php ActiveForm::end(); ?>

        <?php elseif ($model->status === User::STATUS_ACTIVE) : ?>

            <?php $form = ActiveForm::begin([
                'id' => 'formStatusUpdate',
                'action' => Url::to(['/client/users/status-update', 'id' => $model->id]),
                'options' => ['class' => 'g-py-15'],
                'errorCssClass' => 'u-has-error-v1',
                'successCssClass' => 'u-has-success-v1-1',
            ]); ?>

            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <?= $form->field($model, 'status', [
                        'template' => '{input}',
                    ])->widget(Select2::class, [
                        'data' => [User::STATUS_DELETED => 'Заблокировать трекера'],
                        'options' => ['id' => 'selectStatusUpdate'],
                        'disabled' => false,  //Сделать поле неактивным
                        'hideSearch' => true, //Скрытие поиска
                    ]); ?>
                </div>
                <div class="col-md-3"></div>
            </div>

            <div class="row" style="display:flex; justify-content: center;">
                <?= Html::submitButton('Сохранить', [
                    'style' => [
                        'display' => 'flex',
                        'align-items' => 'center',
                        'justify-content' => 'center',
                        'background' => '#52BE7F',
                        'width' => '180px',
                        'height' => '40px',
                        'font-size' => '24px',
                        'border-radius' => '8px',
                    ],
                    'class' => 'btn btn-lg btn-success',
                ]) ?>
            </div>

            <?php ActiveForm::end(); ?>

        <?php elseif ($model->status === User::STATUS_DELETED) : ?>

            <?php $form = ActiveForm::begin([
                'id' => 'formStatusUpdate',
                'action' => Url::to(['/client/users/status-update', 'id' => $model->id]),
                'options' => ['class' => 'g-py-15'],
                'errorCssClass' => 'u-has-error-v1',
                'successCssClass' => 'u-has-success-v1-1',
            ]); ?>

            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <?= $form->field($model, 'status', [
                        'template' => '{input}',
                    ])->widget(Select2::class, [
                        'data' => [User::STATUS_ACTIVE => 'Активировать трекера', User::STATUS_REMOVE => 'Удалить трекера'],
                        'options' => ['id' => 'selectStatusUpdate'],
                        'disabled' => false,  //Сделать поле неактивным
                        'hideSearch' => true, //Скрытие поиска
                    ]); ?>
                </div>
                <div class="col-md-3"></div>
            </div>

            <div class="row" style="display:flex; justify-content: center;">
                <?= Html::submitButton('Сохранить', [
                    'style' => [
                        'display' => 'flex',
                        'align-items' => 'center',
                        'justify-content' => 'center',
                        'background' => '#52BE7F',
                        'width' => '180px',
                        'height' => '40px',
                        'font-size' => '24px',
                        'border-radius' => '8px',
                    ],
                    'class' => 'btn btn-lg btn-success',
                ]) ?>
            </div>

            <?php ActiveForm::end(); ?>

        <?php endif; ?>

    <?php endif; ?>

<?php elseif ($model->role === User::ROLE_EXPERT) : ?>

    <?php if ($model->status === User::STATUS_NOT_ACTIVE) : ?>

        <?php $form = ActiveForm::begin([
            'id' => 'formStatusUpdate',
            'action' => Url::to(['/client/users/status-update', 'id' => $model->id]),
            'options' => ['class' => 'g-py-15'],
            'errorCssClass' => 'u-has-error-v1',
            'successCssClass' => 'u-has-success-v1-1',
        ]); ?>

        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <?= $form->field($model, 'status', [
                    'template' => '{input}',
                ])->widget(Select2::class, [
                    'data' => [User::STATUS_ACTIVE => 'Активировать эксперта', User::STATUS_DELETED => 'Заблокировать эксперта'],
                    'options' => ['id' => 'selectStatusUpdate'],
                    'disabled' => false,  //Сделать поле неактивным
                    'hideSearch' => true, //Скрытие поиска
                ]); ?>
            </div>
            <div class="col-md-3"></div>
        </div>

        <div class="row" style="display:flex; justify-content: center;">
            <?= Html::submitButton('Сохранить', [
                'style' => [
                    'display' => 'flex',
                    'align-items' => 'center',
                    'justify-content' => 'center',
                    'background' => '#52BE7F',
                    'width' => '180px',
                    'height' => '40px',
                    'font-size' => '24px',
                    'border-radius' => '8px',
                ],
                'class' => 'btn btn-lg btn-success',
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>

    <?php elseif ($model->status === User::STATUS_ACTIVE) : ?>

        <?php $form = ActiveForm::begin([
            'id' => 'formStatusUpdate',
            'action' => Url::to(['/client/users/status-update', 'id' => $model->id]),
            'options' => ['class' => 'g-py-15'],
            'errorCssClass' => 'u-has-error-v1',
            'successCssClass' => 'u-has-success-v1-1',
        ]); ?>

        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <?= $form->field($model, 'status', [
                    'template' => '{input}',
                ])->widget(Select2::class, [
                    'data' => [User::STATUS_DELETED => 'Заблокировать эксперта'],
                    'options' => ['id' => 'selectStatusUpdate'],
                    'disabled' => false,  //Сделать поле неактивным
                    'hideSearch' => true, //Скрытие поиска
                ]); ?>
            </div>
            <div class="col-md-3"></div>
        </div>

        <div class="row" style="display:flex; justify-content: center;">
            <?= Html::submitButton('Сохранить', [
                'style' => [
                    'display' => 'flex',
                    'align-items' => 'center',
                    'justify-content' => 'center',
                    'background' => '#52BE7F',
                    'width' => '180px',
                    'height' => '40px',
                    'font-size' => '24px',
                    'border-radius' => '8px',
                ],
                'class' => 'btn btn-lg btn-success',
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>

    <?php elseif ($model->status === User::STATUS_DELETED) : ?>

        <?php $form = ActiveForm::begin([
            'id' => 'formStatusUpdate',
            'action' => Url::to(['/client/users/status-update', 'id' => $model->id]),
            'options' => ['class' => 'g-py-15'],
            'errorCssClass' => 'u-has-error-v1',
            'successCssClass' => 'u-has-success-v1-1',
        ]); ?>

        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <?= $form->field($model, 'status', [
                    'template' => '{input}',
                ])->widget(Select2::class, [
                    'data' => [User::STATUS_ACTIVE => 'Активировать эксперта', User::STATUS_REMOVE => 'Удалить эксперта'],
                    'options' => ['id' => 'selectStatusUpdate'],
                    'disabled' => false,  //Сделать поле неактивным
                    'hideSearch' => true, //Скрытие поиска
                ]); ?>
            </div>
            <div class="col-md-3"></div>
        </div>

        <div class="row" style="display:flex; justify-content: center;">
            <?= Html::submitButton('Сохранить', [
                'style' => [
                    'display' => 'flex',
                    'align-items' => 'center',
                    'justify-content' => 'center',
                    'background' => '#52BE7F',
                    'width' => '180px',
                    'height' => '40px',
                    'font-size' => '24px',
                    'border-radius' => '8px',
                ],
                'class' => 'btn btn-lg btn-success',
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>

    <?php endif; ?>

<?php endif; ?>
