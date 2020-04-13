<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>

<?php

$this->title = 'Админка | Проекты';

?>

<h2><?= $this->title; ?></h2>

<br>


<table class="table table-bordered table" style="width: 1140px;">
    <thead>
    <tr>
        <th scope="col" rowspan="2" style="width: 200px; height: 60px; text-align: center;padding-bottom: 20px;">Наименование проета</th>
        <th scope="col" rowspan="2" style="width: 130px; height: 60px; text-align: center;padding-bottom: 30px;">Автор проекта</th>
        <th scope="col" rowspan="2" style="width: 800px; height: 60px; text-align: center;padding-bottom: 30px;">Сегмент</th>
        <th scope="col" rowspan="2" style="width: 150px; height: 60px; text-align: center;padding-bottom: 30px;">ГПС</th>
        <th scope="col" colspan="2" style="width: 170px; height: 30px; text-align: center;padding-bottom: 2px;">Проблема сегмента</th>
        <th scope="col" rowspan="2" style="width: 130px; height: 60px; text-align: center;padding-bottom: 30px;">ГЦП</th>
        <th scope="col" colspan="2" style="width: 160px; height: 30px; text-align: center;padding-bottom: 2px;">Ценностное предложение</th>
        <th scope="col" rowspan="2" style="width: 130px; height: 60px; text-align: center;padding-bottom: 30px;">ГMVP</th>
        <th scope="col" colspan="2" style="width: 160px; height: 30px; text-align: center;padding-bottom: 11px;">MVP (продукт)</th>
        <th scope="col" rowspan="2" style="width: 140px; height: 60px; text-align: center;padding-bottom: 20px;">Бизнес-модель</th>
    </tr>
    <tr>
        <td style="width: 70px;text-align: center;font-weight: 700;">Статус</td>
        <td style="width: 110px;text-align: center;font-weight: 700;">Дата</td>
        <td style="width: 60px;text-align: center;font-weight: 700;">Статус</td>
        <td style="width: 100px;text-align: center;font-weight: 700;">Дата</td>
        <td style="width: 50px;text-align: center;font-weight: 700;">Статус</td>
        <td style="width: 100px;text-align: center;font-weight: 700;">Дата</td>
    </tr>
    </thead>
    <tbody>

    <?php

    foreach ($models as $k => $model){

        echo '<tr style="text-align: center; font-size: 13px;">';

        echo '<td style="font-weight: 700; height: ' . $minHeight *  $projectsRows[$k] . 'px;">' . Html::a(Html::encode($model->project_name), Url::to(['/projects/view', 'id' => $model->id])). '</td>';

        echo '<td style="font-weight: 700; height: ' . $minHeight *  $projectsRows[$k] . 'px;">' . Html::a(Html::encode($model->user->second_name . ' ' . $model->user->first_name . ' ' . $model->user->middle_name), Url::to(['/projects/view', 'id' => $model->id])) . '</td>';

        echo '<td style="padding: 0;">';

        foreach ($segmentsM as $s => $segment){

            if ($s == 0){

                //Выводим индикатор проекта (здесь по умолчанию зеленый, т.к. в проекте должен быть как минимум один сегмент)
                echo '<div class="border-gray bgc-success" style="height: ' . $minHeight *  1.5 . 'px;"></div>';
            }


            if ($segment->project->id == $model->id){

                //Выводим названия сегментов
                echo '<div class="border-gray" style="font-weight: 700; padding-top: 5px; height: ' . $minHeight *  $segmentsRows[$s] . 'px;">' . Html::a(Html::encode($segment->name), Url::to(['/segment/view', 'id' => $segment->id])). '</div>';

            }
        }

        echo '</td>';

        //Выводим ГПС
        echo '<td style="padding: 0;">';

        foreach ($segmentsM as $s => $segment) {


            if ($s == 0){

                //Выводим индикатор проекта
                if ($countProblems[$k] == 0 ){

                    echo '<div class="border-gray bgc-warning" style="height: ' . $minHeight *  1.5 . 'px;"></div>';

                }else {

                    echo '<div class="border-gray bgc-success" style="height: ' . $minHeight *  1.5 . 'px;"></div>';
                }
            }


            if ($segment->project->id == $model->id) {

                //Если у сегмента нет ГПС выводим следующее
                if(empty($segment->interview->problems)){
                    echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">'. Html::img('@web/images/icons/fast forward.png', ['style' => ['width' => '18px', 'padding-bottom' => '3px',]]) .'</div>';
                }

                //Если у сегмента есть ГПС выводим их названия
                foreach ($problems as $i => $problem) {

                    if ($problem->interview_id == $segment->interview->id) {

                        echo '<div class="border-gray" style="font-weight: 700; line-height: ' . $minHeight * $problemsRows[$i] . 'px; height: ' . $minHeight * $problemsRows[$i] . 'px;">
                
                                    <span data-toggle="tooltip" title="'.$problem->description.'">' . Html::a(Html::encode($problem->title), Url::to(['/generation-problem/view', 'id' => $problem->id])) . '</span>
                
                                </div>';
                    }
                }

            }
        }

        echo '</td>';


        //Выводим подтверждение ГПС
        echo '<td style="padding: 0;">';

        foreach ($segmentsM as $s => $segment) {

            if ($s == 0){

                //Выводим индикатор проекта
                if ($countConfirmProblems[$k] == 0 ){

                    echo '<div class="border-gray bgc-warning" style="height: ' . $minHeight *  1.5 . 'px;"></div>';

                }else {

                    echo '<div class="border-gray bgc-success" style="height: ' . $minHeight *  1.5 . 'px;"></div>';
                }
            }


            if ($segment->project->id == $model->id) {

                foreach ($problems as $i => $problem) {

                    if ($problem->interview_id == $segment->interview->id) {

                        //Если есть подтверждение то выводим его результат
                        if ($problem->exist_confirm === 1) {
                            echo '<div class="border-gray" style="color: green; font-size: 20px; line-height: ' . $minHeight * $problemsRows[$i] . 'px; height: ' . $minHeight * $problemsRows[$i] . 'px;">' . Html::img('@web/images/icons/green tick.png', ['style' => ['width' => '20px', 'padding-bottom' => '3px',]]) . '</div>';
                        }
                        if ($problem->exist_confirm === 0) {
                            echo '<div class="border-gray" style="color: red; font-size: 20px; line-height: ' . $minHeight * $problemsRows[$i] . 'px; height: ' . $minHeight * $problemsRows[$i] . 'px;">' . Html::img('@web/images/icons/cross delete.png', ['style' => ['width' => '22px', 'padding-bottom' => '3px',]]) . '</div>';
                        }

                        //Если у существующей ГПС нет подтверждения то выводим следующее
                        if ($problem->exist_confirm === null && !empty($problem)) {
                            echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight * $problemsRows[$i] . 'px; height: ' . $minHeight * $problemsRows[$i] . 'px;">' . Html::img('@web/images/icons/fast forward.png', ['style' => ['width' => '18px', 'padding-bottom' => '3px',]]) . '</div>';
                        }
                    }
                }
            }
        }

        echo '</td>';


        //Выводим дату подтверждения ГПС
        echo '<td style="padding: 0;">';

        foreach ($segmentsM as $s => $segment) {

            if ($s == 0){

                //Выводим индикатор проекта
                if ($countConfirmProblems[$k] == 0 ){

                    echo '<div class="border-gray bgc-warning" style="height: ' . $minHeight *  1.5 . 'px;"></div>';

                }else {

                    echo '<div class="border-gray bgc-success" style="height: ' . $minHeight *  1.5 . 'px;"></div>';
                }
            }


            if ($segment->project->id == $model->id) {

                foreach ($problems as $i => $problem) {

                    if ($problem->interview_id == $segment->interview->id) {

                        //Если есть подтверждение то выводим его результат
                        if ($problem->exist_confirm === 1) {
                            echo '<div class="border-gray" style="font-weight: 700; font-size: 13px; line-height: ' . $minHeight * $problemsRows[$i] . 'px; height: ' . $minHeight * $problemsRows[$i] . 'px;">'. date('d.m.y', strtotime($problem->date_confirm)) .'</div>';
                        }
                        if ($problem->exist_confirm === 0) {
                            echo '<div class="border-gray" style="font-weight: 700; font-size: 13px; line-height: ' . $minHeight * $problemsRows[$i] . 'px; height: ' . $minHeight * $problemsRows[$i] . 'px;">'. date('d.m.y', strtotime($problem->date_confirm)) .'</div>';
                        }

                        //Если у существующей ГПС нет подтверждения то выводим следующее
                        if ($problem->exist_confirm === null && !empty($problem)) {
                            echo '<div class="border-gray" style="line-height: ' . $minHeight * $problemsRows[$i] . 'px; height: ' . $minHeight * $problemsRows[$i] . 'px;"></div>';
                        }
                    }
                }
            }
        }

        echo '</td>';


        //Выводим ГЦП
        echo '<td style="padding: 0;">';

        foreach ($segmentsM as $s => $segment) {

            if ($s == 0){

                //Выводим индикатор проекта
                if ($countOffers[$k] == 0 ){

                    echo '<div class="border-gray bgc-warning" style="height: ' . $minHeight *  1.5 . 'px;"></div>';

                }else {

                    echo '<div class="border-gray bgc-success" style="height: ' . $minHeight *  1.5 . 'px;"></div>';
                }
            }


            if ($segment->project->id == $model->id) {

                foreach ($problems as $i => $problem) {

                    if ($problem->interview_id == $segment->interview->id) {

                        //Если не существует ГЦП
                        if (empty($problem->confirm->gcps)){

                            if ($problem->exist_confirm === 0){

                                //Если подтверждение ГПС отрицательное выводим следующее
                                echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">  </div>';

                            }elseif ($problem->exist_confirm === 1){

                                //Если подтверждение ГПС положительное выводим следующее
                                echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">'. Html::img('@web/images/icons/fast forward.png', ['style' => ['width' => '18px', 'padding-bottom' => '3px',]]) .'</div>';

                            }elseif ($problem->exist_confirm === null){

                                //Если подтверждение ГПС отсутствует или не закончено выводим следующее
                                echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">  </div>';
                            }
                        }

                        //Если существует ГЦП выводим названия
                        foreach ($offers as $j => $offer) {

                            if ($offer->confirm_problem_id == $problem->confirm->id) {

                                echo '<div class="border-gray" style="font-weight: 700; line-height: ' . $minHeight * $offersRows[$j] . 'px; height: ' . $minHeight * $offersRows[$j] . 'px;">
                            
                                            <span data-toggle="tooltip" title="'.$offer->description.'">' . Html::a(Html::encode($offer->title), Url::to(['/gcp/view', 'id' => $offer->id])) . '</span>
                            
                                        </div>';
                            }
                        }
                    }
                }
            }
        }

        echo '</td>';



        //Выводим подтверждение ГЦП
        echo '<td style="padding: 0;">';

        foreach ($segmentsM as $s => $segment) {


            if ($s == 0){

                //Выводим индикатор проекта
                if ($countConfirmOffers[$k] == 0 ){

                    echo '<div class="border-gray bgc-warning" style="height: ' . $minHeight *  1.5 . 'px;"></div>';

                }else {

                    echo '<div class="border-gray bgc-success" style="height: ' . $minHeight *  1.5 . 'px;"></div>';
                }
            }


            if ($segment->project->id == $model->id) {

                foreach ($problems as $i => $problem) {

                    if ($problem->interview_id == $segment->interview->id) {


                        if (empty($problem->confirm->gcps)){

                            //Если не  существует ГЦП выводим следующее
                            echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">  </div>';
                        }


                        //Если ГЦП существует
                        foreach ($offers as $j => $offer) {

                            if ($offer->confirm_problem_id == $problem->confirm->id) {

                                if ($offer->exist_confirm === 1) {

                                    //Если подтверждение ГЦП положительное выводим следующее
                                    echo '<div class="border-gray" style="color: green; font-size: 20px; line-height: ' . $minHeight * $offersRows[$j] . 'px; height: ' . $minHeight * $offersRows[$j] . 'px;">'. Html::img('@web/images/icons/green tick.png', ['style' => ['width' => '20px', 'padding-bottom' => '3px',]]) .'</div>';
                                }
                                if ($offer->exist_confirm === 0) {

                                    //Если подтверждение ГЦП отрицательное выводим следующее
                                    echo '<div class="border-gray" style="color: red; font-size: 20px; line-height: ' . $minHeight * $offersRows[$j] . 'px; height: ' . $minHeight * $offersRows[$j] . 'px;">'. Html::img('@web/images/icons/cross delete.png', ['style' => ['width' => '22px', 'padding-bottom' => '3px',]]) .'</div>';
                                }
                                if ($offer->exist_confirm === null) {

                                    //Если подтверждение ГЦП отсутствует или не закончено выводим следующее
                                    echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight * $offersRows[$j] . 'px; height: ' . $minHeight * $offersRows[$j] . 'px;">'. Html::img('@web/images/icons/fast forward.png', ['style' => ['width' => '18px', 'padding-bottom' => '3px',]]) .'</div>';
                                }
                            }
                        }
                    }
                }
            }
        }

        echo '</td>';


        //Выводим дату подтверждения ГЦП
        echo '<td style="padding: 0;">';

        foreach ($segmentsM as $s => $segment) {

            if ($s == 0){

                //Выводим индикатор проекта
                if ($countConfirmOffers[$k] == 0 ){

                    echo '<div class="border-gray bgc-warning" style="height: ' . $minHeight *  1.5 . 'px;"></div>';

                }else {

                    echo '<div class="border-gray bgc-success" style="height: ' . $minHeight *  1.5 . 'px;"></div>';
                }
            }


            if ($segment->project->id == $model->id) {

                foreach ($problems as $i => $problem) {

                    if ($problem->interview_id == $segment->interview->id) {


                        if (empty($problem->confirm->gcps)){

                            //Если не  существует ГЦП выводим следующее
                            echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">  </div>';
                        }


                        //Если ГЦП существует
                        foreach ($offers as $j => $offer) {

                            if ($offer->confirm_problem_id == $problem->confirm->id) {

                                if ($offer->exist_confirm === 1) {

                                    //Если подтверждение ГЦП положительное выводим следующее
                                    echo '<div class="border-gray" style="font-weight: 700; font-size: 13px; line-height: ' . $minHeight * $offersRows[$j] . 'px; height: ' . $minHeight * $offersRows[$j] . 'px;">'. date('d.m.y', strtotime($offer->date_confirm)) .'</div>';
                                }
                                if ($offer->exist_confirm === 0) {

                                    //Если подтверждение ГЦП отрицательное выводим следующее
                                    echo '<div class="border-gray" style="font-weight: 700; font-size: 13px; line-height: ' . $minHeight * $offersRows[$j] . 'px; height: ' . $minHeight * $offersRows[$j] . 'px;">'. date('d.m.y', strtotime($offer->date_confirm)) .'</div>';
                                }
                                if ($offer->exist_confirm === null) {

                                    //Если подтверждение ГЦП отсутствует или не закончено выводим следующее
                                    echo '<div class="border-gray" style="line-height: ' . $minHeight * $offersRows[$j] . 'px; height: ' . $minHeight * $offersRows[$j] . 'px;"></div>';
                                }
                            }
                        }
                    }
                }
            }
        }

        echo '</td>';


        //Выводим ГMVP
        echo '<td style="padding: 0;">';

        foreach ($segmentsM as $s => $segment) {

            if ($s == 0){

                //Выводим индикатор проекта
                if ($countMvp[$k] == 0 ){

                    echo '<div class="border-gray bgc-warning" style="height: ' . $minHeight *  1.5 . 'px;"></div>';

                }else {

                    echo '<div class="border-gray bgc-success" style="height: ' . $minHeight *  1.5 . 'px;"></div>';
                }
            }


            if ($segment->project->id == $model->id) {

                foreach ($problems as $i => $problem) {

                    if ($problem->interview_id == $segment->interview->id) {

                        if (empty($problem->confirm->gcps)){

                            //Если отсутствует ГЦП выводим следующее
                            echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">  </div>';
                        }

                        //Если ГЦП существует
                        foreach ($offers as $j => $offer) {

                            if ($offer->confirm_problem_id == $problem->confirm->id) {

                                //Если ГMVP не существует
                                if (empty($offer->confirm->mvps)){

                                    if ($offer->exist_confirm === 0){

                                        //Если подтверждение ГЦП отрицательное выводим следующее
                                        echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">  </div>';

                                    }elseif ($offer->exist_confirm === 1){

                                        //Если подтверждение ГЦП положительное выводим следующее
                                        echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">'. Html::img('@web/images/icons/fast forward.png', ['style' => ['width' => '18px', 'padding-bottom' => '3px',]]) .'</div>';

                                    }elseif ($offer->exist_confirm === null){

                                        //Если подтверждение ГЦП отсутствует или не закончено выводим следующее
                                        echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">  </div>';
                                    }
                                }

                                //Если ГMVP существует
                                foreach ($mvProducts as $mvProduct) {

                                    if ($mvProduct->confirm_gcp_id == $offer->confirm->id) {

                                        //Выводим название соответствующего ГMVP
                                        echo '<div class="border-gray" style="font-weight: 700; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">
                                
                                                    <span data-toggle="tooltip" title="'.$mvProduct->description.'">' . Html::a(Html::encode($mvProduct->title), Url::to(['/mvp/view', 'id' => $mvProduct->id])) . '</span>
                                
                                                </div>';
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        echo '</td>';



        //Выводим подтверждение ГMVP
        echo '<td style="padding: 0;">';

        foreach ($segmentsM as $s => $segment) {

            if ($s == 0){

                //Выводим индикатор проекта
                if ($countConfirmMvp[$k] == 0 ){

                    echo '<div class="border-gray bgc-warning" style="height: ' . $minHeight *  1.5 . 'px;"></div>';

                }else {

                    echo '<div class="border-gray bgc-success" style="height: ' . $minHeight *  1.5 . 'px;"></div>';
                }
            }


            if ($segment->project->id == $model->id) {

                foreach ($problems as $i => $problem) {

                    if ($problem->interview_id == $segment->interview->id) {


                        if (empty($problem->confirm->gcps)){

                            //Если отсутствует ГЦП
                            echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">  </div>';
                        }

                        foreach ($offers as $j => $offer) {

                            if ($offer->confirm_problem_id == $problem->confirm->id) {

                                if (empty($offer->confirm->mvps)){

                                    //Если отсутствует ГMVP
                                    echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">  </div>';
                                }

                                foreach ($mvProducts as $mvProduct) {
                                    if ($mvProduct->confirm_gcp_id == $offer->confirm->id) {

                                        if ($mvProduct->exist_confirm === 1) {

                                            //Если подтверждение ГMVP положительное выводим следующее
                                            echo '<div class="border-gray" style="color: green; font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">'. Html::img('@web/images/icons/green tick.png', ['style' => ['width' => '20px', 'padding-bottom' => '3px',]]) .'</div>';
                                        }
                                        if ($mvProduct->exist_confirm === 0) {

                                            //Если подтверждение ГMVP отрицательное выводим следующее
                                            echo '<div class="border-gray" style="color: red; font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">'. Html::img('@web/images/icons/cross delete.png', ['style' => ['width' => '22px', 'padding-bottom' => '3px',]]) .'</div>';
                                        }
                                        if ($mvProduct->exist_confirm === null) {

                                            //Если подтверждение ГMVP отсутствует или не закончено выводим следующее
                                            echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">'. Html::img('@web/images/icons/fast forward.png', ['style' => ['width' => '18px', 'padding-bottom' => '3px',]]) .'</div>';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        echo '</td>';


        //Выводим дату подтверждения ГMVP
        echo '<td style="padding: 0;">';

        foreach ($segmentsM as $s => $segment) {

            if ($s == 0){

                //Выводим индикатор проекта
                if ($countConfirmMvp[$k] == 0 ){

                    echo '<div class="border-gray bgc-warning" style="height: ' . $minHeight *  1.5 . 'px;"></div>';

                }else {

                    echo '<div class="border-gray bgc-success" style="height: ' . $minHeight *  1.5 . 'px;"></div>';
                }
            }


            if ($segment->project->id == $model->id) {

                foreach ($problems as $i => $problem) {

                    if ($problem->interview_id == $segment->interview->id) {


                        if (empty($problem->confirm->gcps)){

                            //Если отсутствует ГЦП
                            echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">  </div>';
                        }

                        foreach ($offers as $j => $offer) {

                            if ($offer->confirm_problem_id == $problem->confirm->id) {

                                if (empty($offer->confirm->mvps)){

                                    //Если отсутствует ГMVP
                                    echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">  </div>';
                                }

                                foreach ($mvProducts as $mvProduct) {
                                    if ($mvProduct->confirm_gcp_id == $offer->confirm->id) {

                                        if ($mvProduct->exist_confirm === 1) {

                                            //Если подтверждение ГMVP положительное выводим следующее
                                            echo '<div class="border-gray" style="font-weight: 700; font-size: 13px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">'. date('d.m.y', strtotime($mvProduct->date_confirm)) .'</div>';
                                        }
                                        if ($mvProduct->exist_confirm === 0) {

                                            //Если подтверждение ГMVP отрицательное выводим следующее
                                            echo '<div class="border-gray" style="font-weight: 700; font-size: 13px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">'. date('d.m.y', strtotime($mvProduct->date_confirm)) .'</div>';
                                        }
                                        if ($mvProduct->exist_confirm === null) {

                                            //Если подтверждение ГMVP отсутствует или не закончено выводим следующее
                                            echo '<div class="border-gray" style="line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;"> </div>';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        echo '</td>';


        //Выводим бизнес-модель
        echo '<td style="padding: 0;">';

        foreach ($segmentsM as $s => $segment) {

            if ($s == 0){

                //Выводим индикатор проекта
                if ($countBusinessModel[$k] == 0 ){

                    echo '<div class="border-gray bgc-warning" style="height: ' . $minHeight *  1.5 . 'px;"></div>';

                }else {

                    echo '<div class="border-gray bgc-success" style="height: ' . $minHeight *  1.5 . 'px;"></div>';
                }
            }

            if ($segment->project->id == $model->id) {

                foreach ($problems as $i => $problem) {

                    if ($problem->interview_id == $segment->interview->id) {


                        if (empty($problem->confirm->gcps)){

                            //Если не существует ГЦП выводим следующее
                            echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">  </div>';
                        }

                        //Если существует ГЦП
                        foreach ($offers as $j => $offer) {

                            if ($offer->confirm_problem_id == $problem->confirm->id) {

                                if (empty($offer->confirm->mvps)){

                                    //Если не существует ГMVP выводим следующее
                                    echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">  </div>';
                                }


                                //Если существует ГMVP
                                foreach ($mvProducts as $k => $mvProduct) {

                                    if ($mvProduct->confirm_gcp_id == $offer->confirm->id) {

                                        //Если подтверждение ГMVP положительное
                                        if ($mvProduct->exist_confirm === 1) {

                                            foreach ($confirmMvps as $confirmMvp){

                                                if ($confirmMvp->id == $mvProduct->confirm->id){

                                                    if (empty($confirmMvp->business)){

                                                        echo '<div class="border-gray" style="display: flex; justify-content: center; align-items: center; height: ' . $minHeight . 'px;">'. Html::a('Создать', ['/business-model/create', 'id' => $mvProducts[$k]->confirm->id], ['class' => 'btn btn-success btn-sm', 'style' => ['font-weight' => '700', 'width' => '90px']]) .'</div>';
                                                    }else{
                                                        echo '<div class="border-gray" style="display: flex; justify-content: center; align-items: center; height: ' . $minHeight . 'px;">'. Html::a('Посмотреть', ['/business-model/view', 'id' => $confirmMvp->business->id], ['class' => 'btn btn-success btn-sm', 'style' => ['font-weight' => '700', 'width' => '90px']]) .'</div>';

                                                    }
                                                }
                                            }
                                        }
                                        if ($mvProduct->exist_confirm === 0) {

                                            //Если подтверждение ГMVP отрицательное
                                            echo '<div class="border-gray" style="color: red; font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">  </div>';
                                        }
                                        if ($mvProduct->exist_confirm === null) {

                                            //Если подтверждение ГMVP отсутствует или не закончено
                                            echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">  </div>';
                                        }
                                    }
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


<div style="font-size: 13px;">

    <p><span class="bolder">Сегмент</span> - целевой сегмент, по которому проводится исследование.</p>
    <p><span class="bolder">Проблема сегмента</span> - подтвержденная гипотеза проблемы целевого сегмента.</p>
    <p><span class="bolder">Ценностное предложение</span> - подтвержденная гипотеза ценностного предложения.</p>
    <p><span class="bolder">MVP (продукт)</span> - подтвержденный минимально жизнеспособный продукт.</p>
    <p><span class="bolder">Бизнес-модель</span> - построение бизнес-модели по Остервальдеру.</p>

    <p><span class="bolder">ГПС</span> - гипотеза проблемы целевого сегмента.</p>
    <p><span class="bolder">ГЦП</span> - гипотеза ценностного предложения.</p>
    <p><span class="bolder">ГMVP</span> - гипотеза минимально жизнеспособного продукта.</p>

    <p style="padding-right: 80px;"><?= Html::img('@web/images/icons/green tick.png', ['style' => ['width' => '20px', 'padding-bottom' => '3px',]])?> - этап подтвержден.</p>
    <p style="padding-right: 80px;"><?= Html::img('@web/images/icons/cross delete.png', ['style' => ['width' => '22px', 'padding-bottom' => '3px',]])?> - этап не подтвержден.</p>
    <p><?= Html::img('@web/images/icons/fast forward.png', ['style' => ['width' => '18px', 'padding-bottom' => '3px',]])?> - этап, который требует дальнейшей реализации.</p>

</div>
