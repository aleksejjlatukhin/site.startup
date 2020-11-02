<?php

namespace app\controllers;

use app\models\ConfirmProblem;
use app\models\DescInterviewGcp;
use app\models\FormUpdateConfirmGcp;
use app\models\Gcp;
use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Projects;
use app\models\QuestionsConfirmGcp;
use app\models\RespondsConfirm;
use app\models\RespondsGcp;
use app\models\Segment;
use app\models\UpdateRespondGcpForm;
use app\models\User;
use kartik\mpdf\Pdf;
use Yii;
use app\models\ConfirmGcp;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;


class ConfirmGcpController extends AppController
{

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\HttpException
     */
    public function beforeAction($action)
    {

        if (in_array($action->id, ['view'])){

            $model = ConfirmGcp::findOne(Yii::$app->request->get());
            $gcp = Gcp::find()->where(['id' => $model->gcp_id])->one();
            $project = Projects::find()->where(['id' => $gcp->project->id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['update']) || in_array($action->id, ['delete'])){

            $model = ConfirmGcp::findOne(Yii::$app->request->get());
            $gcp = Gcp::find()->where(['id' => $model->gcp_id])->one();
            $project = Projects::find()->where(['id' => $gcp->project->id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){

            $gcp = Gcp::findOne(Yii::$app->request->get());
            $project = Projects::find()->where(['id' => $gcp->project->id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['save-confirm-gcp'])){

            $gcp = Gcp::findOne(Yii::$app->request->get());
            $project = Projects::find()->where(['id' => $gcp->project->id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['add-questions'])){

            $model = ConfirmGcp::findOne(Yii::$app->request->get());
            $gcp = Gcp::find()->where(['id' => $model->gcp_id])->one();
            $project = Projects::find()->where(['id' => $gcp->project->id])->one();

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

            $question = QuestionsConfirmGcp::findOne(Yii::$app->request->get());
            $confirm_gcp = ConfirmGcp::find()->where(['id' => $question->confirm_gcp_id])->one();
            $gcp = Gcp::find()->where(['id' => $confirm_gcp->gcp_id])->one();
            $project = Projects::find()->where(['id' => $gcp->project->id])->one();

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
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $formUpdateConfirmGcp = new FormUpdateConfirmGcp($id);
        $gcp = Gcp::find()->where(['id' => $model->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $responds = RespondsGcp::find()->where(['confirm_gcp_id' => $id])->all();
        $questions = QuestionsConfirmGcp::find()->where(['confirm_gcp_id' => $id])->all();

        $newQuestion = new QuestionsConfirmGcp();
        $newQuestion->confirm_gcp_id = $id;

        //Список вопросов для добавления к списку программы
        $queryQuestions = $model->queryQuestionsGeneralList();
        $queryQuestions = ArrayHelper::map($queryQuestions,'title','title');

        $newRespond = new RespondsGcp();
        $newRespond->confirm_gcp_id = $model->id;

        $updateRespondForms = [];
        $createDescInterviewForms = [];
        $updateDescInterviewForms = [];

        foreach ($responds as $i => $respond) {

            $updateRespondForms[] = new UpdateRespondGcpForm($respond->id);

            $createDescInterviewForms[] = new DescInterviewGcp();

            $updateDescInterviewForms[] = $respond->descInterview;
        }


        return $this->render('view', [
            'model' => $model,
            'formUpdateConfirmGcp' => $formUpdateConfirmGcp,
            'gcp' => $gcp,
            'confirmProblem' => $confirmProblem,
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
        $model = ConfirmGcp::findOne($id);

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
            if ((count($model->responds) == $count_descInterview && $model->count_positive <= $count_positive) || (!empty($model->mvps)  && $model->count_positive <= $count_positive)) {

                $response =  ['success' => true];
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
     * Завершение подтверждения ГЦП и переход на следующий этап
     * @param $id
     * @return array
     */
    public function actionMovingNextStage($id)
    {
        $model = ConfirmGcp::findOne($id);
        $gcp = $model->gcp;

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

            if (count($model->responds) > $count_descInterview && empty($model->mvps)) {

                $response = ['not_completed_descInterviews' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;

            }if ((count($model->responds) == $count_descInterview && $model->count_positive <= $count_positive) || (!empty($model->mvps))) {

                $response =  [
                    'success' => true,
                    'exist_confirm' => $gcp->exist_confirm,
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
        $model = ConfirmGcp::find()->where(['id' => $id])->one();
        $gcp = Gcp::find()->where(['id' => $model->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        if ($gcp->exist_confirm === 0) {

            return $this->redirect(['/gcp/index', 'id' => $confirmProblem->id]);
        } else {

            $gcp->exist_confirm = 0;
            $gcp->time_confirm = time();

            if ($gcp->save()){

                $project->updated_at = time();
                if ($project->save()){
                    return $this->redirect(['/gcp/index', 'id' => $confirmProblem->id]);
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
        $model = ConfirmGcp::find()->where(['id' => $id])->one();
        $gcp = Gcp::find()->where(['id' => $model->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $gcp->exist_confirm = 1;
        $gcp->time_confirm = time();

        if ($gcp->save()){

            $project->updated_at = time();
            if ($project->save()){
                return $this->redirect(['/mvp/index', 'id' => $model->id]);
            }
        }
    }


    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionCreate($id)
    {
        $model = new ConfirmGcp();
        $model->gcp_id = $id;

        $gcp = Gcp::find()->where(['id' => $id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $responds = RespondsConfirm::find()->where(['confirm_problem_id' => $confirmProblem->id])->all();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        if (!empty($gcp->confirm)){
            return $this->redirect(['view', 'id' => $gcp->confirm->id]);
        }

        $respondsPre = []; // респонденты, кот-е подтвердили проблему
        foreach ($responds as $respond){
            if ($respond->descInterview->status == 1){
                $respondsPre[] = $respond;
            }
        }

        $model->count_respond = count($respondsPre);

        return $this->render('create', [
            'model' => $model,
            'gcp' => $gcp,
            'confirmProblem' => $confirmProblem,
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
    public function actionSaveConfirmGcp($id)
    {
        $model = new ConfirmGcp();
        $model->gcp_id = $id;

        $gcp = Gcp::find()->where(['id' => $id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $responds = RespondsConfirm::find()->where(['confirm_problem_id' => $confirmProblem->id])->all();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $user = User::find()->where(['id' => $project->user_id])->one();


        if (!empty($gcp->confirm)){
            return $this->redirect(['view', 'id' => $gcp->confirm->id]);
        }

        foreach ($responds as $respond){
            if ($respond->descInterview->status == 1){
                $respondsPre[] = $respond;
            }
        }

        $model->count_respond = count($respondsPre);


        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model->count_respond >= $model->count_positive && $model->count_positive > 0){

                    if ($model->save()) {

                        $feedbacks_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                            mb_convert_encoding($this->translit($project->project_name), "windows-1251") . '/segments/' .
                            mb_convert_encoding($this->translit($segment->name), "windows-1251") . '/generation problems/'
                            . mb_convert_encoding($this->translit($generationProblem->title), "windows-1251") . '/gcps/'
                            . mb_convert_encoding($this->translit($gcp->title), "windows-1251") . '/feedbacks-confirm/';

                        $feedbacks_dir = mb_strtolower($feedbacks_dir, "windows-1251");

                        if (!file_exists($feedbacks_dir)) {
                            mkdir($feedbacks_dir, 0777);
                        }


                        //Создание респондентов для программы подтверждения ГЦП из респондентов подтвердивших проблему
                        $model->createRespondConfirm($responds);

                        //Вопросы, которые будут добавлены по-умолчанию
                        $model->addQuestionDefault('Во сколько обходится эта проблема?');
                        $model->addQuestionDefault('Сколько сейчас платят?');
                        $model->addQuestionDefault('Какой бюджет до этого выделяли?');
                        $model->addQuestionDefault('Что еще пытались сделать?');
                        $model->addQuestionDefault('Заплатили бы вы «X» рублей за продукт, который выполняет задачу «Y»?');
                        $model->addQuestionDefault('Как вы решаете эту проблему сейчас?');
                        $model->addQuestionDefault('Кто будет финансировать покупку?');
                        $model->addQuestionDefault('С кем еще мне следует переговорить?');
                        $model->addQuestionDefault('Решает ли ценностное предложенное вашу проблему?');
                        $model->addQuestionDefault('Вы бы рассказали об этом ценностном предложении своим коллегам?');
                        $model->addQuestionDefault('Вы бы попросили своего руководителя приобрести продукт, который реализует данное ценностное предложение?');


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
                } else {

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
        $confirmGcp = ConfirmGcp::findOne($id);
        $formUpdateConfirmGcp = new FormUpdateConfirmGcp($id);
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $problem = GenerationProblem::findOne(['id' => $confirmProblem->gps_id]);
        $interview = Interview::findOne(['id' => $problem->interview_id]);
        $segment = Segment::findOne(['id' => $problem->segment_id]);
        $project = Projects::findOne(['id' => $problem->project_id]);
        $questions = QuestionsConfirmGcp::find()->where(['confirm_gcp_id' => $id])->all();

        $newQuestion = new QuestionsConfirmGcp();
        $newQuestion->confirm_gcp_id = $id;

        //Список вопросов для добавления к списку программы
        $queryQuestions = $confirmGcp->queryQuestionsGeneralList();
        $queryQuestions = ArrayHelper::map($queryQuestions,'title','title');

        return $this->render('add-questions', [
            'questions' => $questions,
            'formUpdateConfirmGcp' => $formUpdateConfirmGcp,
            'newQuestion' => $newQuestion,
            'queryQuestions' => $queryQuestions,
            'confirmGcp' => $confirmGcp,
            'gcp' => $gcp,
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
        $model = new QuestionsConfirmGcp();
        $model->confirm_gcp_id = $id;
        $confirmGcp = ConfirmGcp::findOne($id);
        $gcp = Gcp::findOne(['id' => $confirmGcp->gcp_id]);
        $project = Projects::findOne(['id' => $gcp->project_id]);


        if ($model->load(Yii::$app->request->post())){

            if(Yii::$app->request->isAjax) {

                if ($model->save()){

                    $confirmGcpNew = ConfirmGcp::findOne($id);
                    $questions = $confirmGcpNew->questions;

                    //Создание пустого ответа для нового вопроса для каждого респондента
                    $confirmGcpNew->addAnswerConfirmGcp($model->id);
                    //Добавляем вопрос в общую базу вопросов
                    $confirmGcpNew->addQuestionToGeneralList($model->title);
                    //Передаем обновленный список вопросов для добавления в программу
                    $queryQuestions = $confirmGcpNew->queryQuestionsGeneralList();

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
        $model = QuestionsConfirmGcp::findOne($id);
        $confirmGcp = ConfirmGcp::find()->where(['id' => $model->confirm_gcp_id])->one();
        $gcp = Gcp::findOne(['id' => $confirmGcp->gcp_id]);
        $project = Projects::findOne(['id' => $gcp->project_id]);

        if(Yii::$app->request->isAjax) {

            if ($model->delete()){

                $project->updated_at = time();
                $project->save();

                $confirmGcpNew = ConfirmGcp::findOne(['id' => $model->confirm_gcp_id]);
                $questions = $confirmGcpNew->questions;

                //Удаление ответов по данному вопросу у всех респондентов данного подтверждения
                $confirmGcpNew->deleteAnswerConfirmGcp($id);

                //Передаем обновленный список вопросов для добавления в программу
                $queryQuestions = $confirmGcpNew->queryQuestionsGeneralList();

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
        $model = new FormUpdateConfirmGcp($id);
        $gcp = Gcp::find()->where(['id' => $model->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();


        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model->count_respond >= $model->count_positive && $model->count_positive > 0){

                    if ($confirm_gcp = $model->update()){

                        $project->updated_at = time();

                        if ($project->save()){

                            $descInterviews = [];
                            foreach ($confirm_gcp->responds as $respond) {
                                if($respond->descInterview) {
                                    $descInterviews[] = $respond->descInterview;
                                }
                            }

                            $response = [
                                'model' => $confirm_gcp,
                                'responds' => $confirm_gcp->responds,
                                'descInterviews' => $descInterviews,
                                'mvps' => $confirm_gcp->mvps,
                                'gcp' => $gcp,
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
        $model = ConfirmGcp::findOne($id);
        $responds = $model->responds;

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('/confirm-gcp/viewpdf', ['model' => $model, 'responds' => $responds]);

        $destination = Pdf::DEST_BROWSER;
        //$destination = Pdf::DEST_DOWNLOAD;

        $gcp_desc = $model->gcp->description;
        if (mb_strlen($gcp_desc) > 25) {
            $gcp_desc = mb_substr($gcp_desc, 0, 25) . '...';
        }

        $filename = 'Подтверждение ЦП: «'.$gcp_desc.'». Таблица респондентов.pdf';

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
                'SetTitle' => ['Респонденты для подтверждения гипотезы ЦП: «'.$gcp_desc.'»'],
                'SetHeader' => ['<div style="color: #3c3c3c;">Респонденты для подтверждения гипотезы ЦП: «'.$gcp_desc.'»</div>||<div style="color: #3c3c3c;">Сгенерировано: ' . date("H:i d.m.Y") . '</div>'],
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
     * Deletes an existing ConfirmGcp model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    /*public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $gcp = Gcp::find()->where(['id' => $model->gcp_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['id' => $gcp->confirm_problem_id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $confirmProblem->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $user = User::find()->where(['id' => $project->user_id])->one();
        $_user = Yii::$app->user->identity;

        if (!User::isUserDev(Yii::$app->user->identity['username'])) {

            //Удаление доступно только проектанту, который создал данную модель
            if ($user->id != $_user['id']){
                Yii::$app->session->setFlash('error', 'У Вас нет прав на данное действие!');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $model->delete();

        return $this->redirect(['index']);
    }*/

    /**
     * Finds the ConfirmGcp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ConfirmGcp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ConfirmGcp::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
