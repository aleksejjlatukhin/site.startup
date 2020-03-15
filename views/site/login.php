<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Страница входа';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">

    <div class="row" style="display: flex">
        <div class="col-md-4" style="margin: auto;">

            <div style="text-align: center;">

                <h2><?= Html::encode($this->title) ?></h2>

                <p>Пожалуйста, заполните необходимые поля:</p>

            </div>

        </div>
    </div>

    <?php $form = ActiveForm::begin(); ?>

        <div class="row" style="display: flex">
            <div class="col-md-3" style="margin: auto;">

                <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox([
                    'template' => "<div>{input} {label}</div>\n",
                ]) ?>

            </div>
        </div>

        <div class="form-group">
            <div class="row" style="display: flex">
                <div class="col-md-3" style="margin: auto;">
                    <?= Html::submitButton('Войти', ['class' => 'btn btn-primary col-md-4', 'name' => 'login-button']) ?>
                    <?= Html::a('Забыли пароль?', ['send-email'], ['class' => 'btn btn-success col-md-7 pull-right']) ?>
                </div>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>
