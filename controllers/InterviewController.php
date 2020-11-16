<?php

namespace app\controllers;

use app\models\DescInterview;
use app\models\forms\FormCreateProblem;
use app\models\forms\FormUpdateConfirmSegment;
use app\models\GenerationProblem;
use app\models\Projects;
use app\models\Questions;
use app\models\Respond;
use app\models\Segment;
use app\models\forms\UpdateRespondForm;
use app\models\User;
use kartik\mpdf\Pdf;
use Yii;
use app\models\Interview;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;


class InterviewController extends AppController
{

    public function beforeAction($action)
    {

        if (in_array($action->id, ['view'])){

            $model = Interview::findOne(Yii::$app->request->get());
            $segment = Segment::find()->where(['id' => $model->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['update']) || in_array($action->id, ['delete'])){

            $model = Interview::findOne(Yii::$app->request->get());
            $segment = Segment::find()->where(['id' => $model->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id)  || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){

            $segment = Segment::findOne(Yii::$app->request->get());
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id)  || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['save-interview'])){

            $segment = Segment::findOne(Yii::$app->request->get());
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id)  || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        } elseif (in_array($action->id, ['add-questions'])){

            $model = Interview::findOne(Yii::$app->request->get());
            $segment = Segment::find()->where(['id' => $model->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        } elseif (in_array($action->id, ['delete-question'])){

            $question = Questions::findOne(Yii::$app->request->get());
            $interview = Interview::find()->where(['id' => $question->interview_id])->one();
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id)  || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }else{
            return parent::beforeAction($action);
        }

    }


    /**
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        $model = Interview::find()->with('questions')->where(['id' => $id])->one();
        $formUpdateConfirmSegment = new FormUpdateConfirmSegment($id);
        $segment = Segment::find()->where(['id' => $model->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $responds = Respond::find()->where(['interview_id' => $id])->all();

        $queryResponds = Respond::find()->where(['interview_id' => $id]);
        $dataProviderQueryResponds = new ActiveDataProvider([
            'query' => $queryResponds,
            'pagination' => false,
            //'pagination' => ['pageSize' => 10],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                    //'name' => SORT_ASC,
                ]
            ],
        ]);

        $questions = Questions::find()->where(['interview_id' => $id])->all();

        $newQuestion = new Questions();
        $newQuestion->interview_id = $id;

        //Список вопросов для добавления к списку программы
        $queryQuestions = $model->queryQuestionsGeneralList();
        $queryQuestions = ArrayHelper::map($queryQuestions,'title','title');

        $newRespond = new Respond();
        $newRespond->interview_id = $model->id;

        $updateRespondForms = [];
        $createDescInterviewForms = [];
        $updateDescInterviewForms = [];
        foreach ($responds as $i => $respond) {

            $updateRespondForms[] = new UpdateRespondForm($respond->id);

            $createDescInterviewForms[] = new DescInterview();

            $updateDescInterviewForms[] = $respond->descInterview;
        }


        return $this->render('view', [
            'model' => $model,
            'formUpdateConfirmSegment' => $formUpdateConfirmSegment,
            'segment' => $segment,
            'project' => $project,
            'responds' => $responds,
            'dataProviderQueryResponds' => $dataProviderQueryResponds,
            'questions' => $questions,
            'newQuestion' => $newQuestion,
            'newRespond' => $newRespond,
            'queryQuestions' => $queryQuestions,
            'updateRespondForms' => $updateRespondForms,
            'createDescInterviewForms' => $createDescInterviewForms,
            'updateDescInterviewForms' => $updateDescInterviewForms,
        ]);
    }


    /**
     * Проверка данных подтверждения на этапе генерации ГПС
     * @param $id
     * @return array
     */
    public function actionDataAvailabilityForNextStep($id)
    {
        $model = Interview::findOne($id);

        $count_descInterview = 0;
        $count_positive = 0;

        foreach ($model->responds as $respond) {

            if ($respond->descInterview){
                $count_descInterview++;

                if ($respond->descInterview->status == 1){
                    $count_positive++;
                }
            }
        }

        if(Yii::$app->request->isAjax) {

            if ((count($model->responds) == $count_descInterview && $model->count_positive <= $count_positive) || (!empty($model->problems)  && $model->count_positive <= $count_positive)) {

                $response =  [
                    'success' => true,
                    'renderAjax' => $this->renderAjax('/generation-problem/create', [
                        'interview' => $model,
                        'model' => new FormCreateProblem(),
                        'responds' => Respond::find()->with('descInterview')
                            ->leftJoin('desc_interview', '`desc_interview`.`respond_id` = `responds`.`id`')
                            ->where(['interview_id' => $id, 'desc_interview.status' => '1'])->all(),
                    ]),
                ];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;

            }else{

                $response = ['error' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }
        }
    }



    /**
     * Завершение подтверждения сегмента и переход на следующий этап
     * @param $id
     * @return array
     */
    public function actionMovingNextStage($id)
    {
        $model = Interview::findOne($id);
        $segment = $model->segment;

        $count_descInterview = 0;
        $count_positive = 0;

        foreach ($model->responds as $respond) {

            if ($respond->descInterview){
                $count_descInterview++;

                if ($respond->descInterview->status == 1){
                    $count_positive++;
                }
            }
        }

        if(Yii::$app->request->isAjax) {

            if (count($model->responds) > $count_descInterview && empty($model->problems)) {

                $response = ['not_completed_descInterviews' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;

            } elseif ((count($model->responds) == $count_descInterview && $model->count_positive <= $count_positive) || (!empty($model->problems))) {

                $response =  [
                    'success' => true,
                    'exist_confirm' => $segment->exist_confirm,
                ];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;

            }else{

                $response = ['error' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }
        }
    }


    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionCreate($id)
    {
        $segment = Segment::findOne($id);
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $model = new Interview();
        $model->segment_id = $id;

        if (empty($segment)){
            //Отсутствуют данные сегмента
            return $this->redirect(['/segment/index', 'id' => $project->id]);
        }

        $modelInterview = Interview::find()->where(['segment_id' => $id])->one();
        if (!empty($modelInterview)){
            //Если у сегмента создана программа подтверждения, то перейти на страницу подтверждения
            return $this->redirect(['/interview/view', 'id' => $modelInterview->id]);
        }


        return $this->render('create', [
            'model' => $model,
            'segment' => $segment,
            'project' => $project,
        ]);
    }


    /**
     * @param $id
     * @return array|\yii\web\Response
     */
    public function actionSaveInterview($id)
    {
        $segment = Segment::findOne($id);
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $model = new Interview();
        $model->segment_id = $id;
        $user = User::find()->where(['id' => $project->user_id])->one();

        $modelInterview = Interview::find()->where(['segment_id' => $id])->one();
        if (!empty($modelInterview)){ return $this->redirect(['/interview/view', 'id' => $modelInterview->id]); }
        if (empty($segment)){ return $this->redirect(['/segment/index', 'id' => $project->id]); }


        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model->count_respond >= $model->count_positive  && $model->count_respond > 0 && $model->count_positive > 0){

                    if ($model->save()){

                        $interviews_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                            mb_convert_encoding($this->translit($project->project_name), "windows-1251") . '/segments/' .
                            mb_convert_encoding($this->translit($segment->name), "windows-1251") . '/interviews/';
                        if (!file_exists($interviews_dir)) {
                            mkdir($interviews_dir, 0777);
                        }

                        $generation_problems_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                            mb_convert_encoding($this->translit($project->project_name), "windows-1251") . '/segments/' .
                            mb_convert_encoding($this->translit($segment->name), "windows-1251") . '/generation problems/';
                        if (!file_exists($generation_problems_dir)) {
                            mkdir($generation_problems_dir, 0777);
                        }

                        //Создание респондентов по заданному значению count_respond
                        $model->createRespond();

                        //Вопросы, которые будут добавлены по-умолчанию
                        $model->addQuestionDefault('Как и посредством какого инструмента / процесса вы справляетесь с задачей?');
                        $model->addQuestionDefault('Что нравится / не нравится в текущем положении вещей?');
                        $model->addQuestionDefault('Вас беспокоит данная ситуация?');
                        $model->addQuestionDefault('Что вы пытались с этим сделать?');
                        $model->addQuestionDefault('Что вы делали с этим в последний раз, какие шаги предпринимали?');
                        $model->addQuestionDefault('Если ничего не делали, то почему?');
                        $model->addQuestionDefault('Сколько денег / времени на это тратится сейчас?');
                        $model->addQuestionDefault('Есть ли деньги на решение сложившейся ситуации сейчас?');
                        $model->addQuestionDefault('Что влияет на решение о покупке продукта?');
                        $model->addQuestionDefault('Как принимается решение о покупке?');

                        $project->updated_at = time();

                        if ($project->save()) {

                            $response =  [
                                'success' => true,
                                'id' => $model->id,
                            ];
                            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            \Yii::$app->response->data = $response;
                            return $response;

                        }
                    }
                }else{

                    $response =  [
                        'error' => true,
                    ];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;

                }
            }
        }
    }

    /**
     * Страница со списком вопросов
     * @param $id
     * @return string
     */
    public function actionAddQuestions($id)
    {
        $model = Interview::find()->with('questions')->where(['id' => $id])->one();
        $formUpdateConfirmSegment = new FormUpdateConfirmSegment($id);
        $segment = Segment::find()->where(['id' => $model->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $dataProviderQuestions = new ActiveDataProvider([
            'query' => Questions::find()->where(['interview_id' => $id]),
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                    //'title' => SORT_ASC,
                ]
            ],
        ]);

        $questions = Questions::find()->where(['interview_id' => $id])->all();
        $newQuestion = new Questions();
        $newQuestion->interview_id = $id;

        //Список вопросов для добавления к списку программы
        $queryQuestions = $model->queryQuestionsGeneralList();
        $queryQuestions = ArrayHelper::map($queryQuestions,'title','title');

        return $this->render('add-questions', [
            'dataProviderQuestions' => $dataProviderQuestions,
            'questions' => $questions,
            'newQuestion' => $newQuestion,
            'queryQuestions' => $queryQuestions,
            'model' => $model,
            'formUpdateConfirmSegment' => $formUpdateConfirmSegment,
            'segment' => $segment,
            'project' => $project,
        ]);
    }


    /**
     * @param $id
     * @return array
     */
    public function actionUpdate ($id)
    {
        $model = new FormUpdateConfirmSegment($id);
        $confirm_segment = Interview::findOne($id);
        $segment = Segment::find()->where(['id' => $confirm_segment->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model->count_respond >= $model->count_positive && $model->count_respond > 0 && $model->count_positive > 0){

                    if ($update_confirm_segment = $model->update()){

                        $project->updated_at = time();

                        if ($project->save()){

                            $descInterviews = [];
                            foreach ($update_confirm_segment->responds as $respond) {
                                if($respond->descInterview) {
                                    $descInterviews[] = $respond->descInterview;
                                }
                            }

                            $response = [
                                'model' => $update_confirm_segment,
                                'responds' => $update_confirm_segment->responds,
                                'descInterviews' => $descInterviews,
                                'problems' => $update_confirm_segment->problems,
                            ];
                            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            \Yii::$app->response->data = $response;
                            return $response;
                        }
                    }
                }else{

                    $response = ['error' => true];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
    }


    /**
     * Метод для добавления новых вопросов
     * @param $id
     * @return array
     */
    public function actionAddQuestion($id)
    {
        $model = new Questions();
        $model->interview_id = $id;

        $confirm_segment = Interview::findOne($id);
        $segment = Segment::find()->where(['id' => $confirm_segment->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        if ($model->load(Yii::$app->request->post())){

            if(Yii::$app->request->isAjax) {

                if ($model->save()){

                    $project->updated_at = time();
                    $project->save();

                    $interviewNew = Interview::findOne($id);
                    $questions = $interviewNew->questions;

                    //Добавляем вопрос в общую базу вопросов
                    $interviewNew->addQuestionToGeneralList($model->title);
                    //Передаем обновленный список вопросов для добавления в программу
                    $queryQuestions = $interviewNew->queryQuestionsGeneralList();

                    $response = [
                        'model' => $model,
                        'questions' => $questions,
                        'queryQuestions' => $queryQuestions,
                    ];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
    }


    /**
     * @param $id
     * @return array
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteQuestion ($id)
    {
        $model = Questions::findOne($id);
        $interview = Interview::find()->where(['id' => $model->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        if(Yii::$app->request->isAjax) {

            if ($model->delete()){

                $project->updated_at = time();
                $project->save();

                $interviewNew = Interview::find()->where(['id' => $model->interview_id])->one();
                $questions = $interviewNew->questions;

                //Передаем обновленный список вопросов для добавления в программу
                $queryQuestions = $interviewNew->queryQuestionsGeneralList();

                $response = [
                    'questions' => $questions,
                    'queryQuestions' => $queryQuestions,
                ];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }
        }
    }


    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionNotExistConfirm($id)
    {
        $model = Interview::findOne($id);
        $segment = Segment::find()->where(['id' => $model->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        if ($segment->exist_confirm === 0) {
            return $this->redirect(['/segment/index', 'id' => $project->id]);

        }else {

            $segment->exist_confirm = 0;
            $segment->time_confirm = time();

            if ($segment->save()){

                $project->updated_at = time();
                if ($project->save()){
                    return $this->redirect(['/segment/index', 'id' => $project->id]);
                }
            }
        }
    }


    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionExistConfirm($id)
    {
        $model = Interview::findOne($id);
        $segment = Segment::find()->where(['id' => $model->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $segment->exist_confirm = 1;
        $segment->time_confirm = time();

        if ($segment->save()){

            $project->updated_at = time();
            if ($project->save()){
                return $this->redirect(['/generation-problem/index', 'id' => $id]);
            }
        }
    }


    /**
     * @param $id
     * @return mixed
     * @throws \Mpdf\MpdfException
     * @throws \setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException
     * @throws \setasign\Fpdi\PdfParser\PdfParserException
     * @throws \setasign\Fpdi\PdfParser\Type\PdfTypeException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionMpdfDataResponds($id)
    {
        $model = Interview::findOne($id);
        $responds = $model->responds;

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('/interview/viewpdf', ['model' => $model, 'responds' => $responds]);

        $destination = Pdf::DEST_BROWSER;
        //$destination = Pdf::DEST_DOWNLOAD;

        $segment_name = $model->segment->name;
        if (mb_strlen($segment_name) > 25) {
            $segment_name = mb_substr($segment_name, 0, 25) . '...';
        }

        $filename = 'Подтверждение сегмента «'.$segment_name.'». Таблица респондентов.pdf';

        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            //'format' => Pdf::FORMAT_TABLOID,
            // portrait orientation
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            //'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => $destination,
            'filename' => $filename,
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            //'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssFile' => '@app/web/css/style.css',
            // any css to be embedded if required
            //'cssInline' => '.business-model-view-export {color: #3c3c3c;};',
            'marginFooter' => 5,
            'defaultFont' => 'RobotoCondensed-Light',
            // call mPDF methods on the fly
            'methods' => [
                'SetTitle' => ['Респонденты для подтверждения гипотезы сегмента «'.$model->segment->name.'»'],
                'SetHeader' => ['<div style="color: #3c3c3c;">Респонденты для подтверждения гипотезы сегмента «'.$segment_name.'»</div>||<div style="color: #3c3c3c;">Сгенерировано: ' . date("H:i d.m.Y") . '</div>'],
                'SetFooter' => ['<div style="color: #3c3c3c;">Страница {PAGENO}</div>'],
                //'SetSubject' => 'Generating PDF files via yii2-mpdf extension has never been easy',
                //'SetAuthor' => 'Kartik Visweswaran',
                //'SetCreator' => 'Kartik Visweswaran',
                //'SetKeywords' => 'Krajee, Yii2, Export, PDF, MPDF, Output, Privacy, Policy, yii2-mpdf',
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }


    /**
     * Deletes an existing Interview model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    /*public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $segment = Segment::find()->where(['id' => $model->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $user = User::find()->where(['id' => $project->user_id])->one();
        $_user = Yii::$app->user->identity;

        //Удаление доступно только проектанту, который создал данную модель
        if ($user->id != $_user['id']){
            Yii::$app->session->setFlash('error', 'У Вас нет прав на данное действие!');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $responds = Respond::find()->where(['interview_id' => $model->id])->all();
        $generationProblems = GenerationProblem::find()->where(['interview_id' => $model->id])->all();
        $project->update_at = date('Y:m:d');

        if ($project->save()){

            $pathDeleteInt = \Yii::getAlias(UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251")
                    . '/' . mb_convert_encoding($this->translit($project->project_name), "windows-1251") .
                    '/segments/' . mb_convert_encoding($this->translit($segment->name), "windows-1251")) . '/interviews';
            $this->delTree($pathDeleteInt);

            $pathDeleteGps = \Yii::getAlias(UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251")
                    . '/' . mb_convert_encoding($this->translit($project->project_name), "windows-1251") .
                    '/segments/' . mb_convert_encoding($this->translit($segment->name), "windows-1251")) . '/generation problems';
            $this->delTree($pathDeleteGps);

            $pathDeleteFeedbacks = \Yii::getAlias(UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251")
                    . '/' . mb_convert_encoding($this->translit($project->project_name), "windows-1251") .
                    '/segments/' . mb_convert_encoding($this->translit($segment->name), "windows-1251")) . '/feedbacks';
            $this->delTree($pathDeleteFeedbacks);

            foreach ($responds as $respond){
                $descInterview = $respond->descInterview;

                if (!empty($descInterview)){
                    $descInterview->delete();
                }
            }


            if (!empty($generationProblems)){
                foreach ($generationProblems as $generationProblem){
                    if (!empty($generationProblem->confirm)){
                        $confirmProblem = $generationProblem->confirm;


                        if (!empty($confirmProblem->feedbacks)){
                            FeedbackExpertConfirm::deleteAll(['confirm_problem_id' => $confirmProblem->id]);
                        }


                        if (!empty($confirmProblem->responds)){
                            $respondsConfirm = $confirmProblem->responds;
                            foreach ($respondsConfirm as $respondConfirm){
                                if (!empty($respondConfirm->descInterview)){
                                    $descInterviewConfirm = $respondConfirm->descInterview;
                                    $descInterviewConfirm->delete();
                                }
                            }
                            RespondsConfirm::deleteAll(['confirm_problem_id' => $confirmProblem->id]);
                        }

                        $confirmProblem->delete();
                    }
                }
            }

            Questions::deleteAll(['interview_id' => $id]);
            Respond::deleteAll(['interview_id' => $id]);
            FeedbackExpert::deleteAll(['interview_id' => $id]);
            GenerationProblem::deleteAll(['interview_id' => $id]);

            Yii::$app->session->setFlash('error', "Ваше интервью удалено, создайте новое интервью!");

            $model->delete();

            return $this->redirect(['create', 'id' => $model->segment_id]);
        }
    }*/

    /**
     * Finds the Interview model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Interview the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Interview::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
