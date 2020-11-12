<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use app\models\User;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Данные пользователя';

?>

<div class="user-index row">

    <?= $this->render('menu_user', [
        'user' => $user,
    ]) ?>


    <div class="col-md-9" style="padding-left: 0;">

        <h5 class="d-inline p-2" style="font-weight: 700;text-transform: uppercase;text-align: center; background-color: #0972a5;color: #fff; height: 50px; line-height: 50px;margin-bottom: 0;">
            <div class="row">

                <?= Html::encode($this->title) ?>

            </div>
        </h5>

        <div style="display:flex; padding: 20px 0 10px 0;">

            <div class="" style="padding-left: 0;">
                <?= Html::img([$user['avatar_image']],['width' => '200px', 'min-height' => '200px', 'max-height' => '300px'])?>

                <div class="row" style="margin: 10px 0;padding-left: 0;">
                    <?= Html::a('Редактировать данные',Url::to(['/profile/update-profile', 'id' => $user['id']]), ['class' => 'btn btn-sm btn-primary', 'style' => ['width' => '200px']]);?>
                </div>

                <div class="row" style="margin: 10px 0;padding-left: 0;">
                    <?= Html::a('Сменить пароль',Url::to(['/profile/change-password', 'id' => $user['id']]), ['class' => 'btn btn-sm btn-primary', 'style' => ['width' => '200px']]);?>
                </div>

            </div>

            <div class="" style="width: 100%; margin-left: 30px;">

                <div>
                    <div class="col-md-4" style="padding: 0;">Дата регистрации: </div>
                    <span style="font-weight: 700;"><?= date('d.m.Y', $user['created_at']); ?></span>
                </div>

                <div>
                    <div class="col-md-4" style="padding: 0;">Последнее изменение: </div>
                    <span style="font-weight: 700;"><?= date('d.m.Y', $user['updated_at']); ?></span>
                </div>

                <div style="border-bottom: 1px solid #ccc;padding-bottom: 10px;">

                    <div class="col-md-4" style="padding: 0;">Статус:</div>

                    <span style="font-weight: 700;">
                        <? if ($user['status'] == User::STATUS_ACTIVE) echo '<span style="color: green;">активирован</span>'; ?>
                        <? if ($user['status'] == User::STATUS_NOT_ACTIVE) echo '<span style="color: #0972a5;">не активирован</span>'; ?>
                        <? if ($user['status'] == User::STATUS_DELETED) echo '<span style="color: red;">заблокирован</span>'; ?>
                    </span>

                </div>

                <div style="padding-top: 10px;">
                    <div class="col-md-4" style="padding: 0;">Фамилия: </div>
                    <span style="font-weight: 700;"><?= $user['second_name']; ?></span>
                </div>

                <div>
                    <div class="col-md-4" style="padding: 0;">Имя: </div>
                    <span style="font-weight: 700;"><?= $user['first_name']; ?></span>
                </div>

                <div>
                    <div class="col-md-4" style="padding: 0;">Отчество: </div>
                    <span style="font-weight: 700;"><?= $user['middle_name']; ?></span>
                </div>

                <div>
                    <div class="col-md-4" style="padding: 0;">Логин: </div>
                    <span style="font-weight: 700;"><?= $user['username']; ?></span>
                </div>

                <?php if (!empty($user['telephone'])) : ?>

                    <div>
                        <div class="col-md-4" style="padding: 0;">Телефон: </div>
                            <span style="font-weight: 700;">
                                <?= $user['telephone']; ?>
                            </span>
                    </div>

                <?php endif; ?>

                <div style="border-bottom: 1px solid #ccc;padding-bottom: 10px;">
                    <div class="col-md-4" style="padding-left: 0;">Эл.почта: </div>
                    <span style="font-weight: 700;"><?= $user['email']; ?></span>
                </div>

            </div>
        </div>


        <script>

            $( ".catalog" ).dcAccordion({speed:300});

        </script>

    </div>

</div>