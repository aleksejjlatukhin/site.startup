<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Сводная таблица проекта ' . '"' . mb_strtolower($model->project_name) . '"';

?>

<?= $this->render('menu_user', [
    'user' => $user,
]) ?>


<div class="user-index col-md-9" style="padding-left: 0;">

    <h5 class="d-inline p-2" style="font-weight: 700;text-transform: uppercase;text-align: center; background-color: #0972a5;color: #fff; height: 50px; line-height: 50px;margin-bottom: 0;">
        <div class="row">

            <?= Html::encode($this->title) ?>

        </div>
    </h5>


    <table class="table table-bordered table">
        <thead>
        <tr>
            <th scope="col" style="text-align: center;width: 180px;padding: 30px 0;">Сегмент</th>
            <th scope="col" style="text-align: center;width: 180px;padding: 30px 0;">ГПС</th>
            <th scope="col" style="text-align: center;width: 80px;padding: 20px 0;">Подтв. ГПС</th>
            <th scope="col" style="text-align: center;width: 180px;padding: 30px 0;">ГЦП</th>
            <th scope="col" style="text-align: center;width: 80px;padding: 20px 0;">Подтв. ГЦП</th>
            <th scope="col" style="text-align: center;width: 180px;padding: 30px 0;">MVP</th>
            <th scope="col" style="text-align: center;width: 80px;padding: 20px 0;">Подтв. MVP</th>
            <th scope="col" style="text-align: center;width: 180px;padding: 30px 0;">Бизнес-модель</th>
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
            echo '<tr style="text-align: center"><td style="vertical-align: middle; height: ' . $minHeight *  $countM[$k] . 'px">' . Html::a(Html::encode($segment->name), Url::to(['segment/view', 'id' => $segment->id])). '</td>';

            /*Выводим ГПС*/
            echo '<td style="padding: 0;">';

            /*Если у сегмента нет ГПС выводим следующее*/
            if(empty($segment->interview->problems)){
                echo '<div class="border-gray" style="font-size: 20px; line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">'. Html::img('@web/images/icons/fast forward.png', ['style' => ['width' => '18px', 'padding-bottom' => '3px',]]) .'</div>';
            }

            /*Если у сегмента есть ГПС выводим их названия*/
            foreach ($problems as $i => $problem) {

                if ($problem->interview_id == $segment->interview->id) {

                    echo '<div class="border-gray" style="line-height: ' . $minHeight * $countGcps[$i] . 'px; height: ' . $minHeight * $countGcps[$i] . 'px;">' . Html::a(Html::encode($problem->title), Url::to(['generation-problem/view', 'id' => $problem->id])) . '</div>';
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
                            echo '<div class="border-gray" style="line-height: ' . $minHeight * $countMvps[$j] . 'px; height: ' . $minHeight * $countMvps[$j] . 'px;">' . Html::a(Html::encode($offer->title), Url::to(['gcp/view', 'id' => $offer->id])) . '</div>';
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
                                    echo '<div class="border-gray" style="line-height: ' . $minHeight . 'px; height: ' . $minHeight . 'px;">' . Html::a(Html::encode($mvProduct->title), Url::to(['mvp/view', 'id' => $mvProduct->id])) . '</div>';
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

                                                    echo '<div class="border-gray" style="display: flex; justify-content: center; align-items: center; height: ' . $minHeight . 'px;">'. Html::a('Создать', ['business-model/create', 'id' => $mvProducts[$k]->confirm->id], ['class' => 'btn btn-success btn-block', 'style' => ['width' => '120px', 'height' => '30px', 'line-height' => '15px']]) .'</div>';
                                                }else{
                                                    echo '<div class="border-gray" style="display: flex; justify-content: center; align-items: center; height: ' . $minHeight . 'px;">'. Html::a('Посмотреть', ['business-model/view', 'id' => $confirmMvp->business->id], ['class' => 'btn btn-success btn-block', 'style' => ['width' => '120px', 'height' => '30px', 'line-height' => '15px', 'text-align' => 'center',]]) .'</div>';

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



    <div style="display: flex; flex: auto; flex-wrap: wrap;">
        <div style="width: 400px;margin-right: 30px;">
            <p><span class="bolder">Сегмент</span> - целевой сегмент, по которому проводится исследование.</p>
            <p><span class="bolder">ГПС</span> - гипотеза проблемы целевого сегмента.</p>
            <p><span class="bolder">Подтв. ГПС</span> - подтверждение гипотезы проблемы целевого сегмента.</p>
            <p><span class="bolder">ГЦП</span> - гипотеза ценностного предложения.</p>
            <p><span class="bolder">Подтв. ГЦП</span> - подтверждение гипотезы ценностного предложения.</p>
        </div>
        <div style="width: 400px;">
            <p><span class="bolder">MVP</span>(Minimum Viable Product) — минимально жизнеспособный продукт, обладающий минимальными, но достаточными для удовлетворения первых потребителей функциями.</p>
            <p><span class="bolder">Подтв. MVP</span> - подтверждение MVP(см.выше).</p>
            <p><span class="bolder">Бизнес-модель</span> - построение бизнес-модели по Остервальдеру.</p>
        </div>
    </div>

    <div style="display: flex; flex: auto; flex-wrap: wrap; margin-bottom: 30px;">
        <p style="padding-right: 45px;"><?= Html::img('@web/images/icons/green tick.png', ['style' => ['width' => '20px', 'padding-bottom' => '3px',]])?> - этап подтвержден.</p>
        <p style="padding-right: 45px;"><?= Html::img('@web/images/icons/cross delete.png', ['style' => ['width' => '22px', 'padding-bottom' => '3px',]])?> - этап не подтвержден.</p>
        <p><?= Html::img('@web/images/icons/fast forward.png', ['style' => ['width' => '18px', 'padding-bottom' => '3px',]])?> - этап, который требует дальнейшей реализации.</p>
    </div>



    <script>

        $( ".catalog" ).dcAccordion({speed:300});

    </script>

</div>
