<?php


use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\User;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Админка | Профиль |  Данные пользователя';

?>

<div class="row">


    <div class="users-profile col-md-12" >

        <h5 class="d-inline p-2" style="font-weight: 700;text-transform: uppercase;text-align: center; background-color: #0972a5;color: #fff; height: 50px; line-height: 50px;margin-bottom: 0;">

            <?= Html::encode($this->title) ?>

        </h5>

        <div style="padding: 20px 0 10px 0;">

            <div class="col-md-3" style="padding-left: 0;">

                <?= Html::img([$admin['avatar_image']],['width' => '200px', 'min-height' => '200px', 'max-height' => '300px'])?>

                <?php if (($admin->id == Yii::$app->user->id) ||  (User::isUserDev(Yii::$app->user->identity['username']))) :?>

                    <div class="row" style="margin: 10px 0;">
                        <?= Html::a('Редактировать данные',Url::to(['/admin/users/update-profile', 'id' => $admin['id']]), ['class' => 'btn btn-sm btn-primary col-md-12', 'style' => ['width' => '200px']]);?>
                    </div>

                    <div class="row" style="margin: 10px 0;">
                        <?= Html::a('Сменить пароль',Url::to(['/admin/users/change-password', 'id' => $admin['id']]), ['class' => 'btn btn-sm btn-primary col-md-12', 'style' => ['width' => '200px']]);?>
                    </div>

                <?php endif; ?>

            </div>

            <div class="col-md-9" style="margin-top: -5px;">

                <div>
                    <div class="col-md-4" style="padding: 0;">Дата регистрации: </div>
                    <span style="font-weight: 700;"><?= date('d.m.Y', $admin['created_at']); ?></span>
                </div>

                <div>
                    <div class="col-md-4" style="padding: 0;">Последнее изменение: </div>
                    <span style="font-weight: 700;"><?= date('d.m.Y', $admin['updated_at']); ?></span>
                </div>

                <div style="border-bottom: 1px solid #ccc;padding-bottom: 10px;">

                    <div class="col-md-4" style="padding: 0;">Статус:</div>

                    <span style="font-weight: 700;">
                        <? if ($admin['status'] == 10) echo '<span style="color: green;">активирован</span>'; ?>
                        <? if ($admin['status'] == 1) echo '<span style="color: #0972a5;">не активирован</span>'; ?>
                        <? if ($admin['status'] == 0) echo '<span style="color: red;">заблокирован</span>'; ?>
                    </span>

                </div>

                <div style="padding-top: 10px;">
                    <div class="col-md-4" style="padding: 0;">Фамилия: </div>
                    <span style="font-weight: 700;"><?= $admin['second_name']; ?></span>
                </div>

                <div>
                    <div class="col-md-4" style="padding: 0;">Имя: </div>
                    <span style="font-weight: 700;"><?= $admin['first_name']; ?></span>
                </div>

                <div>
                    <div class="col-md-4" style="padding: 0;">Отчество: </div>
                    <span style="font-weight: 700;"><?= $admin['middle_name']; ?></span>
                </div>

                <div>
                    <div class="col-md-4" style="padding: 0;">Логин: </div>
                    <span style="font-weight: 700;"><?= $admin['username']; ?></span>
                </div>

                <?php if (!empty($admin['telephone'])) : ?>

                    <div>
                        <div class="col-md-4" style="padding: 0;">Телефон: </div>
                        <span style="font-weight: 700;">
                                <?= $admin['telephone']; ?>
                            </span>
                    </div>

                <?php endif; ?>

                <div style="border-bottom: 1px solid #ccc;padding-bottom: 10px;">
                    <div class="col-md-4" style="padding-left: 0;">Эл.почта: </div>
                    <span style="font-weight: 700;"><?= $admin['email']; ?></span>
                </div>


                <?php if (User::isUserMainAdmin(Yii::$app->user->identity['username']) ||  User::isUserDev(Yii::$app->user->identity['username'])) :?>

                    <?php if (!empty($users)) : ?>

                        <div style="padding-top: 7px;">
                            <div class="col-md-4" style="padding: 3px 0 0 0;">Администрирование: </div>
                            <span>
                                <?= Html::a('<div style="display: flex;"><div>Пользователи</div><div class="bgc-success" style="padding: 1px 7px; margin: 0 0 0 7px; border-radius: 5px;">' . count($users) . '</div></div>',Url::to(['/admin/users/group', 'id' => $admin['id']]), ['class' => 'btn btn-sm btn-primary', 'style' => [ 'margin-right' => '10px']]);?>
                                <?= Html::a('<div style="display: flex;"><div>Проекты</div><div class="bgc-success" style="padding: 1px 7px; margin: 0 0 0 7px; border-radius: 5px;">' . $countProjects . '</div></div>',Url::to(['/admin/projects/group', 'id' => $admin['id']]), ['class' => 'btn btn-sm btn-primary']);?>
                            </span>
                        </div>

                    <?php endif; ?>

                <?php endif; ?>

            </div>
        </div>

    </div>

</div>
