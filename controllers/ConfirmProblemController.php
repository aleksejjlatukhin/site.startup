<?php

namespace app\controllers;

use app\models\DescInterviewConfirm;
use app\models\forms\FormCreateGcp;
use app\models\FormUpdateConfirmProblem;
use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Projects;
use app\models\QuestionsConfirmProblem;
use app\models\Respond;
use app\models\RespondsConfirm;
use app\models\Segment;
use app\models\forms\UpdateRespondConfirmForm;
use app\models\User;
use kartik\mpdf\Pdf;
use Yii;
use app\models\ConfirmProblem;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;


class ConfirmProblemController extends AppController
{

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\HttpException
     */
    public function beforeAction($action)
    {

        if (in_array($action->id, ['view'])){

            $model = ConfirmProblem::findOne(Yii::$app->request->get());
            $problem = GenerationProblem::find()->where(['id' => $model->gps_id])->one();
            $project = Projects::find()->where(['id' => $problem->project->id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['update']) || in_array($action->id, ['delete'])){

            $model = ConfirmProblem::findOne(Yii::$app->request->get());
            $problem = GenerationProblem::find()->where(['id' => $model->gps_id])->one();
            $project = Projects::find()->where(['id' => $problem->project->id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){

            $problem = GenerationProblem::findOne(Yii::$app->request->get());
            $interview = Interview::find()->where(['id' => $problem->interview_id])->one();
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['save-confirm-problem'])){

            $problem = GenerationProblem::findOne(Yii::$app->request->get());
            $interview = Interview::find()->where(['id' => $problem->interview_id])->one();
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

        } elseif (in_array($action->id, ['add-questions'])){

            $model = ConfirmProblem::findOne(Yii::$app->request->get());
            $problem = GenerationProblem::find()->where(['id' => $model->gps_id])->one();
            $project = Projects::find()->where(['id' => $problem->project->id])->one();

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

            $question = QuestionsConfirmProblem::findOne(Yii::$app->request->get());
            $confirm_problem = ConfirmProblem::find()->where(['id' => $question->confirm_problem_id])->one();
            $problem = GenerationProblem::find()->where(['id' => $confirm_problem->gps_id])->one();
            $project = Projects::find()->where(['id' => $problem->project->id])->one();

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
        $model = ConfirmProblem::findOne($id);
        $formUpdateConfirmProblem = new FormUpdateConfirmProblem($id);
        $generationProblem = GenerationProblem::find()->where(['id' => $model->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $responds = RespondsConfirm::find()->where(['confirm_problem_id' => $id])->all();
        $questions = QuestionsConfirmProblem::find()->where(['confirm_problem_id' => $id])->all();

        $newQuestion = new QuestionsConfirmProblem();
        $newQuestion->confirm_problem_id = $id;

        //Список вопросов для добавления к списку программы
        $queryQuestions = $model->queryQuestionsGeneralList();
        $queryQuestions = ArrayHelper::map($queryQuestions,'title','title');

        $newRespond = new RespondsConfirm();
        $newRespond->confirm_problem_id = $model->id;

        $updateRespondForms = [];
        $createDescInterviewForms = [];
        $updateDescInterviewForms = [];

        foreach ($responds as $i => $respond) {

            $updateRespondForms[] = new UpdateRespondConfirmForm($respond->id);

            $createDescInterviewForms[] = new DescInterviewConfirm();

            $updateDescInterviewForms[] = $respond->descInterview;
        }


        return $this->render('view', [
            'model' => $model,
            'formUpdateConfirmProblem' => $formUpdateConfirmProblem,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
            'responds' => $responds,
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
     * Проверка данных подтверждения на этапе разработки ГЦП
     * @param $id
     * @return array
     */
    public function actionDataAvailabilityForNextStep($id)
    {
        $model = ConfirmProblem::findOne($id);

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
            if ((count($model->responds) == $count_descInterview && $model->count_positive <= $count_positive) || (!empty($model->gcps)  && $model->count_positive <= $count_positive)) {

                $response =  [
                    'success' => true,
                    'renderAjax' => $this->renderAjax('/gcp/create', [
                        'confirmProblem' => $model,
                        'model' => new FormCreateGcp(),
                        'segment' => $model->problem->segment,
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
     * Завершение подтверждения ГПС и переход на следующий этап
     * @param $id
     * @return array
     */
    public function actionMovingNextStage($id)
    {
        $model = ConfirmProblem::findOne($id);
        $problem = $model->problem;

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

            if (count($model->responds) > $count_descInterview && empty($model->gcps)) {

                $response = ['not_completed_descInterviews' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;

            }if ((count($model->responds) == $count_descInterview && $model->count_positive <= $count_positive) || (!empty($model->gcps))) {

                $response =  [
                    'success' => true,
                    'exist_confirm' => $problem->exist_confirm,
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
     * @return \yii\web\Response
     */
    public function actionNotExistConfirm($id)
    {
        $model = ConfirmProblem::findOne($id);
        $generationProblem = GenerationProblem::find()->where(['id' => $model->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        if ($generationProblem->exist_confirm === 0) {

            return $this->redirect(['/generation-problem/index', 'id' => $interview->id]);
        }else {

            $generationProblem->exist_confirm = 0;
            $generationProblem->time_confirm = time();

            if ($generationProblem->save()){

                $project->updated_at = time();
                if ($project->save()){
                    return $this->redirect(['/generation-problem/index', 'id' => $interview->id]);
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
        $model = ConfirmProblem::findOne($id);
        $generationProblem = GenerationProblem::find()->where(['id' => $model->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $generationProblem->exist_confirm = 1;
        $generationProblem->time_confirm = time();

        if ($generationProblem->save()){

            $project->updated_at = time();
            if ($project->save()){
                return $this->redirect(['/gcp/index', 'id' => $model->id]);
            }
        }
    }


    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionCreate($id)
    {
        $model = new ConfirmProblem();
        $model->gps_id = $id;

        $generationProblem = GenerationProblem::findOne($id);
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $responds = Respond::find()->where(['interview_id' => $interview->id])->all();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        if (!empty($generationProblem->confirm)){
            return $this->redirect(['view', 'id' => $generationProblem->confirm->id]);
        }

        $respondsPre = []; // представители сегмента
        foreach ($responds as $respond){
            if ($respond->descInterview->status == 1){
                $respondsPre[] = $respond;
            }
        }

        $model->count_respond = count($respondsPre);

        return $this->render('create', [
            'model' => $model,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }


    /**
     * @param $id
     * @return array|\yii\web\Response
     */
    public function actionSaveConfirmProblem($id)
    {
        $model = new ConfirmProblem();
        $model->gps_id = $id;

        $generationProblem = GenerationProblem::findOne($id);
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $responds = Respond::find()->where(['interview_id' => $interview->id])->all();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $user = User::find()->where(['id' => $project->user_id])->one();


        if (!empty($generationProblem->confirm)){
            return $this->redirect(['view', 'id' => $generationProblem->confirm->id]);
        }


        $respondsPre = []; // представители сегмента
        foreach ($responds as $respond){
            if ($respond->descInterview->status == 1){
                $respondsPre[] = $respond;
            }
        }

        $model->count_respond = count($respondsPre);


        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model->count_respond >= $model->count_positive && $model->count_positive > 0){

                    if ($model->save()){

                        $gcps_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                            mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
                            mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/generation problems/'
                            . mb_convert_encoding($this->translit($generationProblem->title) , "windows-1251") . '/gcps/';

                        $gcps_dir = mb_strtolower($gcps_dir, "windows-1251");

                        if (!file_exists($gcps_dir)){
                            mkdir($gcps_dir, 0777);
                        }

                        //Создание респондентов для программы подтверждения ГПС из представителей сегмента
                        $model->createRespondConfirm($responds);

                        //Вопросы, которые будут добавлены по-умолчанию
                        $model->addQuestionDefault('Какими функциями должен обладать продукт вашей мечты?');
                        $model->addQuestionDefault('Расскажите поподробнее, каков алгоритм вашей работы?');
                        $model->addQuestionDefault('Почему вас это беспокоит?');
                        $model->addQuestionDefault('Каковы последствия этой ситуации?');
                        $model->addQuestionDefault('Расскажите поподробнее, что произошло в последний раз?');
                        $model->addQuestionDefault('Что еще пытались сделать?');
                        $model->addQuestionDefault('Кто будет финансировать покупку?');
                        $model->addQuestionDefault('С кем еще мне следует переговорить?');
                        $model->addQuestionDefault('Есть ли еще вопросы, которые мне следовало задать?');
                        $model->addQuestionDefault('Пытались ли найти решение?');
                        $model->addQuestionDefault('Эти решения оказались недостаточно эффективными?');
                        $model->addQuestionDefault('Как справляются с задачей сейчас и сколько денег тратят?');
                        $model->addQuestionDefault('Сколько времени это занимает?');
                        $model->addQuestionDefault('Продемонстрировать как они выполняют работу или другую деятельность?');
                        $model->addQuestionDefault('Что в этом нравится и что нет?');
                        $model->addQuestionDefault('Какие еще инструменты и процессы пробовали пока не остановились на этом?');
                        $model->addQuestionDefault('Ищут ли активно сейчас чем это можно заменить?');
                        $model->addQuestionDefault('Если да, то в чем проблема?');
                        $model->addQuestionDefault('Если не ищут, то почему?');
                        $model->addQuestionDefault('На чем теряют деньги, используя текущие инструменты?');

                        $project->updated_at = time();

                        if ($project->save()){

                            $response =  [
                                'success' => true,
                                'id' => $model->id,
                            ];
                            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            \Yii::$app->response->data = $response;
                            return $response;
                        }
                    }
                } else{

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
        $confirmProblem = ConfirmProblem::find()->with('questions')->where(['id' => $id])->one();
        $formUpdateConfirmProblem = new FormUpdateConfirmProblem($id);
        $problem = GenerationProblem::findOne(['id' => $confirmProblem->gps_id]);
        $interview = Interview::findOne(['id' => $problem->interview_id]);
        $segment = Segment::findOne(['id' => $problem->segment_id]);
        $project = Projects::findOne(['id' => $problem->project_id]);
        $questions = QuestionsConfirmProblem::find()->where(['confirm_problem_id' => $id])->all();

        $newQuestion = new QuestionsConfirmProblem();
        $newQuestion->confirm_problem_id = $id;

        //Список вопросов для добавления к списку программы
        $queryQuestions = $confirmProblem->queryQuestionsGeneralList();
        $queryQuestions = ArrayHelper::map($queryQuestions,'title','title');

        return $this->render('add-questions', [
            'formUpdateConfirmProblem' => $formUpdateConfirmProblem,
            'questions' => $questions,
            'newQuestion' => $newQuestion,
            'queryQuestions' => $queryQuestions,
            'confirmProblem' => $confirmProblem,
            'problem' => $problem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * Метод для добавления новых вопросов
     * @param $id
     * @return array
     */
    public function actionAddQuestion($id)
    {
        $confirmProblem = ConfirmProblem::findOne($id);
        $problem = GenerationProblem::findOne(['id' => $confirmProblem->gps_id]);
        $project = Projects::findOne(['id' => $problem->project_id]);
        $model = new QuestionsConfirmProblem();
        $model->confirm_problem_id = $id;


        if ($model->load(Yii::$app->request->post())){

            if(Yii::$app->request->isAjax) {

                if ($model->save()){

                    $confirmProblemNew = ConfirmProblem::findOne($id);
                    $questions = $confirmProblemNew->questions;

                    //Создание пустого ответа для нового вопроса для каждого респондента
                    $confirmProblemNew->addAnswerConfirmProblem($model->id);
                    //Добавляем вопрос в общую базу вопросов
                    $confirmProblemNew->addQuestionToGeneralList($model->title);
                    //Передаем обновленный список вопросов для добавления в программу
                    $queryQuestions = $confirmProblemNew->queryQuestionsGeneralList();

                    $project->updated_at = time();
                    $project->save();

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
    public function actionDeleteQuestion($id)
    {
        $model = QuestionsConfirmProblem::findOne($id);
        $confirmProblem = ConfirmProblem::findOne(['id' => $model->confirm_problem_id]);
        $problem = GenerationProblem::findOne(['id' => $confirmProblem->gps_id]);
        $project = Projects::findOne(['id' => $problem->project_id]);

        if(Yii::$app->request->isAjax) {

            if ($model->delete()){

                $project->updated_at = time();
                $project->save();

                $confirmProblemNew = ConfirmProblem::findOne(['id' => $model->confirm_problem_id]);
                $questions = $confirmProblemNew->questions;

                //Удаление ответов по данному вопросу у всех респондентов данного подтверждения
                $confirmProblemNew->deleteAnswerConfirmProblem($id);

                //Передаем обновленный список вопросов для добавления в программу
                $queryQuestions = $confirmProblemNew->queryQuestionsGeneralList();

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
     * @return array
     */
    public function actionUpdate ($id)
    {
        $model = new FormUpdateConfirmProblem($id);
        $generationProblem = GenerationProblem::find()->where(['id' => $model->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model->count_respond >= $model->count_positive && $model->count_positive > 0){

                    if ($confirm_problem = $model->update()){

                        $project->updated_at = time();

                        if ($project->save()){

                            $descInterviews = [];
                            foreach ($confirm_problem->responds as $respond) {
                                if($respond->descInterview) {
                                    $descInterviews[] = $respond->descInterview;
                                }
                            }

                            $response = [
                                'model' => $confirm_problem,
                                'responds' => $confirm_problem->responds,
                                'descInterviews' => $descInterviews,
                                'gcps' => $confirm_problem->gcps,
                                'problem' => $generationProblem,
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
        $model = ConfirmProblem::findOne($id);
        $responds = $model->responds;

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('/confirm-problem/viewpdf', ['model' => $model, 'responds' => $responds]);

        $destination = Pdf::DEST_BROWSER;
        //$destination = Pdf::DEST_DOWNLOAD;

        $problem_desc = $model->problem->description;
        if (mb_strlen($problem_desc) > 25) {
            $problem_desc = mb_substr($problem_desc, 0, 25) . '...';
        }

        $filename = 'Подтверждение проблемы «'.$problem_desc.'». Таблица респондентов.pdf';

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
                'SetTitle' => ['Респонденты для подтверждения гипотезы проблемы «'.$problem_desc.'»'],
                'SetHeader' => ['<div style="color: #3c3c3c;">Респонденты для подтверждения гипотезы проблемы «'.$problem_desc.'»</div>||<div style="color: #3c3c3c;">Сгенерировано: ' . date("H:i d.m.Y") . '</div>'],
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
     * Deletes an existing ConfirmProblem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    /*public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $generationProblem = GenerationProblem::find()->where(['id' => $model->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $responds = RespondsConfirm::find()->where(['confirm_problem_id' => $model->id])->all();
        $project->updated_at = time();
        $user = User::find()->where(['id' => $project->user_id])->one();
        $_user = Yii::$app->user->identity;

        if (!User::isUserDev(Yii::$app->user->identity['username'])) {

            //Удаление доступно только проектанту, который создал данную модель
            if ($user->id != $_user['id']){
                Yii::$app->session->setFlash('error', 'У Вас нет прав на данное действие!');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }


        $gps_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
            mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
            mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/generation problems/'
            . mb_convert_encoding($this->translit($generationProblem->title) , "windows-1251");

        $gps_dir = mb_strtolower($gps_dir, "windows-1251");

        if (file_exists($gps_dir)){
            $this->delTree($gps_dir);
        }

        if ($project->save()){

            foreach ($responds as $respond){

                $descInterview = $respond->descInterview;
                if (!empty($descInterview)){
                    $descInterview->delete();
                }
            }


            RespondsConfirm::deleteAll(['confirm_problem_id' => $id]);
            FeedbackExpertConfirm::deleteAll(['confirm_problem_id' => $id]);

            Yii::$app->session->setFlash('error', "Ваше интервью удалено, создайте новое интервью!");

            $model->delete();

            return $this->redirect(['create', 'id' => $model->gps_id]);
        }
    }*/

    /**
     * Finds the ConfirmProblem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ConfirmProblem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ConfirmProblem::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
