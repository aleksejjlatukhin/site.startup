<?php

namespace app\controllers;

use app\models\AnswersQuestionsConfirmSegment;
use app\models\forms\FormCreateConfirmSegment;
use app\models\forms\FormCreateProblem;
use app\models\forms\FormUpdateConfirmSegment;
use app\models\forms\FormUpdateQuestionConfirmSegment;
use app\models\Projects;
use app\models\QuestionsConfirmSegment;
use app\models\Respond;
use app\models\Segment;
use app\models\User;
use kartik\mpdf\Pdf;
use Yii;
use app\models\Interview;
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

            $question = QuestionsConfirmSegment::findOne(Yii::$app->request->get());
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
        $model = Interview::findOne($id);
        $formUpdateConfirmSegment = new FormUpdateConfirmSegment($id);
        $segment = Segment::findOne(['id' => $model->segment_id]);
        $project = Projects::findOne(['id' => $segment->project_id]);
        $questions = QuestionsConfirmSegment::findAll(['interview_id' => $id]);
        $newQuestion = new QuestionsConfirmSegment();

        //Список вопросов для добавления к списку программы
        $queryQuestions = $model->queryQuestionsGeneralList();
        $queryQuestions = ArrayHelper::map($queryQuestions,'title','title');


        return $this->render('view', [
            'model' => $model,
            'formUpdateConfirmSegment' => $formUpdateConfirmSegment,
            'segment' => $segment,
            'project' => $project,
            'questions' => $questions,
            'newQuestion' => $newQuestion,
            'queryQuestions' => $queryQuestions,
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
        $segment = Segment::findOne(['id' => $model->segment_id]);
        $project = Projects::findOne(['id' => $segment->project_id]);
        $user = User::findOne(['id' => $project->user_id]);
        $formCreateProblem = new FormCreateProblem();
        $cache = Yii::$app->cache;

        $count_descInterview = Respond::find()->with('descInterview')
            ->leftJoin('desc_interview', '`desc_interview`.`respond_id` = `responds`.`id`')
            ->where(['interview_id' => $id])->andWhere(['not', ['desc_interview.id' => null]])->count();

        $count_positive = Respond::find()->with('descInterview')
            ->leftJoin('desc_interview', '`desc_interview`.`respond_id` = `responds`.`id`')
            ->where(['interview_id' => $id, 'desc_interview.status' => '1'])->count();

        if (Yii::$app->request->isAjax) {

            if ((count($model->responds) == $count_descInterview && $model->count_positive <= $count_positive && $model->segment->exist_confirm == 1) || (!empty($model->problems)  && $model->count_positive <= $count_positive && $model->segment->exist_confirm == 1)) {

                $cache->cachePath = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.'/segments/segment-'.$segment->id.'/problems/formCreate/';
                $cache_form_creation = $cache->get('formCreateProblemCache');

                if ($cache_form_creation) {
                    //Заполнение полей модели FormCreateProblem данными из кэша
                    foreach ($cache_form_creation['FormCreateProblem'] as $key => $value) {
                        $formCreateProblem[$key] = $value;
                    }
                }

                $response =  [
                    'success' => true,
                    'renderAjax' => $this->renderAjax('/generation-problem/create', [
                        'interview' => $model,
                        'model' => $formCreateProblem,
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

        $count_descInterview = Respond::find()->with('descInterview')
            ->leftJoin('desc_interview', '`desc_interview`.`respond_id` = `responds`.`id`')
            ->where(['interview_id' => $id])->andWhere(['not', ['desc_interview.id' => null]])->count();

        $count_positive = Respond::find()->with('descInterview')
            ->leftJoin('desc_interview', '`desc_interview`.`respond_id` = `responds`.`id`')
            ->where(['interview_id' => $id, 'desc_interview.status' => '1'])->count();


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


    public function actionSaveCacheCreationForm($id)
    {
        $segment = Segment::findOne($id);
        $project = Projects::findOne(['id' => $segment->project_id]);
        $user = User::findOne(['id' => $project->user_id]);
        $cache = Yii::$app->cache; //Обращаемся к кэшу приложения

        if(Yii::$app->request->isAjax) {

            $data = $_POST; //Массив, который будем записывать в кэш
            $cache->cachePath = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.'/segments/segment-'.$segment->id.'/confirm/formCreateConfirm/';
            $key = 'formCreateConfirmSegmentCache'; //Формируем ключ
            $cache->set($key, $data, 3600*24*30); //Создаем файл кэша на 30дней
        }
    }


    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionCreate($id)
    {
        $segment = Segment::findOne($id);
        $project = Projects::findOne(['id' => $segment->project_id]);
        $user = User::findOne(['id' => $project->user_id]);
        $model = new FormCreateConfirmSegment();
        $cache = Yii::$app->cache;

        $cache->cachePath = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.'/segments/segment-'.$segment->id.'/confirm/formCreateConfirm/';
        $cache_form_creation = $cache->get('formCreateConfirmSegmentCache');

        if ($cache_form_creation) { //Если существует кэш, то добавляем его к полям модели FormCreateConfirmSegment
            foreach ($cache_form_creation['FormCreateConfirmSegment'] as $key => $value) {
                $model[$key] = $value;
            }
        }

        if ($segment->interview){ //Если у сегмента создана программа подтверждения, то перейти на страницу подтверждения
            return $this->redirect(['/interview/view', 'id' => $segment->interview->id]);
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
        $project = Projects::findOne(['id' => $segment->project_id]);
        $user = User::findOne(['id' => $project->user_id]);
        $model = new FormCreateConfirmSegment();
        $model->segment_id = $id;
        $cache = Yii::$app->cache;

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model = $model->create()){

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

                    //Удаление кэша формы создания
                    $cache->cachePath = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.'/segments/segment-'.$segment->id.'/confirm/formCreateConfirm/';
                    if ($cache->exists('formCreateConfirmSegmentCache')) $cache->delete('formCreateConfirmSegmentCache');

                    $response =  ['success' => true, 'id' => $model->id];
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
        $model = Interview::findOne($id);
        $formUpdateConfirmSegment = new FormUpdateConfirmSegment($id);
        $segment = Segment::findOne(['id' => $model->segment_id]);
        $project = Projects::findOne(['id' => $segment->project_id]);
        $questions = QuestionsConfirmSegment::findAll(['interview_id' => $id]);
        $newQuestion = new QuestionsConfirmSegment();
        $newQuestion->interview_id = $id;

        //Список вопросов для добавления к списку программы
        $queryQuestions = $model->queryQuestionsGeneralList();
        $queryQuestions = ArrayHelper::map($queryQuestions,'title','title');

        return $this->render('add-questions', [
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
        $segment = Segment::findOne(['id' => $confirm_segment->segment_id]);
        $project = Projects::findOne(['id' => $segment->project_id]);

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($update_confirm_segment = $model->update()){

                    $response = [
                        'success' => true,
                        'ajax_data_confirm' => $this->renderAjax('ajax_data_confirm', ['model' => $update_confirm_segment, 'formUpdateConfirmSegment' => new FormUpdateConfirmSegment($id), 'project' => $project]),
                    ];
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
        $model = new QuestionsConfirmSegment();
        $model->interview_id = $id;

        if ($model->load(Yii::$app->request->post())){

            if(Yii::$app->request->isAjax) {

                if ($model->save()){

                    $interviewNew = Interview::findOne($id);
                    $questions = $interviewNew->questions;

                    //Создание пустого ответа для нового вопроса для каждого респондента
                    $interviewNew->addAnswerConfirmSegment($model->id);
                    //Добавляем вопрос в общую базу вопросов
                    $interviewNew->addQuestionToGeneralList($model->title);
                    //Передаем обновленный список вопросов для добавления в программу
                    $queryQuestions = $interviewNew->queryQuestionsGeneralList();

                    $response = [
                        'model' => $model,
                        'questions' => $questions,
                        'queryQuestions' => $queryQuestions,
                        'ajax_questions_confirm' => $this->renderAjax('ajax_questions_confirm', ['questions' => $questions]),
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
     * @throws NotFoundHttpException
     */
    public function actionGetQueryQuestions ($id)
    {
        $interview = $this->findModel($id);
        $questions = $interview->questions;

        if(Yii::$app->request->isAjax) {
            $response = ['ajax_questions_confirm' => $this->renderAjax('ajax_questions_confirm', ['questions' => $questions])];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
    }


    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionGetQuestionUpdateForm ($id)
    {
        $model = new FormUpdateQuestionConfirmSegment($id);
        $interview = $this->findModel($model->interview_id);
        $questions = $interview->questions;

        if(Yii::$app->request->isAjax) {

            $response = [
                'ajax_questions_confirm' => $this->renderAjax('ajax_questions_confirm', ['questions' => $questions]),
                'renderAjax' => $this->renderAjax('ajax_form_update_question', ['model' => $model]),
            ];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
    }


    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionUpdateQuestion ($id)
    {
        $model = new FormUpdateQuestionConfirmSegment($id);

        if ($model->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax) {

                if ($model = $model->update()){

                    $interview = $this->findModel($model->interview_id);
                    $questions = $interview->questions;

                    //Добавляем вопрос в общую базу вопросов
                    $interview->addQuestionToGeneralList($model->title);
                    //Передаем обновленный список вопросов для добавления в программу
                    $queryQuestions = $interview->queryQuestionsGeneralList();

                    $response = [
                        'model' => $model,
                        'questions' => $questions,
                        'queryQuestions' => $queryQuestions,
                        'ajax_questions_confirm' => $this->renderAjax('ajax_questions_confirm', ['questions' => $questions]),
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
        $model = QuestionsConfirmSegment::findOne($id);

        if(Yii::$app->request->isAjax) {

            if ($model->delete()){

                $interviewNew = Interview::findOne(['id' => $model->interview_id]);
                $questions = $interviewNew->questions;

                //Удаление ответов по данному вопросу у всех респондентов данного подтверждения
                $interviewNew->deleteAnswerConfirmSegment($id);

                //Передаем обновленный список вопросов для добавления в программу
                $queryQuestions = $interviewNew->queryQuestionsGeneralList();

                $response = [
                    'questions' => $questions,
                    'queryQuestions' => $queryQuestions,
                    'ajax_questions_confirm' => $this->renderAjax('ajax_questions_confirm', ['questions' => $questions]),
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
     * @throws NotFoundHttpException
     */
    public function actionNotExistConfirm($id)
    {
        $model = $this->findModel($id);
        $segment = Segment::findOne(['id' => $model->segment_id]);
        $project = Projects::findOne(['id' => $segment->project_id]);

        if ($segment->exist_confirm === 0) {
            return $this->redirect(['/segment/index', 'id' => $project->id]);

        }else {

            $segment->exist_confirm = 0;
            $segment->time_confirm = time();

            if ($segment->save()){
                $segment->trigger(Segment::EVENT_CLICK_BUTTON_CONFIRM);
                return $this->redirect(['/segment/index', 'id' => $project->id]);
            }
        }
    }


    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionExistConfirm($id)
    {
        $model = $this->findModel($id);
        $segment = Segment::findOne(['id' => $model->segment_id]);

        $segment->exist_confirm = 1;
        $segment->time_confirm = time();

        if ($segment->save()){
            $segment->trigger(Segment::EVENT_CLICK_BUTTON_CONFIRM);
            return $this->redirect(['/generation-problem/index', 'id' => $id]);
        }
    }


    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionGetDataQuestionsAndAnswers($id)
    {
        $model = $this->findModel($id);
        $questions = $model->questions;

        $response = ['ajax_questions_and_answers' => $this->renderAjax('ajax_questions_and_answers', ['questions' => $questions])];
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        \Yii::$app->response->data = $response;
        return $response;

    }


    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Mpdf\MpdfException
     * @throws \setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException
     * @throws \setasign\Fpdi\PdfParser\PdfParserException
     * @throws \setasign\Fpdi\PdfParser\Type\PdfTypeException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionMpdfQuestionsAndAnswers($id)
    {
        $model = $this->findModel($id);
        $questions = $model->questions;

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('/interview/questions_and_answers_pdf', ['questions' => $questions]);

        $destination = Pdf::DEST_BROWSER;
        //$destination = Pdf::DEST_DOWNLOAD;

        $segment_name = $model->segment->name;
        if (mb_strlen($segment_name) > 25) {
            $segment_name = mb_substr($segment_name, 0, 25) . '...';
        }

        $filename = 'Ответы респондентов на вопросы интервью для подтверждения сегмента: «'.$segment_name.'».';

        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            //'format' => Pdf::FORMAT_TABLOID,
            // portrait orientation
            //'orientation' => Pdf::ORIENT_LANDSCAPE,
            'orientation' => Pdf::ORIENT_PORTRAIT,
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
            'marginTop' => 20,
            'marginBottom' => 20,
            'marginFooter' => 5,
            'defaultFont' => 'RobotoCondensed-Light',
            // call mPDF methods on the fly
            'methods' => [
                'SetTitle' => $filename,
                'SetHeader' => ['<div style="color: #3c3c3c;">Ответы респондентов на вопросы интервью. Сегмент: «'.$segment_name.'»</div>||<div style="color: #3c3c3c;">Сгенерировано: ' . date("H:i d.m.Y") . '</div>'],
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
