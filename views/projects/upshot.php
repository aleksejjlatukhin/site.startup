<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>

<?php

$this->title = 'Сводная таблица проекта "' . mb_strtolower($model->project_name) . '"';

?>



<p>
<h2><?= 'Сводная таблица проекта ' . Html::a(Html::encode(mb_strtolower('"' . $model->project_name . '"')), Url::to(['view', 'id' => $model->id])) ?>

    <?= Html::a('Дорожная карта проекта', ['segment/roadmap', 'id' => $model->id], ['class' => 'btn btn-default pull-right']) ?></h2>
</p>

<br>

<table class="table table-bordered table">
    <thead>
    <tr>
        <th scope="col" rowspan="2" style="width: 250px; height: 60px; text-align: center;padding-bottom: 40px;">Сегмент</th>
        <th scope="col" rowspan="2" style="width: 130px; height: 60px; text-align: center;padding-bottom: 20px;">Гипотеза проблемы сегмента</th>
        <th scope="col" colspan="2" style="width: 190px; height: 30px; text-align: center;padding-bottom: 20px;">Проблема сегмента</th>
        <th scope="col" rowspan="2" style="width: 130px; height: 60px; text-align: center;padding-bottom: 20px;">Гипотеза ценностного предложения</th>
        <th scope="col" colspan="2" style="width: 180px; height: 30px; text-align: center;padding-bottom: 2px;">Ценностное предложение</th>
        <th scope="col" rowspan="2" style="width: 130px; height: 60px; text-align: center;padding-bottom: 20px;">Гипотеза MVP (продукт)</th>
        <th scope="col" colspan="2" style="width: 180px; height: 30px; text-align: center;padding-bottom: 20px;">MVP (продукт)</th>
        <th scope="col" rowspan="2" style="width: 140px; height: 60px; text-align: center;padding-bottom: 20px;">Бизнес-модель</th>
    </tr>
    <tr>
        <td style="width: 70px;text-align: center;font-weight: 700;">Статус</td>
        <td style="width: 120px;text-align: center;font-weight: 700;">Дата</td>
        <td style="width: 70px;text-align: center;font-weight: 700;">Статус</td>
        <td style="width: 110px;text-align: center;font-weight: 700;">Дата</td>
        <td style="width: 70px;text-align: center;font-weight: 700;">Статус</td>
        <td style="width: 110px;text-align: center;font-weight: 700;">Дата</td>
    </tr>
    </thead>
    <tbody>


    <?

    $countMvps = [];
    $countGcps = [];
    $countGcpsConfirm = [];

    foreach ($segments as $segment){

        foreach ($problems as $k => $problem){

            /*Если ГПС относится к выбранному сегменту*/
            if ($problem->interview_id == $segment->interview->id){

                /*Если подтверждения ГПС не существует*/
                if (empty($problem->confirm)){
                    $countGcps[] = 1;

                    /*Если подтверждения ГПС существует*/
                }else {

                    /*Если у ГПС существуют ГЦП и они являются массивом*/
                    if (is_array($problem->confirm->gcps) && !empty($problem->confirm->gcps)){

                        /*Проходимся циклом по ГЦП*/
                        foreach ($offers as $i => $offer) {

                            /*Если у выбранной ГЦП существуют ГMVP и они являются массивом и ГЦП относится к выбранной ГПС*/
                            if (is_array($offer->confirm->mvps) && !empty($offer->confirm->mvps) && $offer->confirm_problem_id == $problem->confirm->id){

                                $countGcps[$k] += count($offer->confirm->mvps);

                                /*Если у выбранной ГЦП не существуют ГMVP и ГЦП относится к выбранной ГПС*/
                            }elseif (empty($offer->confirm->mvps) && $offer->confirm_problem_id == $problem->confirm->id){

                                $countGcps[$k]++;
                            }
                        }
                    }
                }

                /*Если у ГПС не существует ГЦП*/
                if (is_array($problem->confirm->gcps) && empty($problem->confirm->gcps)) {
                    $countGcps[] = 1;
                }


                foreach ($offers as $y => $offer) {

                    /*Если не существует подтверждение ГЦП и ГЦП относится к выбранной ГПС*/
                    if (empty($offer->confirm) && $offer->confirm_problem_id == $problem->confirm->id){

                        $countMvps[$y] = 1;

                        /*Если у выбранной ГЦП существует массив ГMVP и ГЦП относится к выбранной ГПС*/
                    }elseif (is_array($offer->confirm->mvps) && $offer->confirm_problem_id == $problem->confirm->id){

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

        $countM = array_sum($countMvps);
        $minHeight = 35;
        //debug($countMvps);
        //debug($countGcps);

    }


    foreach ($segments as $k => $segment){

        /*Выводим названия сегментов*/
        echo '<tr style="text-align: center"><td style="font-weight: 700; vertical-align: middle; height: ' . $minHeight *  $countM[$k] . 'px">' . Html::a(Html::encode($segment->name), Url::to(['segment/view', 'id' => $segment->id])). '</td>';

        /*Выводим ГПС*/
        echo '<td style="padding: 0;">';

        /*Если у сегмента нет ГПС выводим следующее*/
        if(empty($segment->interview->problems)){
            echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">'. Html::img('@web/images/icons/fast forward.png', ['style' => ['width' => '18px', 'padding-bottom' => '3px',]]) .'</div>';
        }

        /*Если у сегмента есть ГПС выводим их названия*/
        foreach ($problems as $i => $problem) {

            if ($problem->interview_id == $segment->interview->id) {

                echo '<div class="border-gray" style="font-weight: 700; line-height: ' . $minHeight * $countGcps[$i] . 'px; height: ' . $minHeight * $countGcps[$i] . 'px;">
                
                    <span data-toggle="tooltip" title="'.$problem->description.'">' . Html::a(Html::encode($problem->title), Url::to(['generation-problem/view', 'id' => $problem->id])) . '</span>
                
                </div>';
            }
        }

        echo '</td>';

        /*Выводим подтверждение ГПС*/
        echo '<td style="padding: 0;">';

        foreach ($problems as $i => $problem) {


            if ($problem->interview_id == $segment->interview->id) {

                /*Если есть подтверждение то выводим его результат*/
                if ($problem->exist_confirm === 1) {
                    echo '<div class="border-gray" style="color: green; font-size: 20px; line-height: ' . $minHeight * $countGcps[$i] . 'px; height: ' . $minHeight * $countGcps[$i] . 'px;">'. Html::img('@web/images/icons/green tick.png', ['style' => ['width' => '20px', 'padding-bottom' => '3px',]]) .'</div>';
                }
                if ($problem->exist_confirm === 0) {
                    echo '<div class="border-gray" style="color: red; font-size: 20px; line-height: ' . $minHeight * $countGcps[$i] . 'px; height: ' . $minHeight * $countGcps[$i] . 'px;">'. Html::img('@web/images/icons/cross delete.png', ['style' => ['width' => '22px', 'padding-bottom' => '3px',]]) .'</div>';
                }

                /*Если у существующей ГПС нет подтверждения то выводим следующее*/
                if ($problem->exist_confirm === null && !empty($problem)) {
                    echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight * $countGcps[$i] . 'px; height: ' . $minHeight * $countGcps[$i] . 'px;">'. Html::img('@web/images/icons/fast forward.png', ['style' => ['width' => '18px', 'padding-bottom' => '3px',]]) .'</div>';
                }
            }

        }

        echo '</td>';

        /*Выводим дату подтверждения ГПС*/
        echo '<td style="padding: 0;">';

        foreach ($problems as $i => $problem) {


            if ($problem->interview_id == $segment->interview->id) {

                /*Если есть подтверждение то выводим его результат*/
                if ($problem->exist_confirm === 1) {
                    echo '<div class="border-gray" style="font-weight: 700; font-size: 13px; line-height: ' . $minHeight * $countGcps[$i] . 'px; height: ' . $minHeight * $countGcps[$i] . 'px;">'. date('d.m.yy', strtotime($problem->date_confirm)) .'</div>';
                }
                if ($problem->exist_confirm === 0) {
                    echo '<div class="border-gray" style="font-weight: 700; font-size: 13px; line-height: ' . $minHeight * $countGcps[$i] . 'px; height: ' . $minHeight * $countGcps[$i] . 'px;">'. date('d.m.yy', strtotime($problem->date_confirm)) .'</div>';
                }

                /*Если у существующей ГПС нет подтверждения то выводим следующее*/
                if ($problem->exist_confirm === null && !empty($problem)) {
                    echo '<div class="border-gray" style="line-height: ' . $minHeight * $countGcps[$i] . 'px; height: ' . $minHeight * $countGcps[$i] . 'px;"></div>';
                }
            }

        }

        echo '</td>';

        /*Выводим ГЦП*/
        echo '<td style="padding: 0;">';

        foreach ($problems as $i => $problem) {

            if ($problem->interview_id == $segment->interview->id) {


                /*Если не существует ГЦП*/
                if (empty($problem->confirm->gcps)){

                    if ($problem->exist_confirm === 0){

                        /*Если подтверждение ГПС отрицательное выводим следующее*/
                        echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">  </div>';

                    }elseif ($problem->exist_confirm === 1){

                        /*Если подтверждение ГПС положительное выводим следующее*/
                        echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">'. Html::img('@web/images/icons/fast forward.png', ['style' => ['width' => '18px', 'padding-bottom' => '3px',]]) .'</div>';

                    }elseif ($problem->exist_confirm === null){

                        /*Если подтверждение ГПС отсутствует или не закончено выводим следующее*/
                        echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">  </div>';
                    }
                }

                /*Если существует ГЦП выводим названия*/
                foreach ($offers as $j => $offer) {

                    if ($offer->confirm_problem_id == $problem->confirm->id) {
                        //debug($countMvps[$j]);
                        echo '<div class="border-gray" style="font-weight: 700; line-height: ' . $minHeight * $countMvps[$j] . 'px; height: ' . $minHeight * $countMvps[$j] . 'px;">
                        
                            <span data-toggle="tooltip" title="'.$offer->description.'">' . Html::a(Html::encode($offer->title), Url::to(['gcp/view', 'id' => $offer->id])) . '</span>
                        
                        </div>';
                    }
                }
            }
        }

        echo '</td>';

        /*Выводим подтверждение ГЦП*/
        echo '<td style="padding: 0;">';

        foreach ($problems as $i => $problem) {

            if ($problem->interview_id == $segment->interview->id) {

                if (empty($problem->confirm->gcps)){

                    /*Если не  существует ГЦП выводим следующее*/
                    echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">  </div>';
                }


                /*Если ГЦП существует*/
                foreach ($offers as $j => $offer) {

                    if ($offer->confirm_problem_id == $problem->confirm->id) {

                        if ($offer->exist_confirm === 1) {

                            /*Если подтверждение ГЦП положительное выводим следующее*/
                            echo '<div class="border-gray" style="color: green; font-size: 20px; line-height: ' . $minHeight * $countMvps[$j] . 'px; height: ' . $minHeight * $countMvps[$j] . 'px;">'. Html::img('@web/images/icons/green tick.png', ['style' => ['width' => '20px', 'padding-bottom' => '3px',]]) .'</div>';
                        }
                        if ($offer->exist_confirm === 0) {

                            /*Если подтверждение ГЦП отрицательное выводим следующее*/
                            echo '<div class="border-gray" style="color: red; font-size: 20px; line-height: ' . $minHeight * $countMvps[$j] . 'px; height: ' . $minHeight * $countMvps[$j] . 'px;">'. Html::img('@web/images/icons/cross delete.png', ['style' => ['width' => '22px', 'padding-bottom' => '3px',]]) .'</div>';
                        }
                        if ($offer->exist_confirm === null) {

                            /*Если подтверждение ГЦП отсутствует или не закончено выводим следующее*/
                            echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight * $countMvps[$j] . 'px; height: ' . $minHeight * $countMvps[$j] . 'px;">'. Html::img('@web/images/icons/fast forward.png', ['style' => ['width' => '18px', 'padding-bottom' => '3px',]]) .'</div>';
                        }
                    }
                }
            }
        }

        echo '</td>';


        /*Выводим дату подтверждения ГЦП*/
        echo '<td style="padding: 0;">';

        foreach ($problems as $i => $problem) {

            if ($problem->interview_id == $segment->interview->id) {

                if (empty($problem->confirm->gcps)){

                    /*Если не  существует ГЦП выводим следующее*/
                    echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">  </div>';
                }


                /*Если ГЦП существует*/
                foreach ($offers as $j => $offer) {

                    if ($offer->confirm_problem_id == $problem->confirm->id) {

                        if ($offer->exist_confirm === 1) {

                            /*Если подтверждение ГЦП положительное выводим следующее*/
                            echo '<div class="border-gray" style="font-weight: 700; font-size: 13px; line-height: ' . $minHeight * $countMvps[$j] . 'px; height: ' . $minHeight * $countMvps[$j] . 'px;">'. date('d.m.yy', strtotime($offer->date_confirm)) .'</div>';
                        }
                        if ($offer->exist_confirm === 0) {

                            /*Если подтверждение ГЦП отрицательное выводим следующее*/
                            echo '<div class="border-gray" style="font-weight: 700; font-size: 13px; line-height: ' . $minHeight * $countMvps[$j] . 'px; height: ' . $minHeight * $countMvps[$j] . 'px;">'. date('d.m.yy', strtotime($offer->date_confirm)) .'</div>';
                        }
                        if ($offer->exist_confirm === null) {

                            /*Если подтверждение ГЦП отсутствует или не закончено выводим следующее*/
                            echo '<div class="border-gray" style="line-height: ' . $minHeight * $countMvps[$j] . 'px; height: ' . $minHeight * $countMvps[$j] . 'px;"></div>';
                        }
                    }
                }
            }
        }

        echo '</td>';


        /*Выводим ГMVP*/
        echo '<td style="padding: 0;">';

        foreach ($problems as $i => $problem) {

            if ($problem->interview_id == $segment->interview->id) {

                if (empty($problem->confirm->gcps)){

                    /*Если отсутствует ГЦП выводим следующее*/
                    echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">  </div>';
                }

                /*Если ГЦП существует*/
                foreach ($offers as $j => $offer) {

                    if ($offer->confirm_problem_id == $problem->confirm->id) {

                        /*Если ГMVP не существует*/
                        if (empty($offer->confirm->mvps)){

                            if ($offer->exist_confirm === 0){

                                /*Если подтверждение ГЦП отрицательное выводим следующее*/
                                echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">  </div>';

                            }elseif ($offer->exist_confirm === 1){

                                /*Если подтверждение ГЦП положительное выводим следующее*/
                                echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">'. Html::img('@web/images/icons/fast forward.png', ['style' => ['width' => '18px', 'padding-bottom' => '3px',]]) .'</div>';

                            }elseif ($offer->exist_confirm === null){

                                /*Если подтверждение ГЦП отсутствует или не закончено выводим следующее*/
                                echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">  </div>';
                            }
                        }

                        /*Если ГMVP существует*/
                        foreach ($mvProducts as $mvProduct) {

                            if ($mvProduct->confirm_gcp_id == $offer->confirm->id) {

                                /*Выводим название соответствующего ГMVP*/
                                echo '<div class="border-gray" style="font-weight: 700; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">
                                
                                    <span data-toggle="tooltip" title="'.$mvProduct->description.'">' . Html::a(Html::encode($mvProduct->title), Url::to(['mvp/view', 'id' => $mvProduct->id])) . '</span>
                                
                                </div>';
                            }
                        }
                    }
                }
            }
        }

        echo '</td>';

        /*Выводим подтверждение ГMVP*/
        echo '<td style="padding: 0;">';

        foreach ($problems as $i => $problem) {

            if ($problem->interview_id == $segment->interview->id) {

                if (empty($problem->confirm->gcps)){

                    /*Если отсутствует ГЦП*/
                    echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">  </div>';
                }

                foreach ($offers as $j => $offer) {

                    if ($offer->confirm_problem_id == $problem->confirm->id) {

                        if (empty($offer->confirm->mvps)){

                            /*Если отсутствует ГMVP*/
                            echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">  </div>';
                        }

                        foreach ($mvProducts as $mvProduct) {
                            if ($mvProduct->confirm_gcp_id == $offer->confirm->id) {

                                if ($mvProduct->exist_confirm === 1) {

                                    /*Если подтверждение ГMVP положительное выводим следующее*/
                                    echo '<div class="border-gray" style="color: green; font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">'. Html::img('@web/images/icons/green tick.png', ['style' => ['width' => '20px', 'padding-bottom' => '3px',]]) .'</div>';
                                }
                                if ($mvProduct->exist_confirm === 0) {

                                    /*Если подтверждение ГMVP отрицательное выводим следующее*/
                                    echo '<div class="border-gray" style="color: red; font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">'. Html::img('@web/images/icons/cross delete.png', ['style' => ['width' => '22px', 'padding-bottom' => '3px',]]) .'</div>';
                                }
                                if ($mvProduct->exist_confirm === null) {

                                    /*Если подтверждение ГMVP отсутствует или не закончено выводим следующее*/
                                    echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">'. Html::img('@web/images/icons/fast forward.png', ['style' => ['width' => '18px', 'padding-bottom' => '3px',]]) .'</div>';
                                }
                            }
                        }
                    }
                }
            }
        }

        echo '</td>';



        /*Выводим дату подтверждения ГMVP*/
        echo '<td style="padding: 0;">';

        foreach ($problems as $i => $problem) {

            if ($problem->interview_id == $segment->interview->id) {

                if (empty($problem->confirm->gcps)){

                    /*Если отсутствует ГЦП*/
                    echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">  </div>';
                }

                foreach ($offers as $j => $offer) {

                    if ($offer->confirm_problem_id == $problem->confirm->id) {

                        if (empty($offer->confirm->mvps)){

                            /*Если отсутствует ГMVP*/
                            echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">  </div>';
                        }

                        foreach ($mvProducts as $mvProduct) {
                            if ($mvProduct->confirm_gcp_id == $offer->confirm->id) {

                                if ($mvProduct->exist_confirm === 1) {

                                    /*Если подтверждение ГMVP положительное выводим следующее*/
                                    echo '<div class="border-gray" style="font-weight: 700; font-size: 13px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">'. date('d.m.yy', strtotime($mvProduct->date_confirm)) .'</div>';
                                }
                                if ($mvProduct->exist_confirm === 0) {

                                    /*Если подтверждение ГMVP отрицательное выводим следующее*/
                                    echo '<div class="border-gray" style="font-weight: 700; font-size: 13px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">'. date('d.m.yy', strtotime($mvProduct->date_confirm)) .'</div>';
                                }
                                if ($mvProduct->exist_confirm === null) {

                                    /*Если подтверждение ГMVP отсутствует или не закончено выводим следующее*/
                                    echo '<div class="border-gray" style="line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;"> </div>';
                                }
                            }
                        }
                    }
                }
            }
        }

        echo '</td>';


        /*Выводим бизнес-модель*/
        echo '<td style="padding: 0;">';

        foreach ($problems as $i => $problem) {

            if ($problem->interview_id == $segment->interview->id) {

                if (empty($problem->confirm->gcps)){

                    /*Если не существует ГЦП выводим следующее*/
                    echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">  </div>';
                }

                /*Если существует ГЦП*/
                foreach ($offers as $j => $offer) {

                    if ($offer->confirm_problem_id == $problem->confirm->id) {

                        if (empty($offer->confirm->mvps)){

                            /*Если не существует ГMVP выводим следующее*/
                            echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">  </div>';
                        }


                        /*Если существует ГMVP*/
                        foreach ($mvProducts as $k => $mvProduct) {

                            if ($mvProduct->confirm_gcp_id == $offer->confirm->id) {

                                /*Если подтверждение ГMVP положительное*/
                                if ($mvProduct->exist_confirm === 1) {

                                    foreach ($confirmMvps as $confirmMvp){

                                        if ($confirmMvp->id == $mvProduct->confirm->id){

                                            if (empty($confirmMvp->business)){

                                                echo '<div class="border-gray" style="display: flex; justify-content: center; align-items: center; height: ' . $minHeight . 'px;">'. Html::a('Создать', ['business-model/create', 'id' => $mvProducts[$k]->confirm->id], ['class' => 'btn btn-success btn-sm', 'style' => ['font-weight' => '700', 'width' => '90px']]) .'</div>';
                                            }else{
                                                echo '<div class="border-gray" style="display: flex; justify-content: center; align-items: center; height: ' . $minHeight . 'px;">'. Html::a('Посмотреть', ['business-model/view', 'id' => $confirmMvp->business->id], ['class' => 'btn btn-success btn-sm', 'style' => ['font-weight' => '700', 'width' => '90px']]) .'</div>';

                                            }
                                        }
                                    }
                                }
                                if ($mvProduct->exist_confirm === 0) {

                                    /*Если подтверждение ГMVP отрицательное*/
                                    echo '<div class="border-gray" style="color: red; font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">  </div>';
                                }
                                if ($mvProduct->exist_confirm === null) {

                                    /*Если подтверждение ГMVP отсутствует или не закончено*/
                                    echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">  </div>';
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
