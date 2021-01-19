<?php

use yii\helpers\Html;

?>

<!--Css Style for PDF-->
<!--https://mpdf.github.io/css-stylesheets/supported-css.html-->

<div class="">


    <table style="border: none;">

        <tr style="background: #F2F2F2;">
            <td colspan="1" style="width: 50px;"></td>
            <td colspan="3" style="width: 265px; padding: 15px 5px; color: #4F4F4F;">
                <strong>Фамилия, имя, отчество</strong>
            </td>
            <td colspan="3" style="width: 265px; padding: 15px 5px; color: #4F4F4F;">
                <strong>Данные респондента</strong>
            </td>
            <td colspan="3" style="width: 265px; padding: 15px 5px; color: #4F4F4F;">
                <strong>Место проведения</strong>
            </td>
            <td colspan="2" style="width: 200px; padding: 15px 5px; color: #4F4F4F; text-align: center;">
                <strong>Интервью</strong>
            </td>
        </tr>


        <tr style="background: #F2F2F2; ">
            <td colspan="1" style="width: 50px;"></td>
            <td colspan="3" style="width: 265px; padding: 10px 5px; color: #4F4F4F; text-align: center;">

            </td>
            <td colspan="3" style="width: 265px; padding: 10px 5px; color: #4F4F4F; font-size: 12px;">
                Кто? Откуда? Чем занят?
            </td>
            <td colspan="3" style="width: 265px; padding: 10px 5px; color: #4F4F4F; font-size: 12px;">
                Организация, адрес
            </td>
            <td colspan="1" style="width: 100px; padding: 10px 5px; color: #4F4F4F; text-align: center; font-size: 12px;">
                План
            </td>
            <td colspan="1" style="width: 100px; padding: 10px 5px; color: #4F4F4F; text-align: center; font-size: 12px;">
                Факт
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

                <td colspan="3" style="width: 265px; padding: 10px 5px; color: #FFFFFF; font-size: 16px; height: 60px;">

                    <?=  $respond->name; ?>

                </td>


                <td colspan="3" style="width: 265px; padding: 10px 5px; color: #FFFFFF; font-size: 12px; height: 60px;">

                    <?php
                    if (!empty($respond->info_respond)){

                        if(mb_strlen($respond->info_respond) > 150) {
                            echo '<div title="'.$respond->info_respond.'">' . mb_substr($respond->info_respond, 0, 147) . '...</div>';
                        }else {
                            echo $respond->info_respond;
                        }
                    }
                    ?>

                </td>

                <td colspan="3" style="width: 265px; padding: 10px 5px; color: #FFFFFF; font-size: 12px; height: 60px;">

                    <?php
                    if (!empty($respond->place_interview)){

                        if(mb_strlen($respond->place_interview) > 150) {
                            echo '<div title="'.$respond->place_interview.'">' . mb_substr($respond->place_interview, 0, 147) . '...</div>';
                        }else {
                            echo $respond->place_interview;
                        }
                    }
                    ?>

                </td>

                <td colspan="1" style="width: 100px; text-align: center; color: #FFFFFF; height: 60px; font-size: 15px;">

                    <?php
                    if (!empty($respond->date_plan)){

                        echo '<div class="text-center" style="padding: 0 5px;">' . date("d.m.y", $respond->date_plan) . '</div>';
                    }
                    ?>

                </td>

                <td colspan="1" style="width: 100px; text-align: center; color: #FFFFFF; height: 60px; font-size: 15px;">

                    <?php
                    if (!empty($respond->descInterview->updated_at)){

                        $date_fact = date("d.m.y", $respond->descInterview->updated_at);
                        echo $date_fact;

                    }elseif (!empty($respond->info_respond) && !empty($respond->place_interview) && !empty($respond->date_plan) && empty($respond->descInterview->updated_at)){

                        echo Html::img(['@web/images/icons/add_vector.png'], ['style' => ['width' => '35px']]);
                    }
                    ?>

                </td>

            </tr>

        <?php  endforeach;?>


    </table>


</div>