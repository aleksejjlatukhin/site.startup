<?php


namespace app\modules\admin\controllers;


use app\models\ConfirmMvp;
use app\models\Gcp;
use app\models\GenerationProblem;
use app\models\Mvp;
use app\models\Projects;
use app\models\Segment;

class ProjectsController extends AppAdminController
{

    public function actionIndex ()
    {

        $models = Projects::find()->all();

        //Мин. высота строки
        $minHeight = 50;
        //Массив данных по сегментам
        $segmentsM = [];
        //Массив данных по ГПС
        $problems = [];
        //Массив данных по ГЦП
        $offers = [];
        //Массив данных по MVP
        $mvProducts = [];
        //Массив данных по подтв. MVP
        $confirmMvps = [];

        //Кол-во строк по проектам
        $projectsRows = [];
        //Кол-во строк по сегментам
        $segmentsRows = [];
        //Кол-во строк по ГПС
        $problemsRows = [];
        //Кол-во строк по ГЦП
        $offersRows = [];

        //Кол-во ГПС в проектax
        $countProblems = [];
        //Кол-во подтв. ГПС в проектax
        $countConfirmProblems = [];
        //Кол-во ГЦП в проектах
        $countOffers = [];
        //Кол-во подт. ГЦП в проектах
        $countConfirmOffers = [];
        //Кол-во ГMVP в проектах
        $countMvp = [];
        //Кол-во подтв. ГMVP в проектах
        $countConfirmMvp = [];
        //Кол-во Бизнес-моделелей в проектах
        $countBusinessModel = [];


        foreach ($models as $model){

            //Кол-во строк по ГПС в проектах
            $mvps_of_gps_in_project = [];

            //Кол-во ГПС по проектам
            $count_of_gps_in_project = [];

            //Счетчик подтв. ГПС по проектам
            $count_of_confirm_gps_in_project = 0;

            //Счетчик ГЦП по проектам
            $count_of_offers_in_project = 0;

            //Счетчик подтв. ГЦП по проектам
            $count_of_confirm_offers_in_project = 0;

            //Счетчик ГMVP по проектам
            $count_of_mvp_in_project = 0;

            //Счетчик подтв. ГMVP по проектам
            $count_of_confirm_mvp_in_project = 0;

            //Счетчик БМ по проектам
            $count_of_business_model_in_project = 0;

            $segments = Segment::find()->where(['project_id' => $model->id])->all();
            foreach ($segments as $segment){

                if ($segment->project->id == $model->id){

                    $segmentsM[] = $segment;

                    //Счетчик строк по сегменту
                    $segmentSum = 0;

                    $generationProblems = GenerationProblem::find()->where(['interview_id' => $segment->interview->id])->all();

                    /*Если у выбранного сегмента нет ГПС*/
                    if (empty($generationProblems)){
                        $mvps_of_gps_in_project[] = 1;
                        $segmentSum++;

                    }else {

                        $count_of_gps_in_project[] = count($generationProblems);
                    }

                    foreach ($generationProblems as $k => $generationProblem){

                        /*Если ГПС относится к выбранному сегменту*/
                        if ($generationProblem->interview_id == $segment->interview->id) {

                            $problems[] = $generationProblem;

                            //Счетчик строк по ГПС
                            $gpsSum = 0;

                            /*Если подтверждения ГПС не существует*/
                            if (empty($generationProblem->confirm)) {
                                $mvps_of_gps_in_project[] = 1;
                                $segmentSum++;
                                $gpsSum++;


                                /*Если подтверждения ГПС существует*/
                            } else {

                                if ($generationProblem->exist_confirm !== null){

                                    $count_of_confirm_gps_in_project++;
                                }

                                /*Если у ГПС существуют ГЦП и они являются массивом*/
                                if (is_array($generationProblem->confirm->gcps) && !empty($generationProblem->confirm->gcps)) {

                                    /*Проходимся циклом по ГЦП*/
                                    $gcps = Gcp::find()->where(['confirm_problem_id' => $generationProblem->confirm->id])->all();
                                    foreach ($gcps as $i => $gcp) {

                                        $offers[] = $gcp;

                                        //Счетчик строк по ГЦП
                                        $offerSum = 0;

                                        $count_of_offers_in_project++;

                                        if ($gcp->exist_confirm !== null){

                                            $count_of_confirm_offers_in_project++;
                                        }

                                        /*Если у выбранной ГЦП существуют ГMVP и они являются массивом и ГЦП относится к выбранной ГПС*/
                                        if (is_array($gcp->confirm->mvps) && !empty($gcp->confirm->mvps) && $gcp->confirm_problem_id == $generationProblem->confirm->id) {

                                            $mvps_of_gps_in_project[$k] += count($gcp->confirm->mvps);
                                            $segmentSum += count($gcp->confirm->mvps);
                                            $gpsSum += count($gcp->confirm->mvps);
                                            $offerSum += count($gcp->confirm->mvps);

                                            /*Если у выбранной ГЦП не существуют ГMVP и ГЦП относится к выбранной ГПС*/
                                        } elseif (empty($gcp->confirm->mvps) && $gcp->confirm_problem_id == $generationProblem->confirm->id) {

                                            $mvps_of_gps_in_project[$k]++;
                                            $segmentSum++;
                                            $gpsSum++;
                                            $offerSum++;
                                        }


                                        $mvps = Mvp::find()->where(['confirm_gcp_id' => $gcp->confirm->id])->all();
                                        foreach ($mvps as $mvp){

                                            $count_of_mvp_in_project++;

                                            if ($mvp->exist_confirm !== null){

                                                $count_of_confirm_mvp_in_project++;
                                            }

                                            $mvProducts[] = $mvp;
                                            $confMvp = ConfirmMvp::find()->where(['mvp_id' => $mvp->id])->one();

                                            $confirmMvps[] = $confMvp;

                                            if (!empty($confMvp->business)){

                                                $count_of_business_model_in_project++;
                                            }
                                        }

                                        $offersRows[] = $offerSum;
                                    }
                                }
                            }

                            /*Если у ГПС не существует ГЦП*/
                            if (is_array($generationProblem->confirm->gcps) && empty($generationProblem->confirm->gcps)) {
                                $mvps_of_gps_in_project[] = 1;
                                $segmentSum++;
                                $gpsSum++;
                            }
                            $problemsRows[] = $gpsSum;
                        }
                    }
                }
                $segmentsRows[] = $segmentSum;
            }

            $projectsRows[] = array_sum($mvps_of_gps_in_project);
            $countProblems[] = array_sum($count_of_gps_in_project);
            $countConfirmProblems[] = $count_of_confirm_gps_in_project;
            $countOffers[] = $count_of_offers_in_project;
            $countConfirmOffers[] = $count_of_confirm_offers_in_project;
            $countMvp[] = $count_of_mvp_in_project;
            $countConfirmMvp[] = $count_of_confirm_mvp_in_project;
            $countBusinessModel[] = $count_of_business_model_in_project;
        }

        //debug($countBusinessModel);



        return $this->render('index', [
            'models' => $models,
            'minHeight' => $minHeight,
            'projectsRows' => $projectsRows,
            'segmentsM' => $segmentsM,
            'segmentsRows' => $segmentsRows,
            'problems' => $problems,
            'problemsRows' => $problemsRows,
            'offers' => $offers,
            'offersRows' => $offersRows,
            'mvProducts' => $mvProducts,
            'confirmMvps' => $confirmMvps,
            'countProblems' => $countProblems,
            'countConfirmProblems' => $countConfirmProblems,
            'countOffers' => $countOffers,
            'countConfirmOffers' => $countConfirmOffers,
            'countMvp' => $countMvp,
            'countConfirmMvp' => $countConfirmMvp,
            'countBusinessModel' => $countBusinessModel,

        ]);
    }
}