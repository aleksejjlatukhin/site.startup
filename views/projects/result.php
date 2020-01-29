<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<?php

$this->title = 'Сводная таблица данных по проекту "' . mb_strtolower($model->project_name) . '"';

?>

<h2 style="text-align: center"><?= 'Сводная таблица данных по проекту ' . Html::a(Html::encode(mb_strtolower('"' . $model->project_name . '"')), Url::to(['view', 'id' => $model->id])) ?></h2 style="text-align: center"><br>

<table class="table table-bordered table">
    <thead>
    <tr>
        <th scope="col" style="text-align: center;width: 180px;padding: 30px 0;">Сегмент</th>
        <th scope="col" style="text-align: center;width: 180px;padding: 30px 0;">ГПС</th>
        <th scope="col" style="text-align: center;width: 180px;padding: 30px 0;">Подтверждение ГПС</th>
        <th scope="col" style="text-align: center;width: 180px;padding: 30px 0;">ГЦП</th>
        <th scope="col" style="text-align: center;width: 180px;padding: 30px 0;">Подтверждение ГЦП</th>
        <th scope="col" style="text-align: center;width: 180px;padding: 30px 0;">MVP</th>
        <th scope="col" style="text-align: center;width: 180px;padding: 30px 0;">Подтверждение MVP</th>
        <th scope="col" style="text-align: center;width: 180px;padding: 30px 0;">Бизнес-модель</th>
    </tr>
    </thead>
    <tbody>


    <?



    foreach ($segments as $segment){

        $countProblems = [];
        $countGcps = [];
        $countMvps = [];

        $countProblems[] = count($segment->interview->problems);
        foreach ($countProblems as $k => $countProblem){
            if ($countProblems[$k] == 0){
                $countProblems[$k] = 1;
            }
        }


        foreach ($problems as $k => $problem){

            if ($problem->interview_id == $segment->interview->id){

                if (count($problem->confirm->gcps) != 0){

                    foreach ($offers as $offer) {
                        if ($offer->confirm_problem_id == $problem->confirm->id){
                            if (count($offer->confirm->mvps) != 0){
                                $countGcps[$k] += count($offer->confirm->mvps);
                            }
                            if (count($offer->confirm->mvps) == 0){
                                $countGcps[$k]++;
                            }
                        }
                    }
                }

                if (count($problem->confirm->gcps) == 0) {
                    $countGcps[$k] = count($problem->confirm->gcps);
                    foreach ($countGcps as $h => $countGcp) {
                        if ($countGcps[$h] == 0) {
                            $countGcps[$h] = 1;
                        }
                    }
                }


                if (empty($problem->confirm->gcps)){
                    $countMvps[] = 1;
                }

                foreach ($offers as $offer) {

                    if ($offer->confirm_problem_id == $problem->confirm->id){

                        $countMvps[] = count($offer->confirm->mvps);
                        foreach ($countMvps as $i => $countMvp){
                            if ($countMvps[$i] == 0){
                                $countMvps[$i] = 1;
                            }
                        }
                    }
                }
            }
        }

        $countP = array_sum($countProblems);
        $countG = array_sum($countGcps);
        $countM = array_sum($countMvps);
        $minHeight = 35;

        $ourCount = array($countP, $countG, $countM);
        $maxCount = max($ourCount);

        //debug($countGcps);
        //debug($countMvps);

        echo '<tr style="text-align: center"><td style="vertical-align: middle; height: ' . $minHeight *  $countM . 'px">' . Html::a(Html::encode($segment->name), Url::to(['segment/view', 'id' => $segment->id])). '</td>';

        echo '<td style="padding: 0;">';

        foreach ($problems as $i => $problem) {

            if ($problem->interview_id == $segment->interview->id) {

                echo '<div class="border-gray" style="line-height: ' . $minHeight * $countGcps[$i] . 'px; height: ' . $minHeight * $countGcps[$i] . 'px;">' . Html::a(Html::encode($problem->title), Url::to(['generation-problem/view', 'id' => $problem->id])) . '</div>';
            }
        }

        echo '</td>';

        echo '<td style="padding: 0;">';

        foreach ($problems as $i => $problem) {

            if ($problem->interview_id == $segment->interview->id) {

                if ($problem->exist_confirm === 1) {
                    echo '<div class="border-gray" style="color: green; font-size: 20px; line-height: ' . $minHeight * $countGcps[$i] . 'px; height: ' . $minHeight * $countGcps[$i] . 'px;"> + </div>';
                }
                if ($problem->exist_confirm === 0) {
                    echo '<div class="border-gray" style="color: red; font-size: 20px; line-height: ' . $minHeight * $countGcps[$i] . 'px; height: ' . $minHeight * $countGcps[$i] . 'px;"> - </div>';
                }
                if ($problem->exist_confirm === null) {
                    echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight * $countGcps[$i] . 'px; height: ' . $minHeight * $countGcps[$i] . 'px;"> --- </div>';
                }
            }
        }

        echo '</td>';

        echo '<td style="padding: 0;">';

        foreach ($problems as $i => $problem) {

            if ($problem->interview_id == $segment->interview->id) {

                if (empty($problem->confirm->gcps)){
                    echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight * $countGcps[$i] . 'px; height: ' . $minHeight * $countGcps[$i] . 'px;"> --- </div>';
                }

                foreach ($offers as $j => $offer) {

                    if ($offer->confirm_problem_id == $problem->confirm->id) {

                        echo '<div class="border-gray" style="line-height: ' . $minHeight * $countMvps[$j] . 'px; height: ' . $minHeight * $countMvps[$j] . 'px;">' . Html::a(Html::encode($offer->title), Url::to(['gcp/view', 'id' => $offer->id])) . '</div>';
                    }
                }
            }
        }

        echo '</td>';

        echo '<td style="padding: 0;">';

        foreach ($problems as $i => $problem) {

            if ($problem->interview_id == $segment->interview->id) {

                if (empty($problem->confirm->gcps)){
                    echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight * $countGcps[$i] . 'px; height: ' . $minHeight * $countGcps[$i] . 'px;"> --- </div>';
                }

                foreach ($offers as $j => $offer) {

                    if ($offer->confirm_problem_id == $problem->confirm->id) {

                        if ($offer->exist_confirm === 1) {
                            echo '<div class="border-gray" style="color: green; font-size: 20px; line-height: ' . $minHeight * $countMvps[$j] . 'px; height: ' . $minHeight * $countMvps[$j] . 'px;"> + </div>';
                        }
                        if ($offer->exist_confirm === 0) {
                            echo '<div class="border-gray" style="color: red; font-size: 20px; line-height: ' . $minHeight * $countMvps[$j] . 'px; height: ' . $minHeight * $countMvps[$j] . 'px;"> - </div>';
                        }
                    }
                }
            }
        }

        echo '</td>';

        echo '<td style="padding: 0;">';

        foreach ($problems as $i => $problem) {

            if ($problem->interview_id == $segment->interview->id) {

                if (empty($problem->confirm->gcps)){
                    echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight * $countGcps[$i] . 'px; height: ' . $minHeight * $countGcps[$i] . 'px;"> --- </div>';
                }

                foreach ($offers as $j => $offer) {

                    if ($offer->confirm_problem_id == $problem->confirm->id) {

                        if (empty($offer->confirm->mvps)){
                            echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight * $countMvps[$j] . 'px; height: ' . $minHeight * $countMvps[$j] . 'px;"> --- </div>';
                        }

                        foreach ($mvProducts as $mvProduct) {
                            if ($mvProduct->confirm_gcp_id == $offer->confirm->id) {

                                echo '<div class="border-gray" style="line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">' . Html::a(Html::encode($mvProduct->title), Url::to(['mvp/view', 'id' => $mvProduct->id])) . '</div>';
                            }
                        }
                    }
                }
            }
        }

        echo '</td>';

        echo '<td style="padding: 0;">';

        foreach ($problems as $i => $problem) {

            if ($problem->interview_id == $segment->interview->id) {

                if (empty($problem->confirm->gcps)){
                    echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;"> --- </div>';
                }

                foreach ($offers as $j => $offer) {

                    if ($offer->confirm_problem_id == $problem->confirm->id) {

                        if (empty($offer->confirm->mvps)){
                            echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;"> --- </div>';
                        }

                        foreach ($mvProducts as $mvProduct) {
                            if ($mvProduct->confirm_gcp_id == $offer->confirm->id) {

                                if ($mvProduct->exist_confirm === 1) {
                                    echo '<div class="border-gray" style="color: green; font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;"> + </div>';
                                }
                                if ($mvProduct->exist_confirm === 0) {
                                    echo '<div class="border-gray" style="color: red; font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;"> - </div>';
                                }
                                if ($mvProduct->exist_confirm === null) {
                                    echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;"> --- </div>';
                                }
                            }
                        }
                    }
                }
            }
        }

        echo '</td>';


        echo '<td style="padding: 0;">';

        foreach ($problems as $i => $problem) {

            if ($problem->interview_id == $segment->interview->id) {

                if (empty($problem->confirm->gcps)){
                    echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;"> --- </div>';
                }

                foreach ($offers as $j => $offer) {

                    if ($offer->confirm_problem_id == $problem->confirm->id) {

                        if (empty($offer->confirm->mvps)){
                            echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;"> --- </div>';
                        }

                        foreach ($mvProducts as $k => $mvProduct) {
                            if ($mvProduct->confirm_gcp_id == $offer->confirm->id) {

                                if ($mvProduct->exist_confirm === 1) {

                                    foreach ($confirmMvps as $confirmMvp){

                                        if ($confirmMvp->id == $mvProduct->confirm->id){

                                            if (empty($confirmMvp->business)){

                                                echo '<div class="border-gray" style="display: flex; justify-content: center; align-items: center; height: ' . $minHeight . 'px;">'. Html::a('Создать', ['business-model/create', 'id' => $mvProducts[$k]->confirm->id], ['class' => 'btn btn-success btn-block', 'style' => ['width' => '120px', 'height' => '30px', 'line-height' => '15px']]) .'</div>';
                                            }else{
                                                echo '<div class="border-gray" style="display: flex; justify-content: center; align-items: center; height: ' . $minHeight . 'px;">'. Html::a('Посмотреть', ['business-model/view', 'id' => $confirmMvp->business->id], ['class' => 'btn btn-success btn-block', 'style' => ['width' => '120px', 'height' => '30px', 'line-height' => '15px', 'text-align' => 'center',]]) .'</div>';

                                            }
                                        }
                                    }
                                }
                                if ($mvProduct->exist_confirm === 0) {
                                    echo '<div class="border-gray" style="color: red; font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;"> - </div>';
                                }
                                if ($mvProduct->exist_confirm === null) {
                                    echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;"> --- </div>';
                                }
                            }
                        }
                    }
                }
            }
        }

        echo '</td>';


        echo '</tr>';
    }
    ?>

    </tbody>
</table>




