<?php

use yii\helpers\Html;

?>


<!--Css Style for PDF-->
<!--https://mpdf.github.io/css-stylesheets/supported-css.html-->

<div class="">


    <table style="border: none;">

        <tr style="background: #F2F2F2;">
            <td colspan="1" style="width: 50px;"></td>
            <td colspan="3" style="width: 300px; padding: 15px 5px; color: #4F4F4F; text-align: center;">
                <strong>Фамилия, имя, отчество</strong>
            </td>
            <td colspan="4" style="width: 300px; padding: 15px 5px; color: #4F4F4F; text-align: center;">
                <strong>Данные респондента</strong>
            </td>
            <td colspan="2" style="width: 200px; padding: 15px 5px; color: #4F4F4F; text-align: center;">
                <strong>E-mail</strong>
            </td>
            <td colspan="2" style="width: 200px; padding: 15px 5px; color: #4F4F4F; text-align: center;">
                <strong>Дата опроса</strong>
            </td>
        </tr>


        <tr style="background: #F2F2F2; ">
            <td colspan="1" style="width: 50px;"></td>
            <td colspan="3" style="width: 300px; padding: 10px 5px; color: #4F4F4F; text-align: center;">

            </td>
            <td colspan="4" style="width: 300px; padding: 10px 5px; color: #4F4F4F; text-align: center; font-size: 12px;">
                Кто? Откуда? Чем занят?
            </td>
            <td colspan="2" style="width: 200px; padding: 10px 5px; color: #4F4F4F; text-align: center; font-size: 12px;">
                Адрес электронной почты
            </td>
            <td colspan="2" style="width: 200px; padding: 10px 5px; color: #4F4F4F; text-align: center; font-size: 12px;">
                Заполнение анкетных данных
            </td>
        </tr>


        <?php foreach ($responds as $respond): ?>

            <tr class="row container-one_respond" style="margin: 3px 0; background: #707F99;">

                <td colspan="1" style="width: 50px; text-align: center; height: 60px;">

                    <?php
                    if ($respond->descInterview->status == 1) {
                        echo  Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px', 'margin-bottom' => '-4px']]);
                    }
                    elseif ($respond->descInterview->status === null) {
                        echo  Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px', 'margin-bottom' => '-4px']]);
                    }
                    elseif ($respond->descInterview->status == 0) {
                        echo  Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px', 'margin-bottom' => '-4px']]);
                    }
                    else {
                        echo '';
                    }
                    ?>

                </td>

                <td colspan="3" style="width: 300px; padding: 10px 5px; color: #FFFFFF; font-size: 16px; height: 60px;">

                    <?=  $respond->name; ?>

                </td>


                <td colspan="4" style="width: 300px; padding: 10px 5px; color: #FFFFFF; font-size: 12px; height: 60px;">

                    <?php
                    if (!empty($respond->info_respond)){

                        if(mb_strlen($respond->info_respond) > 65) {
                            echo '<div title="'.$respond->info_respond.'">' . mb_substr($respond->info_respond, 0, 62) . '...</div>';
                        }else {
                            echo $respond->info_respond;
                        }
                    }
                    ?>

                </td>

                <td colspan="2" style="width: 200px; padding: 10px 5px; color: #FFFFFF; height: 60px; text-align: center;">

                    <?php
                    if (!empty($respond->email)){

                        if(mb_strlen($respond->email) > 25) {
                            echo '<div title="'.$respond->email.'">' . mb_substr($respond->email, 0, 22) . '...</div>';
                        }else {
                            echo $respond->email;
                        }
                    }
                    ?>

                </td>

                <td colspan="2" style="width: 200px; text-align: center; color: #FFFFFF; height: 60px; font-size: 15px;">

                    <?php
                    if (!empty($respond->descInterview->updated_at)){

                        $date_fact = date("d.m.y", $respond->descInterview->updated_at);
                        echo $date_fact;

                    }elseif (!empty($respond->info_respond) && empty($respond->descInterview->updated_at)){

                        echo Html::img(['@web/images/icons/add_vector.png'], ['style' => ['width' => '35px']]);
                    }
                    ?>

                </td>

            </tr>

        <?php  endforeach;?>


    </table>


</div>
